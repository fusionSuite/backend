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

namespace App\v1\Models\Log;

use Illuminate\Database\Eloquent\Model as Model;

class Audit extends Model
{
  // disable UPDATED_AT because will have only write in this table
  public const UPDATED_AT = null;

  protected $appends = [
    'user'
  ];
  protected $visible = [
    'id',
    'user',
    'username',
    'ip',
    'httpmethod',
    'endpoint',
    'httpcode',
    'action',
    'model',
    'item_id',
    'message',
    'created_at'
  ];

  public function getUserAttribute()
  {
    return \App\v1\Models\Common::getUserAttributes($this->attributes['userid']);
  }

  public function scopeofSort($query, $params)
  {
    return \App\v1\Models\Common::scopeofSort($query, $params);
  }
}
