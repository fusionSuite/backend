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
    'byfusioninventory',
    'organization'
  ];
  protected $visible = [
    'id',
    'name',
    'internalname',
    'valuetype',
    'listvalues',
    'unit',
    'default',
    'description',
    'canbenull',
    'setcurrentdate',
    'regexformat'
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

  public function getListvaluesAttribute()
  {
    if ($this->valuetype == 'list')
    {
      return $this->listvalues()->get();
    }
    return [];
  }

  public function getOrganizationAttribute()
  {
    $org = \App\v1\Models\Item::find($this->attributes['organization_id']);
    return [
      'id'   => $org->id,
      'name' => $org->name
    ];
  }

  public function getSubOrganizationAttribute($value)
  {
    return boolval($value);
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
      $item = \App\v1\Models\Item::find(intval($this->pivot->value_itemlink));
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
      $valuetype = 'itemlink';
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

  public function getCanbenullAttribute($value)
  {
    return boolval($value);
  }

  public function getSetcurrentdateAttribute($value)
  {
    if (!in_array($this->valuetype, ['date', 'datetime', 'time']))
    {
      return null;
    }
    return boolval($value);
  }

  public function listvalues()
  {
    return $this->hasMany('\App\v1\Models\Config\Propertylist');
  }

  public function scopeofSort($query, $params)
  {
    return \App\v1\Models\Common::scopeofSort($query, $params);
  }
}
