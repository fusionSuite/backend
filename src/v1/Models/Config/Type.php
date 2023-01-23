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
use GeneaLabs\LaravelPivotEvents\Traits\PivotEventTrait;

class Type extends Model
{
  use SoftDeletes;
  use PivotEventTrait;

  protected $fillable = ['name', 'internalname'];
  protected $appends = [
    'properties',
    'organization',
    'changes'
  ];
  protected $visible = [
    'id',
    'name',
    'internalname',
    'organization',
    'sub_organization',
    'modeling',
    'properties',
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

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'sub_organization'       => 'boolean',
    'tree'                   => 'boolean',
    'allowtreemultipleroots' => 'boolean',
    'unique_name'            => 'boolean',
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
      // delete in allowedtypes
      \App\v1\Controllers\Config\Property::deleteAllowedtypesByTypeId($model->id);
      // delete items of this type
      \App\v1\Controllers\Item::deleteItemsByTypeId($model->id);
      // delete in menuitems
      \App\v1\Controllers\Display\Menu\Menuitem::deleteItemByTypeId(($model->id));
    });

    static::pivotAttached(function ($model, $modelClassName, $relationName, $pivotIds, $pivotIdsAttributes)
    {
      self::changesOnPivotAttached($model, $pivotIds, $pivotIdsAttributes);
    });

    static::pivotDetached(function ($model, $modelClassName, $relationName, $pivotIds, $pivotIdsAttributes)
    {
      self::changesOnPivotDetached($model, $pivotIds, $pivotIdsAttributes);
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

  public function getPropertiesAttribute()
  {
    return [];
  }

  public function getChangesAttribute()
  {
    return $this->changes()->get();
  }


  public function properties()
  {
    return $this->belongsToMany('App\v1\Models\Config\Property')->withTimestamps();
  }

  public function structureroles()
  {
      return $this->morphToMany('App\v1\Models\Config\Role', 'permissionstructure')->withTimestamps();
  }

  public function permissiondatas()
  {
    return $this->hasMany('App\v1\Models\Config\Permissiondata')->withTimestamps();
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

  public static function changesOnPivotAttached($model, $pivotIds, $pivotIdsAttributes)
  {
    if (isset($GLOBALS['no-changes']) && $GLOBALS['no-changes'])
    {
      return;
    }

    $message = '{username} added the property "{new_value.item.name}"';
    foreach ($pivotIds as $propertyId)
    {
      $property = \App\v1\Models\Config\Property::find($propertyId);
      $newValue = (object)[
        'item' => (object)[
          'id'   => $propertyId,
          'name' => $property->name
        ]
      ];
      \App\v1\Controllers\Log\Change::addEntry(
        $model,
        $message,
        $newValue,
        null
      );
    }
  }

  public static function changesOnPivotDetached($model, $pivotIds, $pivotIdsAttributes)
  {
    if (isset($GLOBALS['no-changes']) && $GLOBALS['no-changes'])
    {
      return;
    }

    $message = '{username} deleted the property "{old_value.item.name}"';
    foreach ($pivotIds as $propertyId)
    {
      $property = \App\v1\Models\Config\Property::find($propertyId);
      $oldValue = (object)[
        'item' => (object)[
          'id'   => $propertyId,
          'name' => $property->name
        ]
      ];
      \App\v1\Controllers\Log\Change::addEntry(
        $model,
        $message,
        null,
        $oldValue
      );
    }

    $message = '{username} deleted "{property.name}"';
  }
}
