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

namespace App\v1\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;
use GeneaLabs\LaravelPivotEvents\Traits\PivotEventTrait;

class Item extends Model
{
  use SoftDeletes;
  use PivotEventTrait;

  protected $appends = [
    'changes',
    'type_id',
  ];
  protected $visible = [
    'id',
    'id_bytype',
    'organization',
    'sub_organization',
    'name',
    'parent_id',
    'treepath',
    'properties',
    'propertyItemlink',
    'propertyTypelink',
    'propertyList',
    'propertyPropertylink',
    'created_at',
    'updated_at',
    'deleted_at',
    'created_by',
    'updated_by',
    'deleted_by',
  ];
  protected $hidden = [
  ];
  protected $with = [
    'created_by.properties',
    'updated_by.properties',
    'deleted_by.properties',
    'organization:id,name'
  ];
  protected $fillable = ['name', 'type_id'];

  public $oldProperties = [];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'sub_organization' => 'boolean',
  ];

  public static function boot()
  {
    parent::boot();
    static::creating(function ($model)
    {
      /**
       * Manage id_bytype field
       * Get max id in DB for this type and increase 1 to get new id_bytype
       */
      $model->id_bytype = DB::raw("(SELECT coalesce(max(id_bytype), 0) + 1 as id_bytype " .
                                   "FROM items as item_alias WHERE type_id=" . intval($model->type_id) . ")");
      // manage sub_organization field
      if (!property_exists($model, 'sub_organization'))
      {
        $type = \App\v1\Models\Config\Type::find($model->type_id);
        if ($type->sub_organization)
        {
          $model->sub_organization = true;
        }
      }
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
      DB::table('items')->where('id', $model->id)
        ->update(['deleted_by' => $GLOBALS['user_id']]);
    });

    static::restoring(function ($model)
    {
      $model->deleted_by = null;
    });

    static::pivotUpdating(function ($model, $relationName, $pivotIds)
    {
      foreach ($pivotIds as $propertyId)
      {
        $currentItemProperty = $model->properties()->where('property_id', $propertyId)->first();
        // Special case for itemlink / itemlinks
        if (in_array($currentItemProperty->valuetype, ['itemlink', 'itemlinks']))
        {
          foreach ($currentItemProperty->property_itemlink()->get() as $itemlink)
          {
            if ($itemlink->id == $currentItemProperty->value)
            {
              $model->oldProperties[$propertyId] = $itemlink;
            }
          }
        // Special case for typelink / typelinks
        }
        elseif (in_array($currentItemProperty->valuetype, ['typelink', 'typelinks']))
        {
          foreach ($currentItemProperty->property_typelink()->get() as $typelink)
          {
            if ($typelink->id == $currentItemProperty->value)
            {
              $model->oldProperties[$propertyId] = $typelink;
            }
          }
        // Special case for list
        }
        elseif ($currentItemProperty->valuetype == 'list')
        {
          foreach ($currentItemProperty->property_list()->get() as $list)
          {
            if ($list->id == $currentItemProperty->value)
            {
              $model->oldProperties[$propertyId] = $list;
            }
          }
          // Special case for propertylink
        } elseif ($currentItemProperty->valuetype == 'propertylink')
        {
          foreach ($currentItemProperty->property_propertylink()->get() as $propertylink)
          {
            if ($propertylink->id == $currentItemProperty->value)
            {
              $model->oldProperties[$propertyId] = $propertylink;
            }
          }
        } else {
          $model->oldProperties[$propertyId] = $currentItemProperty->value;
        }
      }
    });
  }

  public static function booted()
  {
    parent::booted();
    static::created(function ($model)
    {
      // check if type is a tree
      $type = \App\v1\Models\Config\Type::find($model->type_id);
      if ($type->tree)
      {
        // It's a tree, manage it here after created the item
        // 1. we get the item created (refresh)
        // 2. if not root (first level), we get the parent item
        // 3. get the parent treepath and add the id_bytype generated to the item created
        // 4. save the item with the treepath
        // we can't do this before, because the id_bypath is generated when insert directly by database (in SQL auery)
        // The treepath is the id_bytype composed by 4 numbers. If have id_bytype 45, it will be 0045.
        // if the parent is 34 and root, the treepath of the current item will be 00340045
        $currItem = (new self())->find($model->id);
        $currItem->treepath = sprintf("%04d", $currItem->id_bytype);
        if (isset($model->parent_id) && !is_null($model->parent_id))
        {
          $parentItem = self::find($model->parent_id);
          $currItem->treepath = $parentItem->treepath . $currItem->treepath;
        }
        $currItem->save();
      }
      // special case for type "organization" (id=1)
      // we put organization_id same as id
      if ($model->type_id == 1)
      {
        $currItem = (new self())->find($model->id);
        $currItem->organization_id = $model->id;
        $currItem->save();
      }
    });

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
      // delete item in properties (valuetype id itemnlink or itemlinks)
      \App\v1\Controllers\Item::deleteItemlinkInProperties($model->id);
    });

    static::pivotUpdated(function ($model, $relationName, $pivotIds, $pivotIdsAttributes)
    {
      \App\v1\Models\Common::changesOnPivotUpdated($model, $pivotIds, $pivotIdsAttributes);
    });

    static::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes)
    {
      \App\v1\Models\Common::changesOnPivotAttached($model, $pivotIds, $pivotIdsAttributes);
    });

    static::pivotDetached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes)
    {
      \App\v1\Models\Common::changesOnPivotDetached($model, $pivotIds, $pivotIdsAttributes);
    });
  }

  public function getPropertiesAttribute()
  {
    return [];
  }

  public function getPropertyItemlinkAttribute()
  {
    return [];
  }

  public function getPropertyTypelinkAttribute()
  {
    return [];
  }

  public function getPropertyListAttribute()
  {
    return [];
  }

  public function getPropertyPropertylinkAttribute()
  {
    return [];
  }

  public function getChildItemsAttribute()
  {
    return [];
  }

  public function getChangesAttribute()
  {
    return $this->changes()->get();
  }

  public function getTypeIdAttribute()
  {
    return $this->attributes['type_id'];
  }

  public function properties()
  {
    return $this->belongsToMany('App\v1\Models\Config\Property')->withPivot(
      'value_integer',
      'value_decimal',
      'value_string',
      'value_text',
      'value_boolean',
      'value_datetime',
      'value_date',
      'value_time',
      'value_number',
      'value_itemlink',
      'value_typelink',
      'value_propertylink',
      'value_list',
      'value_password',
      'value_passwordhash',
      'byfusioninventory'
    )
      ->withTimestamps()
      ->orderByPivot('id', 'asc');
  }

  public function itemlink()
  {
    return $this->belongsToMany('App\v1\Models\Item', 'item_property', 'item_id', 'value_itemlink')->withPivot(
      'property_id',
    )
      ->withTimestamps()
      ->orderByPivot('id', 'asc');
  }

  public function propertiesLinks()
  {
    return $this->belongsToMany('App\v1\Models\Config\Property')->withPivot(
      'id',
      'value_itemlink',
      'value_typelink'
    )->orderByPivot('id', 'asc');
  }

  public function propertyItemlinks($propertyId)
  {
    return $this->belongsToMany('App\v1\Models\Config\Property')->withPivot(
      'id',
      'value_itemlink'
    )
    ->wherePivot('property_id', $propertyId)
    ->orderByPivot('id', 'asc')
    ->withTimestamps();
  }

  public function propertyTypelinks($propertyId)
  {
    return $this->belongsToMany('App\v1\Models\Config\Property')->withPivot(
      'id',
      'value_typelink'
    )
    ->wherePivot('property_id', $propertyId)
    ->orderByPivot('id', 'asc')
    ->withTimestamps();
  }

  public function roles()
  {
    if ($this->attributes['type_id'] != TYPE_USER_ID)
    {
      return null;
    }
    return $this->belongsToMany(\App\v1\Models\Config\Role::class);
  }

  public function organization()
  {
    return $this->belongsTo('App\v1\Models\Item')->without('created_by', 'updated_by', 'deleted_by', 'organization');
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

  public function getItems()
  {
    //  return $this->belongsToMany('App\v1\Models\Item', null, 'parent_item_id', 'child_item_id')
    // ->withPivot(['relationshiptype_id', 'logical', 'physicalinternal', 'propagate'])->withTimestamps();
    return $this->belongsToMany('App\v1\Models\Item', null, 'parent_item_id', 'child_item_id')->withTimestamps();
  }

  /**
   * Get the item's changes.
   */
  public function changes()
  {
    return $this->morphMany(\App\v1\Models\Log\Change::class, 'model')->orderBy('id');
  }

  public function scopeofWhere($query, $params)
  {
  }

  public function scopeofSort($query, $params)
  {
    return \App\v1\Models\Common::scopeofSort($query, $params);
  }

  /**
   * Loop on properties to get the attribute (value, id...) of the property based on the internalname
   */
  public function getPropertyAttribute($internalname, $attribute = 'value')
  {
    foreach ($this->properties()->get() as $property)
    {
      if ($property->internalname == $internalname)
      {
        return $property->{$attribute};
      }
    }
    return null;
  }

  // Make some modification in toArray to have the property* in root into the properties tree
  public function toArray()
  {
    if (isset($this->id)) {
      $data = parent::toArray();

      // Manage it
      if (isset($data['properties']))
      {
        foreach ($data['properties'] as $idxProperties => $property)
        {
          if ($property['valuetype'] == 'itemlinks' || $property['valuetype'] == 'itemlink')
          {
            if (is_numeric($property['value']))
            {
              $data['properties'][$idxProperties]['value'] = $property['property_itemlink'];
            }
          }

          if ($property['valuetype'] == 'typelinks' || $property['valuetype'] == 'typelink')
          {
            if (is_numeric($property['value']))
            {
              $data['properties'][$idxProperties]['value'] = $property['property_typelink'];
            }
          }

          if ($property['valuetype'] == 'list')
          {
            if (is_numeric($property['value']))
            {
              $data['properties'][$idxProperties]['value'] = $property['property_list'];
            }
          }

          if ($property['valuetype'] == 'propertylink')
          {
            if (is_numeric($property['value']))
            {
              $data['properties'][$idxProperties]['value'] = $property['property_propertylink'];
            }
          }

          unset($data['properties'][$idxProperties]['property_itemlink']);
          unset($data['properties'][$idxProperties]['property_typelink']);
          unset($data['properties'][$idxProperties]['property_list']);
          unset($data['properties'][$idxProperties]['property_propertylink']);
        }
      }
      return $data;
    }
    return null;
  }
}
