<?php
/**
 * FusionSuite - Frontend
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

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class SearchItem
{

  public static function runRules($input, $type_id)
  {
    $ruler   = new \Hoa\Ruler\Ruler();
    $context = new \Hoa\Ruler\Context();
    $context['name'] = $input['name'];
    $context['serialnumber'] = $input['serial'];
    $context['inventorynumber'] = $input['inventorynumber'];
    $context['macaddress'] = $input['macaddress'];
    $context['uuid'] = $input['uuid'];

    // special case for regex
    $ruler->getDefaultAsserter()->setOperator('regex', function ($input, $regex)
    {
      if (is_null($input))
      {
        return false;
      }
      $matches = [];
      preg_match($regex, $input, $matches);
      if (count($matches) > 0)
      {
        return true;
      }
      return false;
    });

    // get all rules 
    $rules = \App\v1\Models\Rule::where('type', 'searchitem')->with('criteria', 'actions')->get();
    foreach ($rules as $rule)
    {
      $criteria = [];
      $doSearchInDB = false;
      $criteria[] = '1 = 1';
      $items = \App\v1\Models\CMDB\Item::where('type_id', $type_id);
      foreach ($rule->criteria as $criterium)
      {
        // TODO if values == \d.\d => create a query run after standard rules
        // else use standard rule (example name contains xxx)
        $matches = [];
        preg_match('/^(\d+).(\d+)$/', $criterium->values, $matches);
        if (count($matches) == 3)
        {
          if ($matches[1] == 0)
          {
            $items->where('name', $input['name']);
          }
          else
          {
            $doSearchInDB = true;
            $propertyId = $matches[2];
            $value = $context[str_replace('input.', '', $criterium->field)];
            $items->whereHas('properties', function ($q) use ($propertyId, $value)
            {
              $q->where('property_id', $propertyId)
                ->where('item_property.value', $value);
            });
          }
        }
        else if ($criterium->comparator == 'regex')
        {
          $criteria[] = 'regex("'.$context[str_replace('input.', '', $criterium->field)].'", "'.$criterium->values.'")';
        }
        else
        {
          $criteria[] = $criterium->field.' '.$criterium->comparator.' '.$criterium->values;
        }
      }
      $actionToDo = 'import';
      foreach ($rule->actions as $action)
      {
        // can be import or notimport
        $actionToDo = $action->type;
      }
      // $model = \Hoa\Ruler\Ruler::interpret(implode(' and ', $criteria));
      if ($ruler->assert(implode(' and ', $criteria), $context))
      {
        if ($doSearchInDB)
        {
          // now search in DB
          $items->take(1);
          $item = $items->first();
          if (isset($item->id)) {
            if ($actionToDo == 'import')
            {
              return $item->id;
            }
          }
          if ($actionToDo == 'notimport')
          {
            return 'notimport';
          }
        }
        else
        {
          return $actionToDo;
        }
        // next rule
      }
    }
    return false;
  }
}
