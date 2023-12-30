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

class Change extends Model
{
  // disable UPDATED_AT because will have only write in this table
  public const UPDATED_AT = null;

  protected $appends = [];
  protected $visible = [
    'id',
    'user',
    'username',
    'set_by_rule',
    'message',
    'old_value',
    'new_value',
    'created_at'
  ];

  protected $with = [
    'user.properties',
  ];

  public function user()
  {
    return $this->belongsTo('App\v1\Models\Useraudit', 'userid')
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

  public function scopeofSort($query, $params)
  {
    return \App\v1\Models\Common::scopeofSort($query, $params);
  }

  /**
   * Get the parent model.
   */
  public function model()
  {
    return $this->morphTo();
  }
}
