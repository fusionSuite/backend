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

use stdClass;

final class ActionScript
{
  public static function runRules($input)
  {
    $ruler   = new \Hoa\Ruler\Ruler();
    $context = new \Hoa\Ruler\Context();
    $exceptions = [];
    $actionScript = new ActionScript();

    // get all rules
    $rules = \App\v1\Models\Rule::where('type', 'actionscript')->with('criteria', 'actions')->get();
    foreach ($rules as $rule)
    {
      $criteria = [];
      $criteria[] = '1 = 1';
      foreach ($rule->criteria as $criterium)
      {
        // TODO to code criteria
      }
      // $model = \Hoa\Ruler\Ruler::interpret(implode(' and ', $criteria));
      if ($ruler->assert(implode(' and ', $criteria), $context))
      {
        foreach ($rule->actions as $action)
        {
          $actionItem = \App\v1\Models\Item::
              with(
                'properties:id,name,internalname,valuetype,unit,organization_id',
                'properties.listvalues',
                'properties.property_itemlink:id,name,created_at,created_by,updated_at,updated_by,deleted_at,' .
                'deleted_by,id_bytype',
                'properties.property_typelink:id,name,created_at,created_by,updated_at,updated_by,deleted_at,' .
                'deleted_by',
                'properties.property_list:id,value',
                'properties.property_propertylink:id,name,created_at,created_by,updated_at,updated_by,deleted_at,' .
                'deleted_by',
              )
            ->find($action->values);
          $args = new stdClass();
          $args->itemid = $input['id'];
          $args->itemname = $input['name'];
          $args->hostname = $input['name'];
          // TODO manage this in the configuration
          $args->fusionsuiteurl = 'http://xxxx';
          $actionNamespace = '';
          $actionFunctionName = '';
          $aActionItem = $actionItem->toArray();
          foreach ($aActionItem['properties'] as $property)
          {
            if (preg_match('/action.[\w]+.classname/', $property['internalname']))
            {
              $actionNamespace = $property['value'];
              continue;
            }
            if (preg_match('/action.[\w]+.associatedaction/', $property['internalname']))
            {
              $actionFunctionName = $property['value']['value'];
              continue;
            }

            if ($property['valuetype'] == 'propertyId')
            {
              if ($property['value'] == 0)
              {
                // it's name
                $args->{$property['name']} = $input['name'];
              }
              else
              {
                // get value from properties
                $args->{$property['internalname']} = $input[$property['id']];
              }
            }
            elseif ($property['valuetype'] == 'itemlink')
            {
              // get properties of itemlink
              $args->{$property['internalname']} = $actionScript->getItemProperties($property['value']['id']);
            }
            elseif ($property['valuetype'] == 'list')
            {
              $args->{$property['internalname']} = $property['value']['value'];
            }
            else
            {
              $args->{$property['internalname']} = $property['value'];
            }
          }

          try {
            $className = '\\ActionScripts\\' . $actionNamespace . '\\' . $actionNamespace;
            $myAction = new $className();
            $ret = $myAction->$actionFunctionName($args);
          }
          catch (\Exception $e)
          {
            $exceptions[] = 'Error in rule `' . $rule->name . '`: ' . $e->getMessage();
          }
          finally {
            // manage return
            // Example: store the value in a property of the item
            if (isset($ret['value']) && !is_null($action->res_in_property_id))
            {
              $item = \App\v1\Models\Item::find($input['id']);
              $item->properties()->attach($action->res_in_property_id, ['value' => $ret['value']]);
            }
          }
        }
      }
    }
    // Manage if have exceptions
    if (count($exceptions) > 0)
    {
      throw new \Exception(implode(', ', array_values($exceptions)), 500);
    }
    return false;
  }


  /********************
   * Private functions
   ********************/

  private function getItemProperties($itemId)
  {
    $item = \App\v1\Models\Item::find($itemId);
    $properties = new stdClass();
    foreach ($item->properties()->get() as $property)
    {
      $properties->{$property->internalname} = $property->value;
    }
    return $properties;
  }
}
