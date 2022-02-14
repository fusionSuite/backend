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
namespace App\v1\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class Item
{

  use \App\v1\Read;

  /**
   * @api {get} /v1/items/type/:typeid Get all items with type defined
   * @apiName GetItems
   * @apiGroup Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   * 
   * @apiSuccess {Object[]}        -                               The list of the items.
   * @apiSuccess {Number}          -.id                            The id of the item.
   * @apiSuccess {String}          -.name                          The name of the item.
   * @apiSuccess {String}          -.created_at                    Date of the item creation.
   * @apiSuccess {String|null}     -.updated_at                    Date of the last item modification.
   * @apiSuccess {Object[]}        -.properties                    List of properties of the item.
   * @apiSuccess {Number}          -.properties.id                 The id of the property.
   * @apiSuccess {String}          -.properties.name               The name of the property.
   * @apiSuccess {String="string","integer","float","date","datetime","list","boolean","text","itemlink","itemlinks"}   -.properties.valuetype  The type of value.
   * @apiSuccess {String|null}     -.properties.unit               The unit used for the property (example: Ko, seconds...).
   * @apiSuccess {String[]|null}   -.properties.listvalues         The list of values when valuetype="list", else null.
   * @apiSuccess {String}          -.properties.value              The value of the property.
   * @apiSuccess {Boolean}         -.properties.byfusioninventory  Is updated by FusionInventory.
   * 
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 45,
   *     "name": "LP-000345",
   *     "created_at": "2020-07-20 14:30:45",
   *     "updated_at": null,
   *     "properties": [
   *       {
   *         "id": 3,
   *         "name": "Serial number",
   *         "valuetype": "string",
   *         "unit": null,
   *         "listvalues": [],
   *         "value": "gt43bf87d23d",
   *         "byfusioninventory": true
   *       },
   *       {
   *         "id": 4,
   *         "name": "Model",
   *         "valuetype": "string",
   *         "unit": null,
   *         "listvalues": [],
   *         "value": "Latitude E7470",
   *         "byfusioninventory": true
   *       },
   *       {
   *         "id": 5,
   *         "name": "Manufacturer",
   *         "valuetype": "string",
   *         "unit": null,
   *         "listvalues": [],
   *         "value": "Dell",
   *         "byfusioninventory": true
   *       }
   *     ]
   *   }
   * ]
   *
   */
  public function getAll(Request $request, Response $response, $args): Response
  {
    $paramsQuery = $request->getQueryParams();
    $pagination = $this->paramPagination($paramsQuery);

    $params = $this->manageParams($request);

    $items = \App\v1\Models\Item //::ofWhere($params)
      ::ofSort($params)->where('type_id', $args['typeid'])
      ->with('properties:id,name,valuetype,unit', 'properties.listvalues');

    $items = $this->paramFilters($paramsQuery, $items);
    // Example filter on property value
    // $items->whereHas('properties', function($q) {
    //   $q->where('item_property.value', 'VirtualBox');
    // });
    $totalCnt = $items->count();
    $items->skip(($params['skip'] * $params['take']))->take($params['take']);
    $response->getBody()->write($items->get()->toJson());
    $response = $response->withAddedHeader('X-Total-Count', $totalCnt);
    $response = $response->withAddedHeader('Link', $this->createLink($request, $pagination, $totalCnt));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {get} /v1/items/:id Get one item
   * @apiName GetItem
   * @apiGroup Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} id Rule unique ID.
   *
   * @apiSuccess {Number}          id                            The id of the item.
   * @apiSuccess {String}          name                          The name of the item.
   * @apiSuccess {String}          created_at                    Date of the item creation.
   * @apiSuccess {String|null}     updated_at                    Date of the last item modification.
   * @apiSuccess {Object[]}        properties                    List of properties of the item.
   * @apiSuccess {Number}          properties.id                 The id of the property.
   * @apiSuccess {String}          properties.name               The name of the property.
   * @apiSuccess {String="string","integer","float","date","datetime","list","boolean","text","itemlink","itemlinks"}  properties.valuetype  The type of value.
   * @apiSuccess {String|null}     properties.unit               The unit used for the property (example: Ko, seconds...).
   * @apiSuccess {String[]|null}   properties.listvalues         The list of values when valuetype="list", else null.
   * @apiSuccess {String}          properties.value              The value of the property.
   * @apiSuccess {Boolean}         properties.byfusioninventory  Is updated by FusionInventory.
   * 
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 45,
   *   "name": "LP-000345",
   *   "created_at": "2020-07-20 14:30:45",
   *   "updated_at": null,
   *   "properties": [
   *     {
   *       "id": 3,
   *       "name": "Serial number",
   *       "valuetype": "string",
   *       "unit": null,
   *       "listvalues": [],
   *       "value": "gt43bf87d23d",
   *       "byfusioninventory": true
   *     },
   *     {
   *       "id": 4,
   *       "name": "Model",
   *       "valuetype": "string",
   *       "unit": null,
   *       "listvalues": [],
   *       "value": "Latitude E7470",
   *       "byfusioninventory": true
   *     },
   *     {
   *       "id": 5,
   *       "name": "Manufacturer",
   *       "valuetype": "string",
   *       "unit": null,
   *       "listvalues": [],
   *       "value": "Dell",
   *       "byfusioninventory": true
   *     }
   *   ]
   * }
   *
   */
  public function getOne(Request $request, Response $response, $args): Response
  {
    $item = \App\v1\Models\Item::with('properties:id,name,valuetype,unit', 'properties.listvalues')
      ->find($args['id'])->makeVisible(['propertygroups']);
    if (is_null($item))
    {
      throw new \Exception("This item has not be found", 404);
    }
    $response->getBody()->write($item->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }


  /**
   * @api {post} /v1/items Create a new item
   * @apiName PostItems
   * @apiGroup Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *     
   * @apiSuccess {String}    name                    The name of the item.
   * @apiSuccess {Number}    type_id                 The id of the type of the item.
   * @apiSuccess {Object[]}  properties              List of properties
   * @apiSuccess {Number}    properties.property_id  The id of the property.
   * @apiSuccess {String[]}  properties.value        The value of the property for the item.
   * 
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "LP-000345",
   *   "type_id: 4,
   *   "properties: [
   *     {
   *       "property_id": 3,
   *       "value": "gt43bf87d23d"
   *     },
   *     {
   *       "property_id": 8,
   *       "value": "Latitude E7470"
   *     },
   *     {
   *       "property_id": 5,
   *       "value": "Dell"
   *     }
   *   ]
   * } 
   * 
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id":10
   * }
   * 
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The Name is required"
   * }
   * 
   */
  public function postItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());

    // Validate the data format
    $dataFormat = [
      'name'    => 'required|type:string',
      'type_id' => 'required|type:integer|integer'
    ];
    \App\v1\Common::validateData($data, $dataFormat);
    // validate for each properties
    if (property_exists($data, 'properties'))
    {
      foreach ($data->properties as $property)
      {
        $dataFormat = [
          'property_id' => 'required|type:integer|integer|min:1',
          'value'       => 'present|type:string'
        ];
        \App\v1\Common::validateData($property, $dataFormat);
      }
    }
  
    $ruleData = [
      'name' => $data->name
    ];

    $item = new \App\v1\Models\Item;
    $item->name = $data->name;
    $item->type_id = $data->type_id;
    $item->owner_user_id = 0;
    $item->owner_group_id = 0;
    $item->state_id = 0;
    $item->save();
    $ruleData['id'] = $item->id;
    $ruleData['name'] = $data->name;

    $propertiesId = [];
    if (property_exists($data, 'properties'))
    {
      foreach ($data->properties as $property)
      {
        $propertiesId[] = $property->property_id;
        $ruleData[$property->property_id] = $property->value;
        $item->properties()->attach($property->property_id, ['value' => $property->value]);
      }
    }

    // Define the properties not in post with the default value
    $type = \App\v1\Models\Config\Type::find($data->type_id);
    foreach ($type->properties()->get() as $prop)
    {
      if (in_array($prop->id, $propertiesId))
      {
        continue;
      }
      $item->properties()->attach($prop->id, ['value' => $prop->default]);
    }

    // run rules
    $item_id = \App\v1\Controllers\Rules\ActionScript::runRules($ruleData);

    $response->getBody()->write(json_encode(["id" => intval($item->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {patch} /v1/items/:id Update an existing item
   * @apiName PatchItem
   * @apiGroup Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the item.
   *
   * @apiSuccess {String}  name      Name of the type.
   * 
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "LP-000423",
   * } 
   * 
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   * 
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The Name is required"
   * }
   * 
   */
  public function patchItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());
    $item = \App\v1\Models\Item::find($args['id']);

    if (is_null($item))
    {
      throw new \Exception("The item has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'name' => 'required|type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $item->name = $data->name;
    $item->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/type/:id delete an item
   * @apiName DeleteItem
   * @apiGroup Items
   * @apiVersion 1.0.0-draft
   * @apiDescription The first delete request will do a soft delete. The second delete request will permanently delete the item
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the item.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   * 
   */
  public function deleteItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $item = \App\v1\Models\Item::withTrashed()->find($args['id']);

    if (is_null($item))
    {
      throw new \Exception("The item has not be found", 404);
    }

    // If in soft trash, delete permanently
    if ($item->trashed())
    {
      $item->forceDelete();
    }
    else
    {
      $item->delete();
    }

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  // ***************************************
  // Manage properties (endpoint /property)
  // ***************************************

  /**
   * @api {patch} /v1/items/:id/property/:propertyid Update the value of the property
   * @apiName PatchItemProperty
   * @apiGroup Items/property
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id           Unique ID of the item.
   * @apiParam {Number}    propertyid   Unique ID of the property of the item.
   *
   * @apiSuccess {String}  value        Value of the property.
   * 
   * @apiParamExample {json} Request-Example:
   * {
   *   "value": "my new value",
   * } 
   * 
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   * 
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The Name is required"
   * }
   * 
   */
  public function patchProperty(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());
    $item = \App\v1\Models\Item::find($args['id']);
    if (is_null($item))
    {
      throw new \Exception("The item has not be found", 404);
    }
    
    // Validate the data format
    $dataFormat = [
      'value' => 'required|type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $item->properties()->updateExistingPivot($args['propertyid'], [
      'value' => $data->value,
    ]);

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/type/:id/property/:propertyid delete a property of the item
   * @apiName DeleteItemProperty
   * @apiGroup Items/property
   * @apiVersion 1.0.0-draft
   * @apiDescription Reset the property to the default value
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id           Unique ID of the item.
   * @apiParam {Number}    propertyid   Unique ID of the property of the item.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   * 
   */
  public function deleteProperty(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $item = \App\v1\Models\Item::find($args['id']);
    if (is_null($item))
    {
      throw new \Exception("The item has not be found", 404);
    }

    $property = \App\v1\Models\Config\Property::find($args['propertyid']);
    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }

    $item->properties()->updateExistingPivot($args['propertyid'], [
      'value' => $property->default,
    ]);

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }


  private function runRules($data)
  {
    $ruler   = new \Hoa\Ruler\Ruler();
    $context = new \Hoa\Ruler\Context();

    // prepare context
    $context['name'] = $data->name;
    foreach ($data->properties as $property)
    {
      $prop = \App\v1\Models\Config\Property::find($property->property_id)->get();
      $context[$prop->name] = $property->value;
    }

    // get all rules 
    $rules = \App\v1\Models\Rule::where('type', 'rewritefield')->get();
    foreach ($rules as $rule)
    {
      if (!is_null($rule->serialized) && !empty($rule->serialized))
      {
        // TODO seems a problem with serialized, or perhaps the data in DB are not rigth
        $model = unserialize($rule->serialized);
        // $model = \Hoa\Ruler\Ruler::interpret("name = 'test'");

        if ($ruler->assert($model, $context)) {
          // todo rewrite
          // echo "rewrited !!!!\n";
          $data->name = "rewriten";
        }
      }
    }

    return $data;
  }
  
}
