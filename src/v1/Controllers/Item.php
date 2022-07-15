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
use stdClass;

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
   * @apiSuccess {Number}          -.id_bytype                     The id of the item by type (this id will
   *    generate consecutive id for the same type_id).
   * @apiSuccess {ISO8601}         -.created_at                    Date of the item creation.
   * @apiSuccess {null|ISO8601}    -.updated_at                    Date of the last item modification.
   * @apiSuccess {Object[]}        -.properties                    List of properties of the item.
   * @apiSuccess {Number}          -.properties.id                 The id of the property.
   * @apiSuccess {String}          -.properties.name               The name of the property.
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink",
   *    "itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}   -.properties.valuetype
   *    The type of value.
   * @apiSuccess {null|String}     -.properties.unit               The unit used for the property (example: Ko,
   *    seconds...).
   * @apiSuccess {null|String[]}   -.properties.listvalues         The list of values when valuetype="list", else null.
   * @apiSuccess {String}          -.properties.value              The value of the property.
   * @apiSuccess {Boolean}         -.properties.byfusioninventory  Is updated by FusionInventory.
   * @apiSuccess {Object[]}        -.propertygroups                List of property groups of the item.
   * @apiSuccess {Number}          -.propertygroups.id             The id of the propertygroup.
   * @apiSuccess {String}          -.propertygroups.name           The name of the propertygroup.
   * @apiSuccess {Number}          -.propertygroups.position       The position number of the propertygroup.
   * @apiSuccess {Number[]}        -.propertygroups.properties     The list of properties id.
   * @apiSuccess {ISO8601}         -.propertygroups.created_at     Date of the propertygroups creation.
   * @apiSuccess {null|ISO8601}    -.propertygroups.updated_at     Date of the last propertygroups modification.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 45,
   *     "name": "LP-000345",
   *     "id_bytype": 23,
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
   *     ],
   *     "propertygroups": [
   *       {
   *         "id": 2,
   *         "name": "Main",
   *         "position": 0,
   *         "properties": [3,4,5],
   *         "created_at": "2022-06-02T04:35:44.000000Z",
   *         "updated_at": "2022-06-02T04:35:44.000000Z"
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
    // $items->whereHas('properties', function($q)
    // {
    //   $q->where('item_property.value', 'VirtualBox');
    // });
    $totalCnt = $items->count();
    $items->skip(($params['skip'] * $params['take']))->take($params['take']);
    $allItems = $items->get()->toArray();
    foreach ($allItems as $key => $item)
    {
      $itemProperties = [];
      foreach ($item['properties'] as $property)
      {
        if (isset($itemProperties[$property['id']]))
        {
          // itemlinks case
          $itemProperties[$property['id']]['value'][] = $property['value'];
        }
        else {
          if ($property['valuetype'] == 'itemlinks' && !is_null($property['value']))
          {
            $property['value'] = [$property['value']];
          }
          if ($property['valuetype'] == 'typelinks' && !is_null($property['value']))
          {
            $property['value'] = [$property['value']];
          }
          $itemProperties[$property['id']] = $property;
        }
      }
      $allItems[$key]['properties'] = array_values($itemProperties);
    }

    $response->getBody()->write(json_encode($allItems));
    $response = $response->withAddedHeader('X-Total-Count', $totalCnt);
    $response = $response->withAddedHeader('Link', $this->createLink($request, $pagination, $totalCnt));
    $response = $response->withAddedHeader(
      'Content-Range',
      $this->createContentRange($request, $pagination, $totalCnt)
    );
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
   * @apiParam {Number} The item unique ID.
   *
   * @apiSuccess {Number}          id                            The id of the item.
   * @apiSuccess {String}          name                          The name of the item.
   * @apiSuccess {Number}          id_bytype                     The id of the item by type (this id will generate
   *    consecutive id for the same type_id).
   * @apiSuccess {ISO8601}         created_at                    Date of the item creation.
   * @apiSuccess {null|ISO8601}    updated_at                    Date of the last item modification.
   * @apiSuccess {Object[]}        properties                    List of properties of the item.
   * @apiSuccess {Number}          properties.id                 The id of the property.
   * @apiSuccess {String}          properties.name               The name of the property.
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink",
   *    "itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  properties.valuetype
   *    The type of value.
   * @apiSuccess {null|String}     properties.unit               The unit used for the property (example: Ko,
   *    seconds...).
   * @apiSuccess {null|String[]}   properties.listvalues         The list of values when valuetype="list", else null.
   * @apiSuccess {String}          properties.value              The value of the property.
   * @apiSuccess {Boolean}         properties.byfusioninventory  Is updated by FusionInventory.
   * @apiSuccess {Object[]}        propertygroups                List of property groups of the item.
   * @apiSuccess {Number}          propertygroups.id             The id of the propertygroup.
   * @apiSuccess {String}          propertygroups.name           The name of the propertygroup.
   * @apiSuccess {Number}          propertygroups.position       The position number of the propertygroup.
   * @apiSuccess {Number[]}        propertygroups.properties     The list of properties id.
   * @apiSuccess {ISO8601}         propertygroups.created_at     Date of the propertygroups creation.
   * @apiSuccess {null|ISO8601}    propertygroups.updated_at     Date of the last propertygroups modification.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 45,
   *   "name": "LP-000345",
   *   "id_bytype": 23,
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
   *     ],
   *     "propertygroups": [
   *       {
   *         "id": 2,
   *         "name": "Main",
   *         "position": 0,
   *         "properties": [3,4,5],
   *         "created_at": "2022-06-02T04:35:44.000000Z",
   *         "updated_at": "2022-06-02T04:35:44.000000Z"
   *       }
   *     ]
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

    $itemData = $item->toArray();
    $itemProperties = [];
    foreach ($itemData['properties'] as $property)
    {
      if (isset($itemProperties[$property['id']]))
      {
        // itemlinks case
        $itemProperties[$property['id']]['value'][] = $property['value'];
      }
      else {
        if ($property['valuetype'] == 'itemlinks')
        {
          $property['value'] = [$property['value']];
        }
        if ($property['valuetype'] == 'typelinks')
        {
          $property['value'] = [$property['value']];
        }
        $itemProperties[$property['id']] = $property;
      }
    }
    $itemData['properties'] = array_values($itemProperties);

    $response->getBody()->write(json_encode($itemData));
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
   * @apiBody {String}    name                      The name of the item.
   * @apiBody {Number}    type_id                   The id of the type of the item.
   * @apiBody {Object[]}  [properties]              List of properties
   * @apiBody {Number}    [properties.property_id]  The id of the property.
   * @apiBody {String[]}  [properties.value]        The value of the property for the item.
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
   *     }
   *   ]
   * }
   *
   * @apiSuccess {Number}  id        The id of the item.
   * @apiSuccess {Number}  id_bytype The id of the item by type (this id will generate consecutive id for the
   *    same type_id).
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id":10,
   *   "id_bytype": 4
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
        $this->validationPropertyValue($property);

        $prop = \App\v1\Models\Config\Property::find($property->property_id);

        if (!is_null($property->value))
        {
          if ($prop->valuetype == 'itemlinks')
          {
            foreach ($property->value as $itemId)
            {
              $item = \App\v1\Models\Item::find($itemId);
              if (is_null($item))
              {
                throw new \Exception(
                  'The Value is an id than does not exist (property ' . $prop->name . ' - ' .
                    strval($property->property_id) . ')',
                  400
                );
              }
            }
          }
          if ($prop->valuetype == 'typelinks')
          {
            foreach ($property->value as $typeId)
            {
              $item = \App\v1\Models\Config\Type::find($typeId);
              if (is_null($item))
              {
                throw new \Exception(
                  'The Value is an id than does not exist (property ' . $prop->name . ' - ' .
                    strval($property->property_id) . ')',
                  400
                );
              }
            }
          }
        }
      }
    }

    $ruleData = [
      'name' => $data->name
    ];

    $item = new \App\v1\Models\Item();
    $item->name = $data->name;
    $item->type_id = $data->type_id;
    $item->owner_user_id = 0;
    $item->owner_group_id = 0;
    $item->state_id = 0;
    // To prevent deadlock on heavy charge because have a select in insert for managing id_bytype (see Item model)
    $max_retries = 4;
    $retries = 0;
    $loop = true;
    while ($loop)
    {
      try {
        $item->save();
        $loop = false;
      }
      catch (\Exception $e)
      {
        if ($retries > $max_retries)
        {
          throw $e;
        }
        $retries++;
      }
    }
    $ruleData['id'] = $item->id;
    $ruleData['name'] = $data->name;

    $propertiesId = [];
    if (property_exists($data, 'properties'))
    {
      foreach ($data->properties as $property)
      {
        $propertiesId[] = $property->property_id;
        $ruleData[$property->property_id] = $property->value;
        $propertyItem = \App\v1\Models\Config\Property::find($property->property_id);
        $fieldName = 'value_' . $propertyItem->valuetype;
        if ($propertyItem->valuetype == 'itemlinks' && !is_null($property->value))
        {
          foreach ($property->value as $value)
          {
            $item->properties()->attach($property->property_id, ['value_itemlink' => $value]);
          }
        }
        elseif ($propertyItem->valuetype == 'typelinks' && !is_null($property->value))
        {
          foreach ($property->value as $value)
          {
            $item->properties()->attach($property->property_id, ['value_typelink' => $value]);
          }
        }
        elseif ($propertyItem->valuetype == 'date' && $property->value == '')
        {
          $item->properties()->attach($property->property_id, [$fieldName => date('Y-m-d')]);
        }
        elseif ($propertyItem->valuetype == 'datetime' && $property->value == '')
        {
          $item->properties()->attach($property->property_id, [$fieldName => date('Y-m-d H:i:s')]);
        }
        elseif ($propertyItem->valuetype == 'time' && $property->value == '')
        {
          $item->properties()->attach($property->property_id, [$fieldName => date('H:i:s')]);
        }
        else {
          $item->properties()->attach($property->property_id, [$fieldName => $property->value]);
        }
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
      $fieldName = 'value_' . $prop->valuetype;
      if ($prop->valuetype == 'itemlinks')
      {
        // TODO
      }
      elseif ($prop->valuetype == 'typelinks' && !is_null($prop->default))
      {
        foreach ($prop->default as $typelink)
        {
          $item->properties()->attach($prop->id, ['value_typelink' => $typelink]);
        }
      }
      elseif ($prop->valuetype == 'date' && $prop->default == '' && !is_null($prop->default))
      {
        $item->properties()->attach($prop->id, [$fieldName => date('Y-m-d')]);
      }
      elseif ($prop->valuetype == 'datetime' && $prop->default == '' && !is_null($prop->default))
      {
        $item->properties()->attach($prop->id, [$fieldName => date('Y-m-d H:i:s')]);
      }
      elseif ($prop->valuetype == 'time' && $prop->default == '' && !is_null($prop->default))
      {
        $item->properties()->attach($prop->id, [$fieldName => date('H:i:s')]);
      }
      else {
        $item->properties()->attach($prop->id, [$fieldName => $prop->default]);
      }
    }

    // run rules
    $item_id = \App\v1\Controllers\Rules\ActionScript::runRules($ruleData);
    // Get item to have the internal id
    $item = \App\v1\Models\Item::find($item->id);
    $returnData = [
      "id" => intval($item->id),
      "id_bytype" => intval($item->id_bytype)
    ];

    $response->getBody()->write(json_encode($returnData));
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
   * @apiBody {String}  name      Name of the type.
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
   * @api {delete} /v1/items/:id delete an item
   * @apiName DeleteItem
   * @apiGroup Items
   * @apiVersion 1.0.0-draft
   * @apiDescription The first delete request will do a soft delete. The second delete request will permanently
   *    delete the item
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
    else {
      $item->delete();
    }

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  // *********************** MANAGE PROPERTIES *********************** //

  /**
   * @api {patch} /v1/items/:id/property/:propertyid Update an existing property of item
   * @apiName PatchItemProperty
   * @apiGroup Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id           Unique ID of the item.
   * @apiParam {Number}    propertyid   Unique ID of the property.
   *
   * @apiBody {String}  value                     Value of the property to update.
   * @apiBody {Boolean} [reset_to_default=false]  To update with default value of property.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "value": "my new value"
   * }
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "value": null,
   *   "reset_to_default": true
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
   *   "message": "The Value is required"
   * }
   *
   */

  public function patchProperty(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');
    $args['propertyid'] = intval($args['propertyid']);
    $data = json_decode($request->getBody());
    $item = \App\v1\Models\Item::find($args['id']);

    if (is_null($item))
    {
      throw new \Exception("The item has not be found", 404);
    }

    $this->checkProperty($args['propertyid']);
    $property = \App\v1\Models\Config\Property::find($args['propertyid']);
    if (property_exists($data, 'reset_to_default') && $data->reset_to_default)
    {
      if ($property->valuetype == 'date' && $property->default == '' && !is_null($property->default))
      {
        $item->properties()->updateExistingPivot($args['propertyid'], [
          'value_' . $property->valuetype => date('Y-m-d')
        ]);
      }
      elseif ($property->valuetype == 'datetime' && $property->default == '' && !is_null($property->default))
      {
        $item->properties()->updateExistingPivot($args['propertyid'], [
          'value_' . $property->valuetype => date('Y-m-d H:i:s')
        ]);
      }
      elseif ($property->valuetype == 'time' && $property->default == '' && !is_null($property->default))
      {
        $item->properties()->updateExistingPivot($args['propertyid'], [
          'value_' . $property->valuetype => date('H:i:s')
        ]);
      }
      elseif ($property->valuetype == 'typelinks' && !is_null($property->default))
      {
        // get current values and add or remove from the list
        $currentItems = [];
        foreach ($item->propertiesLinks()->where('property_id', $args['propertyid'])->get() as $t)
        {
          $currentItems[$t->pivot->value_typelink] = $t->pivot->id;
        }
        foreach ($currentItems as $key => $idPivot)
        {
          if (!in_array($key, $property->default))
          {
            $pivot = \App\v1\Models\ItemProperty::find($idPivot);
            $pivot->delete();
          }
        }
        foreach ($property->default as $typelink)
        {
          if (!isset($currentItems[$typelink]))
          {
            $item->properties()->attach($args['propertyid'], [
              'value_typelink' => $typelink
            ]);
          }
        }
      }
      elseif ($property->valuetype == 'typelinks' && is_null($property->default))
      {
        // get current values and remove from the list
        $currentItems = [];
        foreach ($item->propertiesLinks()->where('property_id', $args['propertyid'])->get() as $t)
        {
          $currentItems[$t->pivot->value_typelink] = $t->pivot->id;
        }
        array_shift($currentItems);
        foreach ($currentItems as $key => $idPivot)
        {
          $pivot = \App\v1\Models\ItemProperty::find($idPivot);
          $pivot->delete();
        }
        $item->properties()->updateExistingPivot($args['propertyid'], [
          'value_typelink' => null
        ]);
      }
      else {
        $item->properties()->updateExistingPivot($args['propertyid'], [
          'value_' . $property->valuetype => $property->default
        ]);
      }
    }
    else {
      // Define the arguments
      $valdata = new stdClass();
      $valdata->property_id = $args['propertyid'];
      $valdata->value = $data->value;

      $this->validationPropertyValue($valdata);

      if ($property->valuetype == 'date' && $data->value == '' && !is_null($data->value))
      {
        $item->properties()->updateExistingPivot($args['propertyid'], [
          'value_' . $property->valuetype => date('Y-m-d')
        ]);
      }
      elseif ($property->valuetype == 'datetime' && $data->value == '' && !is_null($data->value))
      {
        $item->properties()->updateExistingPivot($args['propertyid'], [
          'value_' . $property->valuetype => date('Y-m-d H:i:s')
        ]);
      }
      elseif ($property->valuetype == 'time' && $data->value == '' && !is_null($data->value))
      {
        $item->properties()->updateExistingPivot($args['propertyid'], [
          'value_' . $property->valuetype => date('H:i:s')
        ]);
      }
      elseif ($property->valuetype == 'typelinks' && !is_null($data->value))
      {
        // get current values and add or remove from the list
        $currentItems = [];
        foreach ($item->propertiesLinks()->where('property_id', $args['propertyid'])->get() as $t)
        {
          $currentItems[$t->pivot->value_typelink] = $t->pivot->id;
        }
        foreach ($currentItems as $key => $idPivot)
        {
          if (!in_array($key, $data->value))
          {
            $pivot = \App\v1\Models\ItemProperty::find($idPivot);
            $pivot->delete();
          }
        }
        foreach ($data->value as $typelink)
        {
          if (!isset($currentItems[$typelink]))
          {
            $item->properties()->attach($args['propertyid'], [
              'value_typelink' => $typelink
            ]);
          }
        }
      }
      elseif ($property->valuetype == 'typelinks' && is_null($data->value))
      {
        // get current values and remove from the list
        $currentItems = [];
        foreach ($item->propertiesLinks()->where('property_id', $args['propertyid'])->get() as $t)
        {
          $currentItems[$t->pivot->value_typelink] = $t->pivot->id;
        }
        array_shift($currentItems);
        foreach ($currentItems as $key => $idPivot)
        {
          $pivot = \App\v1\Models\ItemProperty::find($idPivot);
          $pivot->delete();
        }
        $item->properties()->updateExistingPivot($args['propertyid'], [
          'value_typelink' => null
        ]);
      }
      else {
        $item->properties()->updateExistingPivot($args['propertyid'], [
          'value_' . $property->valuetype => $data->value
        ]);
      }
    }
    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }




  public function postPropertyItemlink(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');
    $data = json_decode($request->getBody());
    $this->checkProperty($args['propertyid'], 'itemlinks');

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function deletePropertyItemlink(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');
    $data = json_decode($request->getBody());
    $this->checkProperty($args['propertyid']);

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

    /**
   * @api {post} /v1/items/:id/property/:propertyid/typelinks Add a typelink
   * @apiName PostItemPropertyTypelink
   * @apiGroup Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id         Unique ID of the item.
   * @apiParam {Number}    propertyid Unique ID of the property.
   *
   * @apiSuccess {Number}  value      Unique ID of the type.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "value": 3
   * }
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   *
   */
  public function postPropertyTypelink(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');
    $data = json_decode($request->getBody());

    $item = \App\v1\Models\Item::find($args['id']);
    if (is_null($item))
    {
      throw new \Exception("The item has not be found", 404);
    }

    $this->checkProperty($args['propertyid'], 'typelinks');

    $dataFormat = [
      'value' => 'required|type:integer|regex:/^[0-9]+$/'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $typelink = \App\v1\Models\Config\Type::find($data->value);
    if (is_null($typelink))
    {
      throw new \Exception('The Value is an id than does not exist', 400);
    }


    $item->properties()->attach($args['propertyid'], [
      'value_typelink' => $data->value
    ]);

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/items/:id/property/:propertyid/typelinks/:typelinkid Delete a typelink
   * @apiName DeleteItemPropertyTypelink
   * @apiGroup Items
   * @apiVersion 1.0.0-draft
   * @apiDescription Delete a typelink in a property
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id         Unique ID of the item.
   * @apiParam {Number}    propertyid Unique ID of the property.
   * @apiParam {Number}    typelinkid Unique ID of the typelink.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   *
   */
  public function deletePropertyTypelink(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $item = \App\v1\Models\Item::find($args['id']);
    if (is_null($item))
    {
      throw new \Exception("The item has not be found", 404);
    }

    $this->checkProperty($args['propertyid'], 'typelinks');
    $property = \App\v1\Models\Config\Property::find($args['propertyid']);

    $typelink = \App\v1\Models\Config\Type::find($args['typelinkid']);
    if (is_null($typelink))
    {
      throw new \Exception('The typelink is an id than does not exist', 400);
    }

    $currentItems = [];
    foreach (
        $item->propertiesLinks()
        ->where('property_id', $args['propertyid'])
        ->where('value_typelink', $args['typelinkid'])
        ->get() as $t
    )
    {
      $pivot = \App\v1\Models\ItemProperty::find($t->pivot->id);
      $pivot->delete();
    }
    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }


  private function checkProperty($id, $valuetype = null)
  {
    $property = \App\v1\Models\Config\Property::find($id);

    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }
    if (!is_null($valuetype))
    {
      if ($property->valuetype != $valuetype)
      {
        throw new \Exception("The property is not a valuetype " . $valuetype, 404);
      }
    }
  }

  private function validationPropertyValue($data)
  {
    $dataFormat = [
      'property_id' => 'required|type:integer|integer|min:1',
      'value'       => 'present'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $property = \App\v1\Models\Config\Property::find($data->property_id);
    $dataFormat = [
      'property_id' => 'present',
      'value'       => 'required'
    ];

    if ($property->canbenull && is_null($data->value))
    {
      return;
    }
    if (is_null($data->value) && !$property->canbenull)
    {
      throw new \Exception(
        'The Value can\'t be null (property ' . $property->name . ' - ' .
          strval($data->property_id) . ')',
        400
      );
    }

    if ($property->valuetype == 'date')
    {
      $dataFormat['value'] = 'present|type:string|dateformat';
    }
    elseif ($property->valuetype == 'datetime')
    {
      $dataFormat['value'] = 'present|type:string|datetimeformat';
    }
    elseif ($property->valuetype == 'time')
    {
      $dataFormat['value'] = 'present|type:string|timeformat';
    }
    elseif ($property->valuetype == 'decimal')
    {
      $dataFormat['value'] = 'required|type:double';
    }
    elseif ($property->valuetype == 'number')
    {
      $dataFormat['value'] = 'required|type:integer|regex:/^[0-9]+$/';
    }
    elseif (in_array($property->valuetype, ['string', 'text']))
    {
      $dataFormat['value'] = 'required|type:string';
    }
    elseif (in_array($property->valuetype, ['integer']))
    {
      $dataFormat['value'] = 'required|type:integer|integer';
    }
    elseif (in_array($property->valuetype, ['itemlink', 'typelink', 'propertylink', 'list']))
    {
      $dataFormat['value'] = 'required|type:integer|regex:/^[0-9]+$/';
    }
    elseif (in_array($property->valuetype, ['itemlinks', 'typelinks']))
    {
      $dataFormat['value'] = 'required|array';
    }
    elseif ($property->valuetype == 'boolean')
    {
      $dataFormat['value'] = 'present|type:boolean|boolean';
    }
    \App\v1\Common::validateData($data, $dataFormat);

    if (!is_null($data->value))
    {
      // check if the item id exists
      if ($property->valuetype == 'itemlink')
      {
        $item = \App\v1\Models\Item::find($data->value);
        if (is_null($item))
        {
          throw new \Exception(
            'The Value is an id than does not exist (property ' . $property->name . ' - ' .
              strval($data->property_id) . ')',
            400
          );
        }
      }
      // check if the type id exists
      if ($property->valuetype == 'typelink')
      {
        $item = \App\v1\Models\Config\Type::find($data->value);
        if (is_null($item))
        {
          throw new \Exception(
            'The Value is an id than does not exist (property ' . $property->name . ' - ' .
              strval($data->property_id) . ')',
            400
          );
        }
      }
      // check if the property id exists
      if ($property->valuetype == 'propertylink')
      {
        $item = \App\v1\Models\Config\Property::find($data->value);
        if (is_null($item))
        {
          throw new \Exception(
            'The Value is an id than does not exist (property ' . $property->name . ' - ' .
              strval($data->property_id) . ')',
            400
          );
        }
      }

      // check if the list id exists
      if ($property->valuetype == 'list')
      {
        $item = \App\v1\Models\Config\Propertylist::find($data->value);
        if (is_null($item))
        {
          throw new \Exception(
            'The Value is an id than does not exist (property ' . $property->name . ' - ' .
              strval($data->property_id) . ')',
            400
          );
        }
      }
      if ($property->valuetype == 'string')
      {
        if (strlen($data->value) > 255)
        {
          throw new \Exception(
            'The Value is too long, max 255 chars (property ' . $property->name . ' - ' .
              strval($data->property_id) . ')',
            400
          );
        }
      }
    }
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

        if ($ruler->assert($model, $context))
        {
          // todo rewrite
          // echo "rewrited !!!!\n";
          $data->name = "rewriten";
        }
      }
    }

    return $data;
  }
}
