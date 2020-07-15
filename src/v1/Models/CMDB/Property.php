<?php  
/**
 * FusionSuite - Frontend
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
namespace App\v1\Models\CMDB;

use Illuminate\Database\Eloquent\Model as Model;

class Property extends Model
{  

  protected $appends = [
    'listvalues',
    'value',
    'byfusioninventory' 
  ];
  protected $visible = [
    'id', 
    'name',
    'valuetype',
    'listvalues',
    'unit',
    'created_at',
    'updated_at',
    'value',
    'byfusioninventory'
  ];

  protected $hidden = [];
  protected $with = [
  ];

  public function getListvaluesAttribute()
  {
    return [];
  }

  public function getValueAttribute()
  {
    if (isset($this->pivot->value)) {
      return $this->pivot->value;
    }
    return '';
  }

  public function getByfusioninventoryAttribute()
  {
    if (isset($this->pivot->byfusioninventory)) {
      return $this->pivot->byfusioninventory;
    }
    return 0;
  }

  public function listvalues()
  {
    return $this->hasMany('\App\v1\Models\CMDB\Propertylist');
  }

}
