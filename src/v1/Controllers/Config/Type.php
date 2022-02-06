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
namespace App\v1\Controllers\Config;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class Type
{

  /**
   * @api {get} /v1/config/types Get all types of items
   * @apiName GetConfigTypes
   * @apiGroup Config/Types
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Object[]}     -             The list of the types.
   * @apiSuccess {Number}       -.id          The id of the type.
   * @apiSuccess {String}       -.name        The name of the type.
   * @apiSuccess {String}       -.created_at  Date of the type creation.
   * @apiSuccess {String|null}  -.updated_at  Date of the last type modification.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 23,
   *     "name": "Memory",
   *     "created_at": "2020-07-20 22:15:08",
   *     "updated_at": null,
   *   }
   * ]
   *
   */
  public function getAll(Request $request, Response $response, $args): Response
  {
    $items = \App\v1\Models\Config\Type::all();
    $response->getBody()->write($items->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {get} /v1/config/types/:id Get one type
   * @apiName GetConfigType
   * @apiGroup Config/Types
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} id Rule unique ID.
   *
   * @apiSuccess {String}       name                  The name of the type.
   * @apiSuccess {String}       created_at            Date of the type creation.
   * @apiSuccess {String|null}  updated_at            Date of the last type modification.
   * @apiSuccess {Object[]}     properties            The properties list.
   * @apiSuccess {Number}       properties.id         The property id.
   * 
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "name": "memory",
   *   "properties": [
   *     {
   *       "id": 12,
   *     }
   *   ]
   * }
   *
   */
  public function getOne(Request $request, Response $response, $args): Response
  {
    $item = \App\v1\Models\Config\Type::with('properties.listvalues')->find($args['id']);
    if (is_null($item))
    {
      throw new \Exception("This type has not be found", 404);
    }
    $response->getBody()->write($item->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }


  /**
   * @api {post} /v1/config/type Create a new type of items
   * @apiName PostConfigTypes
   * @apiGroup Config/Types
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {String}  name     The name of the type of items.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "Firewall",
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
      'name' => 'required|type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $type = new \App\v1\Models\Config\Type;
    $type->name = $data->name;
    if (property_exists($data, 'internalname') === false)
    {
      $type->internalname = preg_replace("/[^a-z.]+/", "", strtolower($data->name));
    }
    else
    {
      $type->internalname = $data->internalname;
    }
    $type->save();

    $response->getBody()->write(json_encode(["id" => intval($type->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {patch} /v1/config/type/:id Update an existing type of items
   * @apiName PatchConfigType
   * @apiGroup Config/Types
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the type.
   *
   * @apiSuccess {String}  name      Name of the type.
   * 
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "Firewall2",
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
    $type = \App\v1\Models\Config\Type::find($args['id']);

    if (is_null($type))
    {
      throw new \Exception("The type has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'name' => 'required|type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $type->name = $data->name;
    $type->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/config/type/:id delete a type of items
   * @apiName DeletConfigType
   * @apiGroup Config/Types
   * @apiVersion 1.0.0-draft
   * @apiDescription The first delete request will do a soft delete. The second delete request will permanently delete the item
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the type.
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

    $type = \App\v1\Models\Config\Type::withTrashed()->find($args['id']);

    if (is_null($type))
    {
      throw new \Exception("The type has not be found", 404);
    }

    // If in soft trash, delete permanently
    if ($type->trashed())
    {
      $type->forceDelete();
    }
    else
    {
      $type->delete();
    }

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }


  /**
   * @api {post} /v1/config/type/:id/property/:propertyid Associate a property of this type
   * @apiName PostConfigTypesProperty
   * @apiGroup Config/Types/property
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   */
  public function postProperty(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $type = \App\v1\Models\Config\Type::find($args['id']);
    if (is_null($type))
    {
      throw new \Exception("The type has not be found", 404);
    }

    $property = \App\v1\Models\Config\Property::find($args['propertyid']);
    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }

    // TODO check if relation exists

    $type->properties()->attach($args['propertyid']);

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/config/types/templates Create types based on JSON template
   * @apiName PostConfigTypesTemplate
   * @apiGroup Config/Types/Template
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   * 
   * @apiSuccess {String[]}  [license]                                         The license of this template file (Array of Strings).
   * @apiSuccess {Object[]}  types                                             List of types (Array of Objects).
   * @apiSuccess {String}    types.name                                        The name of the type.
   * @apiSuccess {String}    types.internalname                                The unique internalname of the type.
   * @apiSuccess {Object[]}  types.propertygroups                              The propertygroup (Array of Objects).
   * @apiSuccess {String}    types.propertygroups.name                         The name of the propertygroup.
   * @apiSuccess {Object[]}  types.propertygroups.properties                   The typeproperties in the propertygroup (Array of Objects).
   * @apiSuccess {String}    types.propertygroups.properties.name              The name of the typeproperty.
   * @apiSuccess {String}    types.propertygroups.properties.internalname      The internal name of the typeproperty.
   * @apiSuccess {String="string","integer","float","date","datetime","list","boolean","text","itemlink","itemlinks"}   types.propertygroups.properties.valuetype  The type of value.
   * @apiSuccess {String}    types.propertygroups.properties.regexformat       The regexformat to verify the value is conform (works only with valuetype is string or list).
   * @apiSuccess {String[]|null} types.propertygroups.properties.listvalues    The list of values when valuetype="list", else null.
   * @apiSuccess {String|null}   types.propertygroups.properties.unit          The unit used for the property (example: Ko, seconds...).
   * @apiSuccess {String|null}   types.propertygroups.properties.default       The default value for the property.
   * @apiSuccess {String|null}   types.propertygroups.properties.description   The description of the property, describe the usage.
   * 
   * @apiParamExample {json} Request-Example:
   * {
   *   "license": [
   *     " FusionSuite - Backend                                                       ",
   *     " Copyright (C) 2022 FusionSuite                                              ",
   *     "                                                                             ",
   *     " This program is free software: you can redistribute it and/or modify        ",
   *     " it under the terms of the GNU Affero General Public License as published by ",
   *     " the Free Software Foundation, either version 3 of the License, or           ",
   *     " any later version.                                                          ",
   *     "                                                                             ",
   *     " This program is distributed in the hope that it will be useful,             ",
   *     " but WITHOUT ANY WARRANTY; without even the implied warranty of              ",
   *     " MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                ",
   *     " GNU Affero General Public License for more details.                         ",
   *     "                                                                             ",
   *     " You should have received a copy of the GNU Affero General Public License    ",
   *     " along with this program.  If not, see <http://www.gnu.org/licenses/>.       "
   *   ],
   *   "types": [
   *     {
   *       "name": "RuleAction Zabbix API configuration",
   *       "internalname": "ruleaction.zabbix.apiconfiguration",
   *       "propertygroups": [
   *         {
   *           "name": "Configuration",
   *           "properties": [
   *             {
   *               "name": "url",
   *               "internalname": "url",
   *               "valuetype": "string",
   *               "regexformat": "",
   *               "listvalues": [],
   *               "unit": "",
   *               "default": "",
   *               "description": ""
   *             }
   *           ]
   *         }
   *       }
   *     {
   *       "name": "RuleAction Zabbix templates",
   *       "internalname": "ruleaction.zabbix.templates",
   *       "propertygroups": [
   *         {
   *           "name": "Main",
   *           "properties": [
   *             {
   *               "name": "templateId",
   *               "internalname": "action.zabbix.templateid",
   *               "valuetype": "integer",
   *               "regexformat": "",
   *               "listvalues": [],
   *               "unit": "",
   *               "default": "",
   *               "description": ""
   *             }
   *           ]
   *         }
   *       ]
   *     }
   *   ]
   * } 
   * 
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * []
   * 
   */
  public function postTemplate(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());

    $this->_createTemplate($data);

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }


  /********************
   * Private functions
   ********************/

  private function _createType($data)
  {
    if (property_exists($data, 'internalname') === false)
    {
      $data->internalname = preg_replace("/[^a-z.]+/", "", strtolower($data->name));
    }
    else
    {
      $data->internalname = $data->internalname;
    }
    $type = \App\v1\Models\Config\Type::firstOrCreate(
      ['name' => $data->name],
      [
        'internalname' => $data->internalname
      ]
    );
    return $type->id;
  }

  function _createTemplate($data)
  {
    // Validate the data format
    $dataFormat = [
      'types'   => 'required|type:array'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    // validate for each types
    foreach ($data->types as $type)
    {
      $dataFormat = [
        'name'         => 'required|type:string',
        'internalname' => 'type:string|regex:/^[a-z.]+$/'
      ];
      \App\v1\Common::validateData($type, $dataFormat);

      // Validate for each propertygroup
      if (property_exists($type, 'propertygroups'))
      {
        foreach ($type->propertygroups as $propertygroup)
        {
          $dataFormat = [
            'name' => 'required|type:string',
          ];
          \App\v1\Common::validateData($propertygroup, $dataFormat);
  
          // Validate each properties
          if (property_exists($propertygroup, 'properties'))
          {
            foreach ($propertygroup->properties as $property)
            {
              $dataFormat = [
                'name'        => 'required|type:string',
                'internalname' => 'type:string|regex:/^[a-z.]+$/',
                'valuetype'   => 'required|in:string,integer,float,date,datetime,list,boolean,text,itemlink,itemlinks,propertyId|type:string',
                'regexformat' => 'present|type:string',
                'listvalues'  => 'present|type:array',
                'unit'        => 'type:string',
                'default'     => 'present|type:string',
                'description' => 'type:string'
              ];
              \App\v1\Common::validateData($property, $dataFormat);
            }
          }
        }
      }
    }
    // End of data format validation

    $typeProperty = new \App\v1\Controllers\Config\TypeProperty();
    $typePropertygroup = new \App\v1\Controllers\Config\TypePropertygroup();

    // Create types
    foreach ($data->types as $type)
    {
      $typeId = $this->_createType($type);

      // Create propertygroups
      foreach ($type->propertygroups as $propertygroup)
      {
        $newData = new \stdClass;
        $newData->name = $propertygroup->name;
        $propertyListId = [];
        // create properties or get id if yet exists
        foreach ($propertygroup->properties as $property)
        {
          if (property_exists($property, 'internalname') === false)
          {
            $property->internalname = preg_replace("/[^a-z.]+/", "", strtolower($property->name));
          }
          $prop = \App\v1\Models\Config\Property::firstWhere('internalname', $property->internalname);
          if (is_null($prop))
          {
            $propertyListId[] = $typeProperty->_createProperty($property);
          }
          else
          {
            $propertyListId[] = $prop->id;
          }
        }
        $typeItem = \App\v1\Models\Config\Type::find($typeId);
        $propertiesId = [];
        foreach ($typeItem->properties()->get() as $prop)
        {
          $propertiesId[] = $prop->id;
        }
        foreach ($propertyListId as $propId)
        {
          if (!in_array($propId, $propertiesId))
          {
            $typeItem->properties()->attach($propId);
          }
        }

        $newData->properties = $propertyListId;
        $typePropertygroup->_createPropertygroup($newData, $typeId);
      }
    }
    return true;
  }
}
