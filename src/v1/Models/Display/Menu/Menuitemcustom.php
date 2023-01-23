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

namespace App\v1\Models\Display\Menu;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class Menuitemcustom extends Model
{
  protected $appends = [
    'menuitem',
  ];
  protected $visible = [
    'id',
    'menuitem',
    'position',
    'user_id',
    'created_at',
    'updated_at',
  ];

  protected $hidden = [
    'user_id'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'position'  => 'integer'
  ];

  public function getMenuitemAttribute()
  {
    $type = \App\v1\Models\Display\Menu\Menuitem::find($this->attributes['menuitem_id']);
    return $type;
  }
}
