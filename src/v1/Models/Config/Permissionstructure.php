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

class Permissionstructure extends Model
{
  protected $appends = [
    'customs'
  ];

  protected $visible = [
    'id',
    'role',
    'endpoint',
    'view',
    'create',
    'update',
    'softdelete',
    'delete',
    'customs'
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

  public function getRoleAttribute()
  {
    $role = \App\v1\Models\Config\Role::query()->find($this->attributes['role_id']);
    return [
      'id'   => $role->id,
      'name' => $role->name
    ];
  }

  public function getTypeAttribute()
  {
    $type = \App\v1\Models\Config\Type::query()->find($this->attributes['type_id']);
    return [
      'id'           => $type->id,
      'name'         => $type->name,
      'internalname' => $type->internalname
    ];
  }

  public function getCustomsAttribute()
  {
    if (
        $this->attributes['view'] != 'custom'
        && $this->attributes['update'] != 'custom'
        && $this->attributes['softdelete'] != 'custom'
        && $this->attributes['delete'] != 'custom'
    )
    {
      return [];
    }
    return $this->permissionstructurecustoms()->get();
  }

  public function role()
  {
    return $this->belongsTo('App\v1\Models\Config\Role')->withTimestamps();
  }

  public function type()
  {
    return $this->belongsTo('App\v1\Models\Config\Type')->withTimestamps();
  }

  public function permissionstructurecustoms()
  {
    return $this->hasMany('App\v1\Models\Config\Permissionstructurecustom');
  }
}
