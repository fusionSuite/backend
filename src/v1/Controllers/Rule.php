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
namespace App\v1\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class Rule
{

  public static function runRules($item, $properties, $ruleType)
  {
    $ruler   = new \Hoa\Ruler\Ruler();
    $context = new \Hoa\Ruler\Context();
    $context['item'] = function () use ($context, $item) {
      return $item;
    };
    $context['properties'] = function () use ($context, $properties) {
      return $properties;
    };
    $context['name'] = $item->name;
    $context['serial number'] = '';

    if ($ruleType == 'searchitem')
    {
      // special case for search in DB
      $ruler->getDefaultAsserter()->setOperator('searchindb', function ($item, $field, $values, $properties) {
        // search in DB
        [$typeId, $propertyId] = explode('.', $field);
        if ($item->type_id != $typeId)
        {
          return false;
        }

        $values = explode('.', $values);
        $value = '';
        if (count($values) == 1)
        {
          $value = $values;
        }
        else
        {
          $value = $item[$values[1]];
        }

        $items = \App\v1\Models\CMDB\Item::where('type_id', $typeId);
        if ($propertyId == 0)
        {
          $items->where('name', $item->name);
        }
        else
        {
          if (isset($properties[$propertyId]))
          {
            $value = $properties[$propertyId];
          }
          else
          {
            return false;
          }
          // print_r($properties);
          // $value = $properties[$propertyId];
          // echo $propertyId." - ".$value."\n";
          // print_r($values);
          // print_r($item);
          $items->whereHas('properties', function ($q) use ($propertyId, $value) {
            $q->where('property_id', $propertyId)
              ->where('item_property.value', $value);
          });
        }
        $items->skip(0)->take(1);
        if ($items->count() == 0)
        {
          return false;
        }
        // $queries[] = $items;
        return true;
      });
    }

    // prepare context
    // $context['name'] = $item->name;
    // foreach ($item->properties as $property)
    // {
    //   $prop = \App\v1\Models\CMDB\Property::find($property->property_id)->get();
    //   $context[$prop->name] = $property->value;
    // }

    // get all rules 
    $rules = \App\v1\Models\Rule::where('type', $ruleType)->with('criteria', 'actions')->get();
    foreach ($rules as $rule)
    {
      $criteria = [];
      if ($ruleType == 'searchitem')
      {
        foreach ($rule->criteria as $criterium)
        {
          $criteria[] = 'searchindb(item, "'.$criterium->field.'", "'.$criterium->values.'", properties)';
        }
      }
      else
      {
        foreach ($rule->criteria as $criterium)
        {
          $criteria[] = $criterium->field." ".$criterium->comparator." '".$criterium->values."'";
        }
      }

      // $model = \Hoa\Ruler\Ruler::interpret(implode(' and ', $criteria));
      if ($ruler->assert(implode(' and ', $criteria), $context))
      {
        if ($ruleType == 'searchitem')
        {
          // TODO is it posible to get the query on each criterium? 
          // if yes, get it
          // think of the morning: regroup all searchindb() here to find in DB, the assert will only test simple criteria
          // echo "FOUND :)\n";
          // TODO must return the id found
          return true;




        }
      } else {
        if ($ruleType == 'searchitem')
        {
// print_r($criteria);
// echo "NOT FOUND :(\n";
          // next


        }
      }


      // if have serialized rule
      // if (!is_null($rule->serialized) && !empty($rule->serialized))
      // {
      //   // TODO seems a problem with serialized, or perhaps the data in DB are not rigth
      //   $model = unserialize($rule->serialized);
      //   // $model = \Hoa\Ruler\Ruler::interpret("name = 'test'");

      //   if ($ruler->assert($model, $context)) {
      //     // todo rewrite
      //     // echo "rewrited !!!!\n";
      //     $item->name = "rewriten";
      //   }
      // }
    }
    if ($ruleType == 'searchitem')
    {
      return false;
    }

    return $item;






// TODO OLD CODE, MUST BE DELETED    
    // $ruler             = new \Hoa\Ruler\Ruler();
    // $rule              = "name = 'test20200913 - 02'";
    // $context           = new \Hoa\Ruler\Context();
    // $arr = $item->toArray();
    // foreach ($arr as $key => $value) {
    //   $context[$key] = $value;
    // }
    
    // // Nothing in the database.
    // // if (null === $serialized = $database->get($ruleId)) {
    //   // We transform the rule into an object model.
    //   $model = \Hoa\Ruler\Ruler::interpret($rule);

    //   // We serialize and save the object model.
    //   // $database->save($ruleId, serialize($model));
    // // } else {
    //   // We have a serialization! We unserialize it to get the object model.
    //   // $model = unserialize($serialized);
    // // }

    // // We can assert by using a model instead of a rule!
    // if ($ruler->assert($model, $context)) {
    //   $item->name = 'Rewrited';
    // }

    // return $item;
  }

  /**
   * Special case for FusionInventory rules
   * 
   */
  public function fusioninventoryRule()
  {

  }


  /**
   * @api {get} /v1/rules/:type GET - Get all rules with type defined
   * @apiName GetRules//
   * @apiGroup Rules
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {String="searchitem","rewritefield","notification","fusioninventorygettype"} type The type of the rules.
   *     
   * @apiSuccess {Integer}  id      The id of the item.
   * @apiSuccess {String}   name    The name of the item.
   * @apiSuccess {String}   comment The comment of the item.
   * 
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 23,
   *     "name": "Rewrite wrong serial number",
   *     "comment": "On some devices, the serial is not the right (for example '123456')"
   *   }
   * ]
   *
   */
  public function getAll(Request $request, Response $response, $args): Response
  {
    $items = \App\v1\Models\Rule::where('type', $args['type'])->get();
    $response->getBody()->write($items->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }


  /**
   * @api {get} /v1/rules/:type/:id GET - Get one rule
   * @apiName GetRule
   * @apiGroup Rules
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {String="fusioninventorysearchitem","rewritefield","notification","fusioninventorygettype"} type The type of the rules.
   * @apiParam {Number} id Rule unique ID.
   *
   * @apiSuccess {String}   name                  The name of the item.
   * @apiSuccess {String}   comment               The comment of the item.
   * @apiSuccess {Object[]} criteria              The criteria list.
   * @apiSuccess {Integer}  criteria.id           The criteria id.
   * @apiSuccess {string=\d+\.\d+}  criteria.field        The field of the criteria. The format is type_id.property_id
   * @apiSuccess {String="=",">","<","!=","in","contain","regex"}   criteria.comparator   The comparator.
   * @apiSuccess {String}   criteria.values       The value(s) to check.
   * @apiSuccess {String}   criteria.comment      The criteria comment.
   * @apiSuccess {Object[]} actions               The actions list.
   * @apiSuccess {Integer}  actions.id            The action id.
   * @apiSuccess {string=\d+\.\d+|null}  actions.field        The field to update. The format is type_id.property_id
   * @apiSuccess {String="replace","append","import","notimport","sendnotification"}     actions.type   The type of action.
   * @apiSuccess {String}   actions.values        The rewritten value.
   * @apiSuccess {String}   actions.comment       The criteria comment.
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "name": "Rewrite wrong serial number",
   *   "comment": "On some devices, the serial is not the right (for example '123456')",
   *   "criteria": [
   *     {
   *       "id": 12,
   *       "field": "computer.serialnumber",
   *       "comparator": "",
   *       "values": "",
   *       "comment": ""
   *     }
   *   ],
   *   "actions: [
   *     {
   *       "id": 12,
   *       "field": "computer.serialnumber",
   *       "type": "replace",
   *       "values": "",
   *       "comment": "Replace by empty value"
   *     }
   *   ]
   * }
   *
   * @apiUse AutorizationError
   *
   */
  public function getOne(Request $request, Response $response, $args): Response
  {
    $item = \App\v1\Models\Rule::find($args['id'])->criteria()->actions();
    if (is_null($item) || $item->type !== $args['type'])
    {
      throw new \Exception("This ticket has not be found", 404);
    }
    $response->getBody()->write($item->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/rules/:type POST - Create a new rule
   * @apiName PostRule
   * @apiGroup Rules
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *     
   * @apiSuccess {String}  name          Name of the rule.
   * @apiSuccess {String}  comment       Comment of the rule.
   */
  public function postRule(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');
    $data = json_decode($request->getBody());

    $item = new \App\v1\Models\Rule;
    $item->name = $data->name;
    $item->comment = $data->comment;
    $item->type = $args['type'];
    $item->save();

    $response->getBody()->write(json_encode(["id" => intval($item->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/rules/:type/:id/criteria POST - Create a new criteria for the rule
   * @apiName PostRuleCriteria
   * @apiGroup Rules
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *     
   * @apiSuccess {String}  name          Name of the rule.
   * @apiSuccess {String}  comment       Comment of the rule.
   */
  public function postCriterium(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');
    $data = json_decode($request->getBody());

    $item = new \App\v1\Models\Rulecriterium();
    $item->name = $data->name;
    $item->rule_id = $args['id'];
    $item->field = $data->field;
    $item->comparator = $data->comparator;
    $item->values = $data->values;
    $item->comment = $data->comment;
    $item->save();

    $this->serializeRule($args['id']);

    $response->getBody()->write(json_encode(["id" => intval($item->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/rules/:type/:id/action POST - Create a new action for the rule
   * @apiName PostRuleActions
   * @apiGroup Rules
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *     
   * @apiSuccess {String}  name          Name of the rule.
   * @apiSuccess {String}  comment       Comment of the rule.
   */
  public function postAction(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');
    $data = json_decode($request->getBody());

    $item = new \App\v1\Models\Ruleaction();
    $item->name = $data->name;
    $item->rule_id = $args['id'];
    $item->field = $data->field;
    $item->type = $data->type;
    $item->values = $data->values;
    $item->comment = $data->comment;
    $item->serialized = '';
    $item->save();

    $response->getBody()->write(json_encode(["id" => intval($item->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function serializeRule($ruleId) {
    $item = \App\v1\Models\Rule::find($ruleId);
    if (is_null($item)) {
      return null;
    }
    $criteria = \App\v1\Models\Rule::find($ruleId)->criteria()->get();

    // criteria
    $crits = [];
      
    foreach ($criteria as $criterium) {
      $crits[] = $criterium->field." ".$criterium->comparator." '".$criterium->values."'";
    }
    if (count($crits) == 0) {
      return null;
    }

    /**
     * TODO this is a list of possible rules (see https://hoa-project.net/En/Literature/Hack/Ruler.html#Grammar)
     * 
     * 'foo', "foo", 'f\'oo'	strings
     * true, false, null	pre-defined constants
     * 4.2	a real
     * 42	an integer
     * ['foo', true, 4.2, 42]	an array (heterogeneous)
     * sum(1, 2, 3)	a call to the sum function with 3 arguments
     * points	a variable
     * points['x']	an array access
     * line.pointA	an object access (attribute)
     * line.length()	a call to a method
     * and, or, xor, not	logical operators
     * =, !=, >, <, >=, <=	comparison operators
     * is, in	membership operators
     */ 

    $model = \Hoa\Ruler\Ruler::interpret(implode(" and ", $crits));

    $serialized = serialize($model);

    $item->serialized = $serialized;
    $item->save();

    return $serialized;
  }
}
