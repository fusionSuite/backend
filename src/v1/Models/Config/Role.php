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

  protected $with = [
    'created_by.properties',
    'updated_by.properties',
    'deleted_by.properties'
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
      $userList[] = $this->getUserAttributes($user->id);
    }
    return $userList;
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

  public function created_by() // phpcs:ignore
  {
    return $this->belongsTo('App\v1\Models\Useraudit', 'created_by')
    ->with('properties')
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

  public function scopeofSort($query, $params)
  {
    return \App\v1\Models\Common::scopeofSort($query, $params);
  }

  /**
   * Get the user attributes.
   */
  private static function getUserAttributes($user_id)
  {
    if (is_null($user_id))
    {
      return null;
    }
    $user = \App\v1\Models\Useraudit::find($user_id);
    if (is_null($user))
    {
      return [
        'id'         => 0,
        'name'       => 'deleted user',
        'first_name' => '',
        'last_name'  => ''
      ];
    }
    return [
      'id'         => $user->id,
      'name'       => $user->name,
      'first_name' => $user->first_name,
      'last_name'  => $user->last_name
    ];
  }
}
