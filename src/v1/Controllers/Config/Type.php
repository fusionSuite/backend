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
   * @apiSuccess {Object[]}                    types                               List of properties.
   * @apiSuccess {Number}                      types.id                            The id of the type.
   * @apiSuccess {String}                      types.name                          The name of the type.
   * @apiSuccess {String}                      types.internalname                  The internalname of the type.
   * @apiSuccess {String="logical","physical"} types.modeling                      The model of the type.
   * @apiSuccess {Boolean}                     types.tree                          Set if the items of this type are
   *    in a tree.
   * @apiSuccess {Boolean}                     types.allowtreemultipleroots        Set if the items of this type can
   *    have multiple roots.
   * @apiSuccess {ISO8601}                     types.created_at                    Date of the type creation.
   * @apiSuccess {null|ISO8601}                types.updated_at                    Date of the last type modification.
   * @apiSuccess {Object[]}                    types.properties                    The properties list.
   * @apiSuccess {Number}                      types.properties.id                 The id of the property.
   * @apiSuccess {String}                      types.properties.name               The name of the property.
   * @apiSuccess {String}                      types.properties.internalname       The internalname of the property.
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink",
   *    "itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}
   *    types.properties.valuetype
   *    The type of value.
   * @apiSuccess {null|String}                 types.properties.unit               The unit used for the property
   *    (example: Ko, seconds...).
   * @apiSuccess {null|String}                 types.properties.description        The description of the propery.
   * @apiSuccess {ISO8601}                     types.properties.created_at         Date of the item creation.
   * @apiSuccess {null|ISO8601}                types.properties.updated_at         Date of the last item modification.
   * @apiSuccess {Boolean}                     types.properties.canbenull          The property can be null or not.
   * @apiSuccess {Boolean}                     types.properties.setcurrentdate     The property in the item can
   *    automatically use the current date when store in DB.
   * @apiSuccess {null|String}                 types.properties.regexformat        The regexformat to verify the value
   *    is conform (works only with valuetype is string or list).
   * @apiSuccess {null|String[]}               types.properties.listvalues         The list of values when
   *    valuetype="list", else null.
   * @apiSuccess {Any}                         types.properties.default            The default value.
   * @apiSuccess {Object[]}                    types.propertygroups                The properties groups list.
   * @apiSuccess {Number}                      types.propertygroups.id             The id of the properties group.
   * @apiSuccess {String}                      types.propertygroups.name           The name of the properties group.
   * @apiSuccess {Number}                      types.propertygroups.position       The position of the properties
   *    group, related to other groups of the type.
   * @apiSuccess {Number[]}                    types.propertygroups.properties     The id list of properties of the
   *    properties group.
   * @apiSuccess {ISO8601}                     types.propertygroups.created_at     Date of the item creation.
   * @apiSuccess {null|ISO8601}                types.propertygroups.updated_at     Date of the last item modification.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 23,
   *     "name": "Memory",
   *     "created_at": "2020-07-20 22:15:08",
   *     "updated_at": null
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
   * @apiSuccess {Number}                      id                            The id of the type.
   * @apiSuccess {String}                      name                          The name of the type.
   * @apiSuccess {String}                      internalname                  The internalname of the type.
   * @apiSuccess {String="logical","physical"} modeling                      The model of the type.
   * @apiSuccess {Boolean}                     tree                          Set if the items of this type
   *    are in a tree.
   * @apiSuccess {Boolean}                     allowtreemultipleroots        Set if the items of this type can have
   *    multiple roots.
   * @apiSuccess {ISO8601}                     created_at                    Date of the type creation.
   * @apiSuccess {null|ISO8601}                updated_at                    Date of the last type modification.
   * @apiSuccess {Object[]}                    properties                    The properties list.
   * @apiSuccess {Number}                      properties.id                 The id of the property.
   * @apiSuccess {String}                      properties.name               The name of the property.
   * @apiSuccess {String}                      properties.internalname       The internalname of the property.
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink",
   *    "itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}   properties.valuetype
   *    The type of value.
   * @apiSuccess {null|String}                 properties.unit               The unit used for the property (example:
   *    Ko, seconds...).
   * @apiSuccess {null|String}                 properties.description        The description of the propery.
   * @apiSuccess {ISO8601}                     properties.created_at         Date of the item creation.
   * @apiSuccess {null|ISO8601}                properties.updated_at         Date of the last item modification.
   * @apiSuccess {Boolean}                     properties.canbenull          The property can be null or not.
   * @apiSuccess {Boolean}                     properties.setcurrentdate     The property in the item can automatically
   *    use the current date when store in DB.
   * @apiSuccess {null|String}                 properties.regexformat        The regexformat to verify the value is
   *    conform (works only with valuetype is string or list).
   * @apiSuccess {null|String[]}               properties.listvalues         The list of values when valuetype="list",
   *    else null.
   * @apiSuccess {Any}                         properties.default            The default value.
   * @apiSuccess {Object[]}                    propertygroups                The properties groups list.
   * @apiSuccess {Number}                      propertygroups.id             The id of the properties group.
   * @apiSuccess {String}                      propertygroups.name           The name of the properties group.
   * @apiSuccess {Number}                      propertygroups.position       The position of the properties group,
   *    related to other groups of the type.
   * @apiSuccess {Number[]}                    propertygroups.properties     The id list of properties of the
   *    properties group.
   * @apiSuccess {ISO8601}                     propertygroups.created_at     Date of the item creation.
   * @apiSuccess {null|ISO8601}                propertygroups.updated_at     Date of the last item modification.
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
    $item = \App\v1\Models\Config\Type::find($args['id']);
    if (is_null($item))
    {
      throw new \Exception("This type has not be found", 404);
    }
    $response->getBody()->write($item->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }


  /**
   * @api {post} /v1/config/types Create a new type of items
   * @apiName PostConfigTypes
   * @apiGroup Config/Types
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiBody {String}   name                             The name of the type of items.
   * @apiBody {Boolean}  [tree=false]                     Set if the items of this type are in a tree.
   * @apiBody {Boolean}  [allowtreemultipleroots=false]   Set if the items of this type are in a tree and can
   *    have multiple roots.
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
      'name'                   => 'required|type:string',
      'tree'                   => 'type:boolean|boolean',
      'allowtreemultipleroots' => 'type:boolean|boolean'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $type = new \App\v1\Models\Config\Type();
    $type->name = $data->name;
    if (property_exists($data, 'internalname') === false)
    {
      $type->internalname = preg_replace("/[^a-z.]+/", "", strtolower($data->name));
    }
    else
    {
      $type->internalname = $data->internalname;
    }
    if (property_exists($data, 'tree') === true)
    {
      $type->tree = $data->tree;
    }
    if (property_exists($data, 'allowtreemultipleroots'))
    {
      $type->allowtreemultipleroots = $data->allowtreemultipleroots;
    }
    $type->save();

    $response->getBody()->write(json_encode(["id" => intval($type->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {patch} /v1/config/types/:id Update an existing type of items
   * @apiName PatchConfigTypes
   * @apiGroup Config/Types
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the type.
   *
   * @apiBody {String}  name      Name of the type.
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
   * @api {delete} /v1/config/types/:id delete a type of items
   * @apiName DeletConfigTypes
   * @apiGroup Config/Types
   * @apiVersion 1.0.0-draft
   * @apiDescription The first delete request will do a soft delete. The second delete request will permanently
   *                 delete the item
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
      $type->properties()->detach();
      $type->delete();
    }

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }


  /**
   * @api {post} /v1/config/types/:id/property/:propertyid Associate a property of this type
   * @apiName PostConfigTypesProperty
   * @apiGroup Config/Types/property
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id         Unique ID of the type.
   * @apiParam {Number}    propertyid Unique ID of the property.
   *
   *
   * @apiParamExample {json} Request-Example:
   * {}
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
   *   "message": "The type has not be found"
   * }
   *
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The property has not be found"
   * }
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
   * @api {delete} /v1/config/types/:id/property/:propertyid remove a property of this type
   * @apiName DeleteConfigTypesProperty
   * @apiGroup Config/Types/property
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id         Unique ID of the type.
   * @apiParam {Number}    propertyid Unique ID of the property.
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
   *   "message": "The type has not be found"
   * }
   *
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The property has not be found"
   * }
   *
   */
  public function deleteProperty(Request $request, Response $response, $args): Response
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

    $type->properties()->detach($args['propertyid']);

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
   * @apiBody {String[]}  [license]                                         The license of this template file (Array
   *    of Strings).
   * @apiBody {Object[]}  types                                             List of types (Array of Objects).
   * @apiBody {String}    types.name                                        The name of the type.
   * @apiBody {String}    types.internalname                                The unique internalname of the type.
   * @apiBody {Object[]}  types.propertygroups                              The propertygroup (Array of Objects).
   * @apiBody {String}    types.propertygroups.name                         The name of the propertygroup.
   * @apiBody {Object[]}  types.propertygroups.properties                   The properties in the propertygroup
   *    (Array of Objects).
   * @apiBody {String}    types.propertygroups.properties.name              The name of the property.
   * @apiBody {String}    types.propertygroups.properties.internalname      The internal name of the property.
   * @apiBody {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink",
   *    "itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}
   *    types.propertygroups.properties.valuetype  The type of value.
   * @apiBody {null|String}   types.propertygroups.properties.regexformat   The regexformat to verify the value is
   *    conform (works only with valuetype is string or list).
   * @apiBody {null|String[]} types.propertygroups.properties.listvalues    The list of values when valuetype="list",
   *    else null.
   * @apiBody {null|String}   types.propertygroups.properties.unit          The unit used for the property (example:
   *    Ko, seconds...).
   * @apiBody {null|String}   types.propertygroups.properties.default       The default value for the property.
   * @apiBody {null|String}   types.propertygroups.properties.description   The description of the property, describe
   *    the usage.
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

    $this->createTemplate($data);

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }


  /********************
   * Private functions
   ********************/

  private function createType($data)
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

  public function createTemplate($data)
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
                'valuetype'   => 'required|in:boolean,date,datetime,decimal,integer,itemlink,itemlinks,list,number,' .
                                 'propertylink,string,text,time,typelink,typelinks|type:string',
                'regexformat' => 'present|type:string',
                'listvalues'  => 'present|type:array',
                'unit'        => 'type:string',
                'default'     => 'present',
                'description' => 'type:string'
              ];
              \App\v1\Common::validateData($property, $dataFormat);
            }
          }
        }
      }
    }
    // End of data format validation

    $ctrlProperty = new \App\v1\Controllers\Config\Property();
    $ctrlPropertygroup = new \App\v1\Controllers\Config\Propertygroup();

    // Create types
    foreach ($data->types as $type)
    {
      $typeId = $this->createType($type);

      // Create propertygroups
      foreach ($type->propertygroups as $propertygroup)
      {
        $newData = new \stdClass();
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
            $propertyListId[] = $ctrlProperty->createProperty($property);
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
        $ctrlPropertygroup->createPropertygroup($newData, $typeId);
      }
    }
    return true;
  }
}
