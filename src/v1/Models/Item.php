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
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model {  

  use SoftDeletes;

  protected $appends = [
    'properties',
    // 'child_items',
    'propertygroups'
  ];
  protected $visible = [
    'id', 
    'name',
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
    return $this->belongsToMany('App\v1\Models\Config\Property')->withPivot('value', 'byfusioninventory')->withTimestamps();
  }

  public function propertygroups()
  {
    // return $this->belongsToMany('App\v1\Models\Config\Propertygroup', 'id', 'type_id');
  }

  public function getItems()
  {
    //  return $this->belongsToMany('App\v1\Models\Item', null, 'parent_item_id', 'child_item_id')->withPivot(['relationshiptype_id', 'logical', 'physicalinternal', 'propagate'])->withTimestamps();
    return $this->belongsToMany('App\v1\Models\Item', null, 'parent_item_id', 'child_item_id')->withTimestamps();
  }


  public function scopeofWhere($query, $params)
  {

  }

  public function scopeofSort($query, $params)
  {
    if (isset($params['ORDER']))
    {
      foreach ($params['ORDER'] as $order) {
        if (strstr($order, ' DESC')) {
          $order = str_replace(' DESC', '', $order);
          if (isset($this->inverseMutators[$order]))
          {
            $order = $this->inverseMutators[$order];
          }
          $query->orderBy($order, 'desc');
        } else {
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
