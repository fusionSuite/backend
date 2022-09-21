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

class Type extends Model
{
  use SoftDeletes;

  protected $fillable = ['name', 'internalname'];
  protected $appends = [
    'properties',
    'propertygroups',
    'organization'
  ];
  protected $visible = [
    'id',
    'name',
    'internalname',
    'organization',
    'sub_organization',
    'modeling',
    'properties',
    'propertygroups',
    'tree',
    'allowtreemultipleroots',
    'unique_name',
    'created_at',
    'updated_at',
    'deleted_at',
    'created_by',
    'updated_by',
    'deleted_by',
  ];
  protected $hidden = [];
  protected $with = [
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

    static::deleting(function ($model)
    {
      // Disable timestamp to prevent updating updated_at field when soft delete
      $model->timestamps = false;
      // Update deleted_by to have user id
      DB::table('types')->where('id', $model->id)
        ->update(['deleted_by' => $GLOBALS['user_id']]);
    });

    static::restoring(function ($model)
    {
      $model->deleted_by = null;
    });
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

  public function getTreeAttribute($value)
  {
    return boolval($value);
  }

  public function getAllowtreemultiplerootsAttribute($value)
  {
    return boolval($value);
  }

  public function getPropertiesAttribute()
  {
    return [];
  }

  public function getPropertygroupsAttribute()
  {
    return $this->propertygroups()->get();
  }

  public function getUniqueNameAttribute($value)
  {
    return boolval($value);
  }


  public function properties()
  {
    return $this->belongsToMany('App\v1\Models\Config\Property')->withTimestamps();
  }

  public function propertygroups()
  {
    return $this->hasMany('App\v1\Models\Config\Propertygroup');
  }

  public function structureroles()
  {
      return $this->morphToMany('App\v1\Models\Config\Role', 'permissionstructure')->withTimestamps();
  }

  public function permissiondatas()
  {
    return $this->hasMany('App\v1\Models\Config\Permissiondata')->withTimestamps();
  }

  public function scopeofSort($query, $params)
  {
    return \App\v1\Models\Common::scopeofSort($query, $params);
  }
}
