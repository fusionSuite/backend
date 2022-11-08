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

namespace App\v1\Controllers\Log;

final class Change
{
  use \App\v1\Read;

  /**
   *
   * $old_value / $new_value any (string, integer...), but if object :
   * object   {
   *   item: {
   *     id: number|null,
   *     name: string|null
   *   },
   *   type: {
   *     id: number|null,
   *     name: string|null
   *   }
   * }
   */
  public static function addEntry(
    $model,
    $message,
    $new_value,
    $old_value = null,
    $property = null,
    $set_by_rule = false
  )
  {
    $change = new \App\v1\Models\Log\Change();
    $change->userid = $GLOBALS['user_id'];
    $user = \App\v1\Models\Item::find($GLOBALS['user_id']);
    // Store the name in case the user account deleted later
    if (!is_null($user))
    {
      $change->username = $user->name;
    }
    if (is_object($model))
    {
      $change->model_type = get_class($model);
      $change->model_id = $model->id;
    } else {
      $change->model_type = $model;
      $change->model_id = 0;
    }

    $change->set_by_rule = $set_by_rule;
    if (!is_null($property))
    {
      $change->property_name = $property->name;
      $change->property_id = $property->id;
    }

    // mapping conversion in message
    $changelogs = new self();
    $message = $changelogs->mappingReplace($message, 'old', $old_value, $property, $user);
    $message = $changelogs->mappingReplace($message, 'new', $new_value, $property, $user);

    if (is_object($old_value))
    {
      $change->old_value = json_encode(['id' => $old_value->item->id, 'name' => $old_value->item->name]);
    } else {
      $change->old_value = $old_value;
    }

    if (is_object($new_value))
    {
      $change->new_value = json_encode(['id' => $new_value->item->id, 'name' => $new_value->item->name]);
    } else {
      $change->new_value = $new_value;
    }

    $change->message = $message;
    $change->save();
  }

  /**
   * $type string old|new
   */
  private function mappingReplace($message, $type, $value, $property, $user)
  {
    if (!in_array($type, ['old', 'new']))
    {
      return $message;
    }
    // replace user name
    if (is_object($user))
    {
      $message = str_replace('{username}', $user->name, $message);
    } else {
      $message = str_replace('{username}', 'N/A', $message);
    }

    if (!is_null($property))
    {
      $message = str_replace('{property.id}', $property->id, $message);
      $message = str_replace('{property.name}', $property->name, $message);
    }

    if (is_null($value))
    {
      return $message;
    }

    if (is_object($value))
    {
      if (property_exists($value, 'item'))
      {
        $message = str_replace('{' . $type . '_value.item.id}', $value->item->id, $message);
        $message = str_replace('{' . $type . '_value.item.name}', $value->item->name, $message);
      }
      if (property_exists($value, 'type'))
      {
        $message = str_replace('{' . $type . '_value.type.id}', $value->type->id, $message);
        $message = str_replace('{' . $type . '_value.type.name}', $value->type->name, $message);
      }
    } else {
      $message = str_replace('{' . $type . '_value}', $this->reduceName($value), $message);
    }
    return $message;
  }

  private function reduceName($value)
  {
    if (!is_string($value))
    {
      return $value;
    }
    $name = explode(PHP_EOL, $value);
    return strlen($name[0]) > 50 ? substr($name[0], 0, 50) . '...' : $name[0];
  }
}
