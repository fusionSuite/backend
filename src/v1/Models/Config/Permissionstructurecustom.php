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

class Permissionstructurecustom extends Model
{
  protected $appends = [
  ];

  protected $visible = [
    'id',
    'endpoint_id',
    'view',
    'update',
    'softdelete',
    'delete'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'view'       => 'boolean',
    'update'     => 'boolean',
    'softdelete' => 'boolean',
    'delete'     => 'boolean',
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

  public function getPropertyAttribute()
  {
    $property = \App\v1\Models\Config\Property::find($this->attributes['property_id']);
    return [
      'id'           => $property->id,
      'name'         => $property->name,
      'internalname' => $property->internalname
    ];
  }

  public function permissionstructure()
  {
    return $this->belongsTo('App\v1\Models\Config\Permissionstructure')->withTimestamps();
  }
}
