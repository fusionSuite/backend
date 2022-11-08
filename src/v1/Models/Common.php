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
   * Get the user attributes for created_by, updated_by and deleted_by.
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

  /**
   * Add in changes when update fields
   */
  public static function changesOnUpdated($model, $original)
  {
    $changes = $model->getChanges();
    $casts = $model->getCasts();
    foreach ($changes as $key => $newValue)
    {
      if (in_array($key, ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by']))
      {
        continue;
      }
      $oldValue = $original[$key];
      if (isset($casts[$key]) && $casts[$key] == 'boolean')
      {
        $newValue = (boolval($newValue) ? 'true' : 'false');
        $oldValue = (boolval($oldValue) ? 'true' : 'false');
      }
      \App\v1\Controllers\Log\Change::addEntry(
        $model,
        '{username} changed ' . $key . ' to "{new_value}"',
        $newValue,
        $oldValue,
      );
    }
  }

  public static function changesOnDeleted($model, $original)
  {
    \App\v1\Controllers\Log\Change::addEntry(
      $model,
      '{username} deleted this item',
      'hard delete',
      $model->toJson(),
    );
  }

  public static function changesOnSoftDeleted($model)
  {
    \App\v1\Controllers\Log\Change::addEntry(
      $model,
      '{username} soft deleted this item',
      'soft delete',
      '',
    );
  }

  public static function changesOnRestored($model)
  {
    \App\v1\Controllers\Log\Change::addEntry(
      $model,
      '{username} restored this item',
      'restored',
      '',
    );
  }

  public static function changesOnPivotUpdated($model, $pivotIds, $pivotIdsAttributes)
  {
    if (isset($GLOBALS['no-changes']) && $GLOBALS['no-changes'])
    {
      return;
    }
    foreach ($pivotIds as $propertyId)
    {
      $property = \App\v1\Models\Config\Property::find($propertyId);
      // Special case for refreshtoken to not put in changes
      if ($property->internalname == 'userrefreshtoken') {
        continue;
      }
      $newValue = array_values($pivotIdsAttributes[$propertyId])[0];
      $oldValue = null;
      if (isset($model->oldProperties[$propertyId]))
      {
        $oldValue = $model->oldProperties[$propertyId];
      }
      $message = '{username} changed ' . strtolower($property->name) . ' to "{new_value}"';

      switch ($property->valuetype) {
        case 'boolean':
          if (!is_null($newValue))
          {
            $newValue = (boolval($newValue) ? 'true' : 'false');
          }
          if (!is_null($oldValue))
          {
            $oldValue = (boolval($oldValue) ? 'true' : 'false');
          }
            break;

        case 'list':
          if (!is_null($newValue))
          {
            $propertylist = $property->listvalues()->where('id', $newValue)->first();
            $newValue = $propertylist->value;
          }
          if (!is_null($oldValue))
          {
            $oldValue = $oldValue->value;
          }
            break;

        case 'itemlink':
          if (!is_null($newValue))
          {
            $itemlink = \App\v1\Models\Item::find($newValue);
            $type = \App\v1\Models\Config\Type::find($itemlink->type_id);
            $newValue = (object)[
              'item' => (object)[
                'id'   => $itemlink->id,
                'name' => $itemlink->name
              ],
              'type' => (object)[
                'id'   => $type->id,
                'name' => $type->name
              ],
            ];
          }

          if (!is_null($oldValue))
          {
            $type = \App\v1\Models\Config\Type::find($oldValue->type_id);
            $oldValue = (object)[
              'item' => (object)[
                'id'   => $oldValue->id,
                'name' => $oldValue->name
              ],
              'type' => (object)[
                'id'   => $type->id,
                'name' => $type->name
              ],
            ];
          }
          $message = '{username} changed "{property.name}" to "{new_value.item.name}"';
            break;

        case 'propertylink':
          if (!is_null($newValue))
          {
            $propertylink = \App\v1\Models\Config\Property::find($newValue);
            $newValue = (object)[
              'item' => (object)[
                'id'   => $propertylink->id,
                'name' => $propertylink->name
              ],
            ];
          }

          if (!is_null($oldValue))
          {
            $propertylink = $oldValue;
            $oldValue = (object)[
              'item' => (object)[
                'id'   => $propertylink->id,
                'name' => $propertylink->name
              ],
            ];
          }
          $message = '{username} changed "{property.name}" to "{new_value.item.name}"';
            break;

        case 'typelink':
          if (!is_null($newValue))
          {
            $typelink = \App\v1\Models\Config\Type::find($newValue);
            $newValue = (object)[
              'item' => (object)[
                'id'   => $typelink->id,
                'name' => $typelink->name
              ],
              'type' => (object)[
                'id'   => $typelink->id,
                'name' => $typelink->name
              ],
            ];
          }

          if (!is_null($oldValue))
          {
            $typelink = $oldValue;
            $oldValue = (object)[
              'item' => (object)[
                'id'   => $typelink->id,
                'name' => $typelink->name
              ],
              'type' => (object)[
                'id'   => $typelink->id,
                'name' => $typelink->name
              ],
            ];
          }
          $message = '{username} changed "{property.name}" to "{new_value.item.name}"';
            break;
      }

      \App\v1\Controllers\Log\Change::addEntry(
        $model,
        $message,
        $newValue,
        $oldValue,
        $property
      );
    }
  }

  public static function changesOnPivotAttached($model, $pivotIds, $pivotIdsAttributes)
  {
    if (isset($GLOBALS['no-changes']) && $GLOBALS['no-changes'])
    {
      return;
    }
    foreach ($pivotIds as $propertyId)
    {
      $property = \App\v1\Models\Config\Property::find($propertyId);
      // Special case for refreshtoken to not put in changes
      if ($property->internalname == 'userrefreshtoken') {
        continue;
      }
      $newValue = array_values($pivotIdsAttributes[$propertyId])[0];
      $oldValue = null;
      if (isset($model->oldProperties[$propertyId]))
      {
        $oldValue = $model->oldProperties[$propertyId];
      }
      $message = '{username} added "{property.name}" to "{new_value}"';

      switch ($property->valuetype) {
        case 'boolean':
          if (!is_null($newValue))
          {
            $newValue = (boolval($newValue) ? 'true' : 'false');
          }
          if (!is_null($oldValue))
          {
            $oldValue = (boolval($oldValue) ? 'true' : 'false');
          }
            break;

        case 'list':
          $propertylist = $property->listvalues()->where('id', $newValue->value)->first();
          $newValue->value = $propertylist->value;
          $oldValue->value = $oldValue->value->value;
            break;

        case 'itemlink':
          $itemlink = \App\v1\Models\Item::find($newValue);
          $type = \App\v1\Models\Config\Type::find($itemlink->type_id);
          $newValue = (object)[
            'item' => (object)[
              'id'   => $itemlink->id,
              'name' => $itemlink->name
            ],
            'type' => (object)[
              'id'   => $type->id,
              'name' => $type->name
            ],
          ];
          $message = '{username} changed "{property.name}" to "{new_value.item.name}"';
            break;

        case 'itemlinks':
          if (is_null($newValue))
          {
            $newValue = null;
            $message = '{username} added null to "{property.name}"';
          } else {
            $itemlink = \App\v1\Models\Item::find($newValue);
            $type = \App\v1\Models\Config\Type::find($itemlink->type_id);
            $newValue = (object)[
              'item' => (object)[
                'id'   => $itemlink->id,
                'name' => $itemlink->name
              ],
              'type' => (object)[
                'id'   => $type->id,
                'name' => $type->name
              ],
            ];
            $message = '{username} added "{new_value.item.name}" ({new_value.type.name}) to "{property.name}"';
          }
            break;

        case 'typelinks':
          if (is_null($newValue))
          {
            $newValue = null;
            $message = '{username} added null to "{property.name}"';
          } else {
            $typelink = \App\v1\Models\Config\Type::find($newValue);
            $newValue = (object)[
              'item' => (object)[
                'id'   => $typelink->id,
                'name' => $typelink->name
              ],
              'type' => (object)[
                'id'   => $typelink->id,
                'name' => $typelink->name
              ],
            ];
            $message = '{username} added "{new_value.type.name}" to "{property.name}"';
          }
            break;
      }

      \App\v1\Controllers\Log\Change::addEntry(
        $model,
        $message,
        $newValue,
        $oldValue,
        $property
      );
    }
  }

  public static function changesOnPivotDetached($model, $pivotIds, $pivotIdsAttributes)
  {
    if (isset($GLOBALS['no-changes']) && $GLOBALS['no-changes'])
    {
      return;
    }
    foreach ($pivotIds as $propertyId)
    {
      $property = \App\v1\Models\Config\Property::find($propertyId);
      // Special case for refreshtoken to not put in changes
      if ($property->internalname == 'userrefreshtoken') {
        continue;
      }
      $newValue = null;
      $oldValue = null;
      if (isset($model->oldProperties[$propertyId]))
      {
        $oldValue = $model->oldProperties[$propertyId];
      }
      $message = '{username} deleted "{old_value}" (property.name)';

      switch ($property->valuetype) {
        case 'boolean':
          if (!is_null($newValue))
          {
            $newValue = (boolval($newValue) ? 'true' : 'false');
          }
          if (!is_null($oldValue))
          {
            $oldValue = (boolval($oldValue) ? 'true' : 'false');
          }
            break;

        case 'list':
          // $propertylist = $property->listvalues()->where('id', $newValue->value)->first();
          // $newValue->value = $propertylist->value;
          // $oldValue->value = $oldValue->value->value;
            break;

        case 'itemlink':
          $itemlink = \App\v1\Models\Item::find($newValue);
          $type = \App\v1\Models\Config\Type::find($itemlink->type_id);
          $newValue = (object)[
            'item' => [
              'id'   => $itemlink->id,
              'name' => $itemlink->name
            ],
            'type' => [
              'id'   => $type->id,
              'name' => $type->name
            ],
          ];
          $message = '{username} changed "{property.name}" to "{new_value.item.name}"';
            break;

        case 'itemlinks':
          $newValue = \App\v1\Models\Item::find($newValue);
          $message = '{username} deleted property "{property.name}" named "{old_value.item.name}"';
            break;

        case 'typelinks':
          $newValue = \App\v1\Models\Config\Type::find($newValue);
          $message = '{username} deleted property "{property.name}" named "{old_value.type.name}"';
            break;
      }

      \App\v1\Controllers\Log\Change::addEntry(
        $model,
        $message,
        $newValue,
        $oldValue,
        $property
      );
    }
  }
}
