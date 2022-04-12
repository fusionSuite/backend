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

namespace App\v1;

/**
 * @apiDefine AutorizationHeader
 * @apiError (Error 401) AutorizationFailure The JWT token is not valid.
 *
 * @apiHeader {String} Authorization The JWT token.
 * @apiHeaderExample {Header} Header-Example
 *     "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs"
 *
 * @apiError (Error 401) AutorizationFailure The JWT token is not valid.
 *
 * @apiErrorExample {json} (Error 401) Error-Response:
 *     HTTP/1.1 401 Not Found
 *     {
 *         "status": "error",
 *         "message": "Signature verification of the token failed"
 *     }
 */

use Rakit\Validation\Validator;

class Common
{
  public static function getFieldsToHide($allFields, $visibles)
  {
    $fieldsToHide = [];
    foreach ($allFields as $field)
    {
      if (!in_array($field, $visibles))
      {
        $fieldsToHide[] = $field;
      }
    }
    return $fieldsToHide;
  }

  public static function checkValueRight($value, $type, $allowedValues = [], $model = null)
  {
    if ($type == 'foreignkey')
    {
      if (
          in_array(null, $allowedValues)
          && is_null($value)
      )
      {
        return true;
      }
      if (gettype($value) != 'integer')
      {
        return false;
      }
      elseif (in_array($value, $allowedValues))
      {
        return true;
      }
      $select  = call_user_func_array('\App\v1\Models\\' . $model . '::select', ['id']);
      if ($select->where('id', $value)->exists() === false)
      {
        return false;
      }
    }
    else {
      if (gettype($value) != $type)
      {
        return false;
      }

      if (
          !empty($allowedValues)
          && in_array($value, $allowedValues) === false
          && $type != 'array'
      )
      {
        return false;
      }
    }
    return true;
  }

  /**
   * This function is used to validate the data format (for API)
   * and catch an exception if not validate
   */
  public static function validateData($dataStream, $dataFormat)
  {
    $data = [];
    foreach ($dataFormat as $fieldName => $rule)
    {
      if (\App\v1\Post::postHasProperties($dataStream, [$fieldName]))
      {
        $data[$fieldName] = $dataStream->{$fieldName};
      }
    }

    $validator = new Validator();
    $validator->addValidator('type', new \App\v1\Validator\Type());
    $validator->addValidator('dateformat', new \App\v1\Validator\Dateformat());
    $validator->addValidator('datetimeformat', new \App\v1\Validator\Datetimeformat());
    $validator->addValidator('timeformat', new \App\v1\Validator\Timeformat());
    $validator->addValidator('maxchars', new \App\v1\Validator\Maxchars());
    $validator->addValidator('minchars', new \App\v1\Validator\Minchars());
    $validation = $validator->validate($data, $dataFormat);

    // manage errors
    $errors = $validation->errors();
    if ($errors->count() > 0)
    {
      // Manage post item with each property to give more precision to the error
      if (count($data) == 2 && isset($data['property_id']))
      {
        $errorValue = implode(', ', $errors->all());
        $property = \App\v1\Models\Config\Property::find($data['property_id']);
        if (!is_null($property))
        {
          $errorValue .= ' (property ' . $property->name . ' - ' . $data['property_id'] . ')';
        }
        throw new \Exception($errorValue, 400);
      }
      throw new \Exception(implode(', ', $errors->all()), 400);
    }
  }
}
