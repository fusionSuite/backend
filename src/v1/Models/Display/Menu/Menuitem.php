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

class Menuitem extends Model
{
  protected $appends = [
    'type',
  ];
  protected $visible = [
    'id',
    'name',
    'icon',
    'type',
    'position',
    'menu_id',
    'created_at',
    'updated_at',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'name'      => 'string',
    'icon'      => 'string',
    'position'  => 'integer'
  ];

  public function getTypeAttribute()
  {
    $type = \App\v1\Models\Config\Type::query()->find($this->attributes['type_id']);
    return $type;
  }
}
