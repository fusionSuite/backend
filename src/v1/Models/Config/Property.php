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
    'changes',
    'itemlinks',
    'property_itemlink',
    'property_typelink',
    'property_list',
    'property_propertylink'
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
    'property_itemlink',
    'property_typelink',
    'property_list',
    'property_propertylink'
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
    'organization:id,name',
    'allowedtypes',
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
      $model->makeHidden('property_itemlink');
      $model->makeHidden('property_typelink');
      $model->makeHidden('property_list');
      $model->makeHidden('property_propertylink');
      \App\v1\Models\Common::changesOnUpdated($model, $model->original);
    });

    static::deleted(function ($model)
    {
      if (!$model->isForceDeleting())
      {
        if ($model->valuetype == 'itemlink')
        {
          $model->value = $model->property_itemlink;
        }
        $model->makeHidden('property_itemlink');
        $model->makeHidden('property_typelink');
        $model->makeHidden('property_list');
        $model->makeHidden('property_propertylink');
        \App\v1\Models\Common::changesOnSoftDeleted($model, $model->original);
      }
    });

    static::restored(function ($model)
    {
      if ($model->valuetype == 'itemlink')
      {
        $model->value = $model->property_itemlink;
      }
      $model->makeHidden('property_itemlink');
      $model->makeHidden('property_typelink');
      $model->makeHidden('property_list');
      $model->makeHidden('property_propertylink');
      \App\v1\Models\Common::changesOnRestored($model, $model->original);
    });

    static::forceDeleted(function ($model)
    {
      if ($model->valuetype == 'itemlink')
      {
        $model->value = $model->property_itemlink;
      }
      $model->makeHidden('property_itemlink');
      $model->makeHidden('property_typelink');
      $model->makeHidden('property_list');
      $model->makeHidden('property_propertylink');
      \App\v1\Models\Common::changesOnDeleted($model, $model->original);
    });
  }

  public function getListvaluesAttribute()
  {
    return [];
  }

  public function getPropertyItemlinkAttribute()
  {
    return null;
  }

  public function getPropertyTypelinkAttribute()
  {
    return null;
  }

  public function getPropertyListAttribute()
  {
    return null;
  }

  public function getPropertyPropertylinkAttribute()
  {
    return null;
  }

  public function getValueAttribute()
  {
    if (
        ($this->valuetype == 'itemlink' || $this->valuetype == 'itemlinks')
        && isset($this->pivot->value_itemlink)
    )
    {
      return $this->pivot->value_itemlink;
    }
    elseif (
        ($this->valuetype == 'typelink' || $this->valuetype == 'typelinks')
        && isset($this->pivot->value_typelink)
    )
    {
      return $this->pivot->value_typelink;
    }
    elseif (
        ($this->valuetype == 'propertylink')
        && isset($this->pivot->value_propertylink)
    )
    {
      return $this->pivot->value_propertylink;
    }
    elseif (
        ($this->valuetype == 'list')
        && isset($this->pivot->value_list)
    )
    {
      return $this->pivot->value_list;
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

  public function getItemlinksAttribute()
  {
    return [];
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
    // case field not get
    if (is_null($this->valuetype))
    {
      return null;
    }
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

  public function allowedtypes()
  {
    return $this->belongsToMany('App\v1\Models\Config\Type', 'propertyallowedtypes')
      ->without('created_by', 'updated_by', 'deleted_by', 'organization', 'properties')
      ->select('types.id', 'types.name', 'types.internalname')
      ->orderByPivot('id', 'asc');
  }

  // Function used with eagerloading to get relationship based on pivot attributes
  public function property_itemlink() // phpcs:ignore
  {
    return $this->belongsTo('App\v1\Models\Item', 'value')
      ->without('created_by', 'updated_by', 'deleted_by', 'organization', 'properties')
      ->withTrashed();
  }

  public function property_typelink() // phpcs:ignore
  {
    return $this->belongsTo('App\v1\Models\Config\Type', 'value')
      ->withTrashed();
  }

  public function property_list() // phpcs:ignore
  {
    return $this->belongsTo('App\v1\Models\Config\Propertylist', 'value');
  }

  public function property_propertylink() // phpcs:ignore
  {
    return $this->belongsTo('App\v1\Models\Config\Property', 'value')
      ->withTrashed();
  }
}
