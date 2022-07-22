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

class Common
{
  /**
   * Get the user attributes for created_by, udpated_by and deleted_by.
   */
  public static function getUserAttributes($user_id)
  {
    if (is_null($user_id))
    {
      return null;
    }
    $user = \App\v1\Models\Item::withTrashed()->find($user_id);
    if (is_null($user))
    {
      return [
        'id'         => 0,
        'name'       => 'deleted user',
        'first_name' => '',
        'last_name'  => ''
      ];
    }
    $firstName = '';
    $lastName = '';
    foreach ($user->properties()->get() as $test)
    {
      if ($test->internalname == 'userfirstname')
      {
        $firstName = $test->value;
      }
      elseif ($test->internalname == 'userlastname')
      {
        $lastName = $test->value;
      }
    }
    return [
      'id'         => $user->id,
      'name'       => $user->name,
      'first_name' => $firstName,
      'last_name'  => $lastName
    ];
  }

  public static function scopeofSort($query, $params)
  {
    if (isset($params['ORDER']))
    {
      foreach ($params['ORDER'] as $order)
      {
        if (strstr($order, ' DESC'))
        {
          $order = str_replace(' DESC', '', $order);
          $query->orderBy($order, 'desc');
        }
        else
        {
          $query->orderBy($order, 'asc');
        }
      }
    }
    return $query->orderBy('id');
  }
}
