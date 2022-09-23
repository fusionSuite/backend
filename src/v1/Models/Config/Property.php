<?php

/**
 * FusionSuite - Backend
 * Copyright (C) 2022 FusionSuite
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\v1\Models\Config;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class Property extends Model
{
  use SoftDeletes;

  protected $appends = [
    'listvalues',
    'value',
    'default',
    'allowedtypes',
    'byfusioninventory',
    'changes'
  ];
  protected $visible = [
    'id',
    'name',
    'internalname',
    'valuetype',
    'listvalues',
    'unit',
    'default',
    'allowedtypes',
    'description',
    'canbenull',
    'setcurrentdate',
    'regexformat',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'sub_organization' => 'boolean',
    'canbenull'        => 'boolean',
  ];

  protected $hidden = [];
  protected $with = [
    'organization:id,name'
  ];

  public function __construct(array $attributes = [])
  {
    // Hack to make visible for others than types (so mainly for items)
    if (!strstr($_SERVER['SCRIPT_NAME'], 'config'))
    {
      $this->visible[] = 'byfusioninventory';
      $this->visible[] = 'value';
    }
    parent::__construct($attributes);
  }

  public static function boot()
  {
    parent::boot();
    static::creating(function ($model)
    {
      $model->created_by = $GLOBALS['user_id'];
    });

    static::updating(function ($model)
    {
      $model->updated_by = $GLOBALS['user_id'];
    });

    static::deleting(function ($model)
    {
      // Disable timestamp to prevent updating updated_at field when soft delete
      $model->timestamps = false;
      // Update deleted_by to have user id
      DB::table('properties')->where('id', $model->id)
        ->update(['deleted_by' => $GLOBALS['user_id']]);
    });

    static::restoring(function ($model)
    {
      $model->deleted_by = null;
    });
  }

  public static function booted()
  {
    parent::booted();
    static::updated(function ($model)
    {
      \App\v1\Models\Common::changesOnUpdated($model, $model->original);
    });

    static::deleted(function ($model)
    {
      if (!$model->isForceDeleting())
      {
        \App\v1\Models\Common::changesOnSoftDeleted($model, $model->original);
      }
    });

    static::restored(function ($model)
    {
      \App\v1\Models\Common::changesOnRestored($model, $model->original);
    });

    static::forceDeleted(function ($model)
    {
      \App\v1\Models\Common::changesOnDeleted($model, $model->original);
    });
  }

  public function getListvaluesAttribute()
  {
    return [];
  }

  public function getValueAttribute()
  {
    if (
        ($this->valuetype == 'itemlink' || $this->valuetype == 'itemlinks')
        && isset($this->pivot->value_itemlink)
    )
    {
      $item = \App\v1\Models\Item
        ::with('properties:id,name,internalname,valuetype,unit,organization_id', 'properties.listvalues')
        ->find(intval($this->pivot->value_itemlink));
      return $item;
    }
    elseif (
        ($this->valuetype == 'typelink' || $this->valuetype == 'typelinks')
        && isset($this->pivot->value_typelink)
    )
    {
      $item = \App\v1\Models\Config\Type::find(intval($this->pivot->value_typelink));
      return $item;
    }
    elseif (
        ($this->valuetype == 'propertylink')
        && isset($this->pivot->value_propertylink)
    )
    {
      $item = \App\v1\Models\Config\Property::find(intval($this->pivot->value_propertylink));
      return $item;
    }
    elseif (
        ($this->valuetype == 'list')
        && isset($this->pivot->value_list)
    )
    {
      $item = \App\v1\Models\Config\Propertylist::find(intval($this->pivot->value_list));
      return $item;
    }

    if (isset($this->pivot->{'value_' . $this->valuetype}))
    {
      if ($this->valuetype == 'boolean')
      {
        return boolval($this->pivot->value_boolean);
      }
      elseif ($this->valuetype == 'decimal')
      {
        return floatval($this->pivot->value_decimal);
      }
      elseif ($this->valuetype == 'passwordhash')
      {
        return null;
      }
      elseif ($this->valuetype == 'password' && !is_null($this->pivot->{'value_' . $this->valuetype}))
      {
        return \App\v1\Controllers\Config\Property::decryptMessage($this->pivot->{'value_' . $this->valuetype});
      }
      return $this->pivot->{'value_' . $this->valuetype};
    }
    return null;
  }

  public function getByfusioninventoryAttribute()
  {
    if (isset($this->pivot->byfusioninventory))
    {
      return boolval($this->pivot->byfusioninventory);
    }
    return null;
  }

  public function getDefaultAttribute()
  {
    $valuetype = $this->valuetype;
    if (
        in_array($this->valuetype, ['date', 'datetime', 'time'])
        && $this->setcurrentdate
    )
    {
      return '';
    }
    elseif (is_null($this->{'default_' . $valuetype}))
    {
      return $this->{'default_' . $valuetype};
    }
    if ($this->valuetype == 'itemlinks')
    {
      $items = \App\v1\Models\Config\Propertyitemlink::where('property_id', $this->id)->get();
      $itemlinks = [];
      foreach ($items as $item)
      {
        $itemlinks[] = $item->item_id;
      }
      return $itemlinks;
    }
    elseif ($this->valuetype == 'typelinks')
    {
      $items = \App\v1\Models\Config\Propertytypelink::where('property_id', $this->id)->get();
      $typelinks = [];
      foreach ($items as $item)
      {
        $typelinks[] = $item->type_id;
      }
      return $typelinks;
    }
    elseif ($this->valuetype == 'boolean')
    {
      return boolval($this->{'default_boolean'});
    }
    elseif ($this->valuetype == 'decimal')
    {
      return floatval($this->{'default_decimal'});
    }
    elseif ($this->valuetype == 'password')
    {
      return \App\v1\Controllers\Config\Property::decryptMessage($this->{'default_password'});
    }
    elseif ($this->valuetype == 'passwordhash')
    {
      return null;
    }
    return $this->{'default_' . $valuetype};
  }

  public function getSetcurrentdateAttribute($value)
  {
    if (!in_array($this->valuetype, ['date', 'datetime', 'time']))
    {
      return null;
    }
    return boolval($value);
  }

  public function getChangesAttribute()
  {
    return $this->changes()->get();
  }

  public function getAllowedtypesAttribute()
  {
    $allowedTypes = [];
    if ($this->valuetype == 'itemlink' or $this->valuetype == 'itemlinks')
    {
      $types = \App\v1\Models\Config\Propertyallowedtype::where('property_id', $this->id)->orderBy('id')->get();
      $modelType = new \App\v1\Models\Config\Type();
      foreach ($types as $type)
      {
        $mytype = \App\v1\Models\Config\Type::find($type->type_id);
        if (!is_null($mytype))
        {
          $mytype->makeHidden(
            \App\v1\Common::getFieldsToHide($modelType->getVisible(), ['id', 'name', 'internalname'])
          );
          $allowedTypes[] = $mytype;
        } else {
          // TODO it's a type _id deleted, must never go here
        }
      }
    }
    return $allowedTypes;
  }

  public function listvalues()
  {
    return $this->hasMany('\App\v1\Models\Config\Propertylist');
  }

  public function structureroles()
  {
      return $this->morphToMany('App\v1\Models\Config\Role', 'permissionstructure')->withTimestamps();
  }

  /**
   * Get the item's changes.
   */
  public function changes()
  {
    return $this->morphMany(\App\v1\Models\Log\Change::class, 'model')->orderBy('id');
  }

  public function scopeofSort($query, $params)
  {
    return \App\v1\Models\Common::scopeofSort($query, $params);
  }

  public function organization()
  {
    return $this->belongsTo('App\v1\Models\Item')->without('created_by', 'updated_by', 'deleted_by', 'organization');
  }

  public function created_by() // phpcs:ignore
  {
    return $this->belongsTo('App\v1\Models\Useraudit', 'created_by')
      ->with('properties')
      ->without('created_by', 'updated_by', 'deleted_by', 'organization')
      ->without('properties:created_by,updated_by,deleted_by,organization')
      ->withDefault(function ($user, $item)
      {
        if (is_numeric($item->created_by))
        {
          $user->id         = 0;
          $user->name       = 'deleted user';
          $user->first_name = '';
          $user->last_name  = '';
        }
      });
  }

  public function updated_by() // phpcs:ignore
  {
    return $this->belongsTo('App\v1\Models\Useraudit', 'updated_by')
      ->with('properties')
      ->without('created_by', 'updated_by', 'deleted_by', 'organization')
      ->without('properties:created_by,updated_by,deleted_by,organization')
      ->withDefault(function ($user, $item)
      {
        if (is_numeric($item->original['updated_by']))
        {
          $user->id         = 0;
          $user->name       = 'deleted user';
          $user->first_name = '';
          $user->last_name  = '';
        }
      });
  }

  public function deleted_by() // phpcs:ignore
  {
    return $this->belongsTo('App\v1\Models\Useraudit', 'deleted_by')
    ->with('properties')
    ->without('created_by', 'updated_by', 'deleted_by', 'organization')
    ->without('properties:created_by,updated_by,deleted_by,organization')
    ->withDefault(function ($user, $item)
    {
      if (is_numeric($item->deleted_by))
      {
        $user->id         = 0;
        $user->name       = 'deleted user';
        $user->first_name = '';
        $user->last_name  = '';
      }
    });
  }
}
