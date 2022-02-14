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

class Propertylist extends Model {  

  protected $appends = [];
  protected $visible = [
    'id', 
    'value',
    'listvalues',
    'unit',
    'property_id',
    'created_at',
    'updated_at'
  ];
  protected $hidden = [
    'is_type',
  ];

}
