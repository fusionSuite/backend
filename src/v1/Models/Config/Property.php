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
    'organization',
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
    'regexformat'
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
  protected $with = [];

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
        \App\v1\Models\Common::changesOnSoftDeleted($model);
      }
    });

    static::restored(function ($model)
    {
      \App\v1\Models\Common::changesOnRestored($model);
    });

    static::forceDeleted(function ($model)
    {
      \App\v1\Models\Common::changesOnDeleted($model, $model->original);
    });
  }

  public function getListvaluesAttribute()
  {
    if ($this->valuetype == 'list')
    {
      return $this->listvalues()->get();
    }
    elseif ($this->valuetype == 'itemlink' || $this->valuetype == 'itemlinks')
    {
    }
    elseif ($this->valuetype == 'typelink' || $this->valuetype == 'typelinks')
    {
    }
    elseif ($this->valuetype == 'propertylink')
    {
    }
    return [];
  }

  public function getOrganizationAttribute()
  {
    $org = \App\v1\Models\Item::query()->find($this->attributes['organization_id']);
    return [
      'id'   => $org->id,
      'name' => $org->name
    ];
  }

  public function getCreatedByAttribute($value)
  {
    return \App\v1\Models\Common::getUserAttributes($value);
  }

  public function getUpdatedByAttribute($value)
  {
    return \App\v1\Models\Common::getUserAttributes($value);
  }

  public function getDeletedByAttribute($value)
  {
    return \App\v1\Models\Common::getUserAttributes($value);
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
      $item = \App\v1\Models\Config\Type::query()->find(intval($this->pivot->value_typelink));
      return $item;
    }
    elseif (
        ($this->valuetype == 'propertylink')
        && isset($this->pivot->value_propertylink)
    )
    {
      $item = \App\v1\Models\Config\Property::query()->find(intval($this->pivot->value_propertylink));
      return $item;
    }
    elseif (
        ($this->valuetype == 'list')
        && isset($this->pivot->value_list)
    )
    {
      $item = \App\v1\Models\Config\Propertylist::query()->find(intval($this->pivot->value_list));
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
        $mytype = \App\v1\Models\Config\Type::query()->find($type->type_id);
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
}
