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
use Illuminate\Database\Eloquent\Builder;

class Role extends Model
{
  use SoftDeletes;

  protected $appends = [
    'users',
    'permissiondatas',
    'permissionstructures'
  ];

  protected $visible = [
    'id',
    'name',
    'permissionstructure',
    'permissiondata',
    'users',
    'permissiondatas',
    'permissionstructures',
    'created_at',
    'updated_at',
    'deleted_at',
    'created_by',
    'updated_by',
    'deleted_by'
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

    static::deleting(function ($model)
    {
      // Disable timestamp to prevent updating updated_at field when soft delete
      $model->timestamps = false;
      // Update deleted_by to have user id
      DB::table('roles')->where('id', $model->id)
        ->update(['deleted_by' => $GLOBALS['user_id']]);
    });

    static::restoring(function ($model)
    {
      $model->deleted_by = null;
    });
  }

  public function getUsersAttribute()
  {
    $userList = [];
    $users = $this->users()->get();
    foreach ($users as $user)
    {
      $userList[] = \App\v1\Models\Common::getUserAttributes($user->id);
    }
    return $userList;
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

  public function getPermissiondatasAttribute()
  {
    return $this->permissiondatas()->get();
  }

  public function getPermissionstructuresAttribute()
  {
    return $this->permissionstructures()->get();
  }

  public function users()
  {
    return $this->belongsToMany('App\v1\Models\Item')->withTimestamps()->orderByPivot('id', 'asc');
  }

  public function permissiondatas()
  {
    return $this->hasMany('App\v1\Models\Config\Permissiondata');
  }

  public function permissionstructures()
  {
    return $this->hasMany('App\v1\Models\Config\Permissionstructure');
  }

  public function scopeofSort($query, $params)
  {
    return \App\v1\Models\Common::scopeofSort($query, $params);
  }
}
