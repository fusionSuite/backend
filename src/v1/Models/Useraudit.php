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

// this model is only used to get user simple attributes
namespace App\v1\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Useraudit extends Model
{
  protected $table = 'items';
  protected $appends = [
    'first_name',
    'last_name'
  ];
  protected $visible = [
    'id',
    'name',
    'first_name',
    'last_name',
    'displayname',
  ];

  protected $hidden = [];

  protected $with = ['properties'];

  public function getFirstNameAttribute()
  {
    foreach ($this->properties as $prop)
    {
      if ($prop->internalname == 'userfirstname')
      {
        return $prop->value;
      }
    }
    return '';
  }

  public function getLastNameAttribute()
  {
    foreach ($this->properties as $prop)
    {
      if ($prop->internalname == 'userlastname')
      {
        return $prop->value;
      }
    }
    return '';
  }

  public function properties()
  {
    return $this->belongsToMany('App\v1\Models\Config\Property', 'item_property', 'item_id')->withPivot(
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

  // Rewrite toArray to return null if not find the Id of user (user deleted)
  public function toArray()
  {
    if (isset($this->id)) {
      return parent::toArray();
    }
    return null;
  }
}
