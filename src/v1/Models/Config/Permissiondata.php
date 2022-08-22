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

class Permissiondata extends Model
{
  protected $appends = [
    'type',
    'properties'
  ];

  protected $visible = [
    'id',
    'role',
    'type',
    'view',
    'create',
    'update',
    'softdelete',
    'delete',
    'propertiescustom',
    'properties'
  ];

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
  }

  public function getViewAttribute($value)
  {
    return boolval($value);
  }

  public function getCreateAttribute($value)
  {
    return boolval($value);
  }

  public function getUpdateAttribute($value)
  {
    return boolval($value);
  }

  public function getSoftdeleteAttribute($value)
  {
    return boolval($value);
  }

  public function getDeleteAttribute($value)
  {
    return boolval($value);
  }

  public function getPropertiescustomAttribute($value)
  {
    return boolval($value);
  }

  public function getRoleAttribute()
  {
    $role = \App\v1\Models\Config\Role::find($this->attributes['role_id']);
    return [
      'id'   => $role->id,
      'name' => $role->name
    ];
  }

  public function getTypeAttribute()
  {
    $type = \App\v1\Models\Config\Type::find($this->attributes['type_id']);
    return [
      'id'           => $type->id,
      'name'         => $type->name,
      'internalname' => $type->internalname
    ];
  }

  public function getPropertiesAttribute()
  {
    if (!$this->attributes['propertiescustom'])
    {
      return [];
    }
    return $this->permissiondataproperties()->get();
  }

  public function role()
  {
    return $this->belongsTo('App\v1\Models\Config\Role')->withTimestamps();
  }

  public function type()
  {
    return $this->belongsTo('App\v1\Models\Config\Type')->withTimestamps();
  }

  public function permissiondataproperties()
  {
    return $this->hasMany('App\v1\Models\Config\Permissiondataproperty');
  }
}
