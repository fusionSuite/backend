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

namespace App\v1\Controllers\Rules;

final class GetType
{
  public static function runRules($inventoryData)
  {
    $ruler   = new \Hoa\Ruler\Ruler();
    $context = new \Hoa\Ruler\Context();
    $context['inventoryData'] = function () use ($inventoryData)
    {
      return $inventoryData->{'content'};
    };

    $ruler->getDefaultAsserter()->setOperator('searchvalue', function ($inventoryData, $field, $value)
    {
      $keys = explode(".", $field);
      $path = $inventoryData;
      foreach ($keys as $key)
      {
        if (!isset($path->{$key}))
        {
          return false;
        }
        $path = $path->{$key};
      }
      if (is_string($path) && strtolower($path) == strtolower($value))
      {
        return true;
      }
      return false;
    });

    // get all rules
    $rules = \App\v1\Models\Rule::query()->where('type', 'fusioninventorygettype')->with('criteria', 'actions')->get();
    foreach ($rules as $rule)
    {
      $criteria = [];
      foreach ($rule->criteria as $criterium)
      {
        $criteria[] = 'searchvalue(inventoryData, "' . $criterium->field . '", "' .
                      json_decode($criterium->values, true)[0] . '")';
      }
      // $model = \Hoa\Ruler\Ruler::interpret(implode(' and ', $criteria));
      if ($ruler->assert(implode(' and ', $criteria), $context))
      {
        foreach ($rule->actions as $action)
        {
          return $action->values;
        }
      }
    }
    return false;
  }

  public function serializeRule($ruleId)
  {
    $item = \App\v1\Models\Rule::query()->find($ruleId);
    if (is_null($item))
    {
      return null;
    }
    $criteria = \App\v1\Models\Rule::query()->find($ruleId)->criteria()->get();

    // criteria
    $crits = [];

    foreach ($criteria as $criterium)
    {
      $crits[] = $criterium->field . " " . $criterium->comparator . " '" . $criterium->values . "'";
    }
    if (count($crits) == 0)
    {
      return null;
    }

    /**
     * TODO this is a list of possible rules (see https://hoa-project.net/En/Literature/Hack/Ruler.html#Grammar)
     *
     * 'foo', "foo", 'f\'oo' strings
     * true, false, null pre-defined constants
     * 4.2 a real
     * 42 an integer
     * ['foo', true, 4.2, 42] an array (heterogeneous)
     * sum(1, 2, 3) a call to the sum function with 3 arguments
     * points a variable
     * points['x'] an array access
     * line.pointA an object access (attribute)
     * line.length() a call to a method
     * and, or, xor, not logical operators
     * =, !=, >, <, >=, <= comparison operators
     * is, in membership operators
     */

    $model = \Hoa\Ruler\Ruler::interpret(implode(" and ", $crits));

    $serialized = serialize($model);

    $item->serialized = $serialized;
    $item->save();

    return $serialized;
  }
}
