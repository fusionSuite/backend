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

class Item extends Model
{
  use SoftDeletes;

  protected $appends = [
    'properties',
    // 'child_items',
    'propertygroups'
  ];
  protected $visible = [
    'id',
    'id_bytype',
    'name',
    'parent_id',
    'treepath',
    'properties',
    'created_at',
    'updated_at'
  ];
  protected $hidden = [
    //  'child_items'
  ];
  protected $with = [
    // 'getItems'
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
    });
  }

  public function getPropertiesAttribute()
  {
    return [];
  }

  public function getPropertygroupsAttribute()
  {
    return \App\v1\Models\Config\Type::find($this->attributes['type_id'])->propertygroups()->get();
  }

  public function getChildItemsAttribute()
  {
    return [];
    return $this->getItems()->get()->makeHidden(['properties']);
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
    )->withTimestamps()->orderByPivot('id', 'asc');
  }

  public function propertiesLinks()
  {
    return $this->belongsToMany('App\v1\Models\Config\Property')->withPivot(
      'id',
      'value_itemlink',
      'value_typelink'
    )->orderByPivot('id', 'asc');
  }

  public function propertygroups()
  {
    // return $this->belongsToMany('App\v1\Models\Config\Propertygroup', 'id', 'type_id');
  }

  public function getItems()
  {
    //  return $this->belongsToMany('App\v1\Models\Item', null, 'parent_item_id', 'child_item_id')
    // ->withPivot(['relationshiptype_id', 'logical', 'physicalinternal', 'propagate'])->withTimestamps();
    return $this->belongsToMany('App\v1\Models\Item', null, 'parent_item_id', 'child_item_id')->withTimestamps();
  }

  public function scopeofWhere($query, $params)
  {
  }

  public function scopeofSort($query, $params)
  {
    if (isset($params['ORDER']))
    {
      foreach ($params['ORDER'] as $order)
      {
        if (strstr($order, ' DESC'))
        {
          $order = str_replace(' DESC', '', $order);
          if (isset($this->inverseMutators[$order]))
          {
            $order = $this->inverseMutators[$order];
          }
          $query->orderBy($order, 'desc');
        }
        else
        {
          if (isset($this->inverseMutators[$order]))
          {
            $order = $this->inverseMutators[$order];
          }
          $query->orderBy($order, 'asc');
        }
      }
    }
    return $query->orderBy('id');
  }
}
