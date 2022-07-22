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
  use \App\v1\Read;

  /**
   * @api {get} /v1/config/types Get all types of items
   * @apiName GetConfigTypes
   * @apiGroup Config/Types
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Object[]}         types                               List of types.
   * @apiSuccess {Number}           types.id                            The id of the type.
   * @apiSuccess {String}           types.name                          The name of the type.
   * @apiSuccess {String}           types.internalname                  The internalname of the type.
   * @apiSuccess {String="logical","physical"} types.modeling           The model of the type.
   * @apiSuccess {Boolean}          types.tree                          Set if the items of this type are
   *    in a tree.
   * @apiSuccess {Boolean}          types.allowtreemultipleroots        Set if the items of this type can
   *    have multiple roots.
   * @apiSuccess {ISO8601}          types.created_at                    Date of the type creation.
   * @apiSuccess {null|ISO8601}     types.updated_at                    Date of the last type modification.
   * @apiSuccess {null|ISO8601}     types.deleted_at                    Date of the soft delete of the type.
   * @apiSuccess {null|Object}      types.created_by                    User has created the type.
   * @apiSuccess {Number}           types.created_by.id                 Id of the user has created the type.
   * @apiSuccess {String}           types.created_by.name               Name (login) of the user has created the type.
   * @apiSuccess {String}           types.created_by.first_name         First name of the user has created the type.
   * @apiSuccess {String}           types.created_by.last_name          Last name of the user has created the type.
   * @apiSuccess {null|Object}      types.updated_by                    User has updated the type.
   * @apiSuccess {Number}           types.updated_by.id                 Id of the user has updated the type.
   * @apiSuccess {String}           types.updated_by.name               Name (login) of the user has updated the type.
   * @apiSuccess {String}           types.updated_by.first_name         First name of the user has updated the type.
   * @apiSuccess {String}           types.updated_by.last_name          Last name of the user has updated the type.
   * @apiSuccess {null|Object}      types.deleted_by                    User has soft deleted the type.
   * @apiSuccess {Number}           types.deleted_by.id                 Id of the user has soft deleted the type.
   * @apiSuccess {String}           types.deleted_by.name               Name (login) of the user has soft deleted
   *    the type.
   * @apiSuccess {String}           types.deleted_by.first_name         First name of the user has soft deleted
   *    the type.
   * @apiSuccess {String}           types.deleted_by.last_name          Last name of the user has soft deleted the type.
   * @apiSuccess {Object[]}         types.properties                    The properties list.
   * @apiSuccess {Number}           types.properties.id                 The id of the property.
   * @apiSuccess {String}           types.properties.name               The name of the property.
   * @apiSuccess {String}           types.properties.internalname       The internalname of the property.
   * @codingStandardsIgnoreStart because break apidocsjs
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink","itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  types.properties.valuetype   The type of value.
   * @codingStandardsIgnoreEnd
   * @apiSuccess {null|String}      types.properties.unit               The unit used for the property
   *    (example: Ko, seconds...).
   * @apiSuccess {null|String}      types.properties.description        The description of the propery.
   * @apiSuccess {Boolean}          types.properties.canbenull          The property can be null or not.
   * @apiSuccess {Boolean}          types.properties.setcurrentdate     The property in the item can
   *    automatically use the current date when store in DB.
   * @apiSuccess {null|String}      types.properties.regexformat        The regexformat to verify the value
   *    is conform (works only with valuetype is string or list).
   * @apiSuccess {null|String[]}    types.properties.listvalues         The list of values when
   *    valuetype="list", else null.
   * @apiSuccess {Any}              types.properties.default            The default value.
   * @apiSuccess {Object[]}         types.propertygroups                The properties groups list.
   * @apiSuccess {Number}           types.propertygroups.id             The id of the properties group.
   * @apiSuccess {String}           types.propertygroups.name           The name of the properties group.
   * @apiSuccess {Number}           types.propertygroups.position       The position of the properties
   *    group, related to other groups of the type.
   * @apiSuccess {Number[]}         types.propertygroups.properties     The id list of properties of the
   *    properties group.
   * @apiSuccess {ISO8601}          types.propertygroups.created_at     Date of the item creation.
   * @apiSuccess {null|ISO8601}     types.propertygroups.updated_at     Date of the last item modification.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 23,
   *     "name": "Memory",
   *     "internalname": "memory",
   *     "sub_organization": false,
   *     "created_at": "2022-08-11T00:38:57.000000Z",
   *     "updated_at": "022-08-11T10:03:17.000000Z",
   *     "deleted_at": null
   *     "created_by": {
   *       "id": 2,
   *       "name": "admin",
   *       "first_name": "Steve",
   *       "last_name": "Rogers"
   *     },
   *     "updated_by": {
   *       "id": 3,
   *       "name": "tstark",
   *       "first_name": "Tony",
   *       "last_name": "Stark"
   *     },
   *     "deleted_by": null,
   *     "properties": [
   *       {
   *         "id": 6,
   *         "name": "Serial number",
   *         "internalname": "serialnumber",
   *         "valuetype": "string",
   *         "regexformat": null,
   *         "unit": null,
   *         "description": null,
   *         "canbenull": true,
   *         "setcurrentdate": null,
   *         "listvalues": [],
   *         "default": "",
   *       }
   *     ],
   *     "propertygroups": [],
   *     "organization": {
   *       "id": 4,
   *       "name": "suborg_2"
   *     }
   *   }
   * ]
   *
   */
  public function getAll(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $organizations = \App\v1\Common::getOrganizationsIds($token);
    $parentsOrganizations = \App\v1\Common::getParentsOrganizationsIds($token);

    $params = $this->manageParams($request);

    $items = \App\v1\Models\Config\Type::ofSort($params)
    ->where(function ($query) use ($organizations, $parentsOrganizations)
    {
      $query->whereIn('organization_id', $organizations)
            ->orWhere(function ($query2) use ($parentsOrganizations)
            {
              $query2->whereIn('organization_id', $parentsOrganizations)
                     ->where('sub_organization', true);
            });
    })->get();
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
   * @apiSuccess {Number}           id                            The id of the type.
   * @apiSuccess {String}           name                          The name of the type.
   * @apiSuccess {String}           internalname                  The internalname of the type.
   * @apiSuccess {String="logical","physical"} modeling           The model of the type.
   * @apiSuccess {Boolean}          tree                          Set if the items of this type are
   *    in a tree.
   * @apiSuccess {Boolean}          allowtreemultipleroots        Set if the items of this type can
   *    have multiple roots.
   * @apiSuccess {ISO8601}          created_at                    Date of the type creation.
   * @apiSuccess {null|ISO8601}     updated_at                    Date of the last type modification.
   * @apiSuccess {null|ISO8601}     deleted_at                    Date of the soft delete of the type.
   * @apiSuccess {null|Object}      created_by                    User has created the type.
   * @apiSuccess {Number}           created_by.id                 Id of the user has created the type.
   * @apiSuccess {String}           created_by.name               Name (login) of the user has created the type.
   * @apiSuccess {String}           created_by.first_name         First name of the user has created the type.
   * @apiSuccess {String}           created_by.last_name          Last name of the user has created the type.
   * @apiSuccess {null|Object}      updated_by                    User has updated the type.
   * @apiSuccess {Number}           updated_by.id                 Id of the user has updated the type.
   * @apiSuccess {String}           updated_by.name               Name (login) of the user has updated the type.
   * @apiSuccess {String}           updated_by.first_name         First name of the user has updated the type.
   * @apiSuccess {String}           updated_by.last_name          Last name of the user has updated the type.
   * @apiSuccess {null|Object}      deleted_by                    User has soft deleted the type.
   * @apiSuccess {Number}           deleted_by.id                 Id of the user has soft deleted the type.
   * @apiSuccess {String}           deleted_by.name               Name (login) of the user has soft deleted the type.
   * @apiSuccess {String}           deleted_by.first_name         First name of the user has soft deleted the type.
   * @apiSuccess {String}           deleted_by.last_name          Last name of the user has soft deleted the type.
   * @apiSuccess {Object[]}         properties                    The properties list.
   * @apiSuccess {Number}           properties.id                 The id of the property.
   * @apiSuccess {String}           properties.name               The name of the property.
   * @apiSuccess {String}           properties.internalname       The internalname of the property.
   * @codingStandardsIgnoreStart because break apidocsjs
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink","itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  properties.valuetype   The type of value.
   * @codingStandardsIgnoreEnd
   * @apiSuccess {null|String}      properties.unit               The unit used for the property
   *    (example: Ko, seconds...).
   * @apiSuccess {null|String}      properties.description        The description of the propery.
   * @apiSuccess {Boolean}          properties.canbenull          The property can be null or not.
   * @apiSuccess {Boolean}          properties.setcurrentdate     The property in the item can
   *    automatically use the current date when store in DB.
   * @apiSuccess {null|String}      properties.regexformat        The regexformat to verify the value
   *    is conform (works only with valuetype is string or list).
   * @apiSuccess {null|String[]}    properties.listvalues         The list of values when
   *    valuetype="list", else null.
   * @apiSuccess {Any}              properties.default            The default value.
   * @apiSuccess {Object[]}         propertygroups                The properties groups list.
   * @apiSuccess {Number}           propertygroups.id             The id of the properties group.
   * @apiSuccess {String}           propertygroups.name           The name of the properties group.
   * @apiSuccess {Number}           propertygroups.position       The position of the properties
   *    group, related to other groups of the type.
   * @apiSuccess {Number[]}         propertygroups.properties     The id list of properties of the
   *    properties group.
   * @apiSuccess {ISO8601}          propertygroups.created_at     Date of the item creation.
   * @apiSuccess {null|ISO8601}     propertygroups.updated_at     Date of the last item modification.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 23,
   *   "name": "Memory",
   *   "internalname": "memory",
   *   "sub_organization": false,
   *   "created_at": "2022-08-11T00:38:57.000000Z",
   *   "updated_at": "022-08-11T10:03:17.000000Z",
   *   "deleted_at": null
   *   "created_by": {
   *     "id": 2,
   *     "name": "admin",
   *     "first_name": "Steve",
   *     "last_name": "Rogers"
   *   },
   *   "updated_by": {
   *     "id": 3,
   *     "name": "tstark",
   *     "first_name": "Tony",
   *     "last_name": "Stark"
   *   },
   *   "deleted_by": null,
   *   "properties": [
   *     {
   *       "id": 6,
   *       "name": "Serial number",
   *       "internalname": "serialnumber",
   *       "valuetype": "string",
   *       "regexformat": null,
   *       "unit": null,
   *       "description": null,
   *       "canbenull": true,
   *       "setcurrentdate": null,
   *       "listvalues": [],
   *       "default": "",
   *     }
   *   ],
   *   "propertygroups": [],
   *   "organization": {
   *     "id": 4,
   *     "name": "suborg_2"
   *   }
   * }
   *
   */
  public function getOne(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $organizations = \App\v1\Common::getOrganizationsIds($token);
    $parentsOrganizations = \App\v1\Common::getParentsOrganizationsIds($token);

    $item = \App\v1\Models\Config\Type::withTrashed()->find($args['id']);
    if (is_null($item))
    {
      throw new \Exception("This type has not be found", 404);
    }
    if (
        !in_array($item->organization_id, $organizations)
        && (!(in_array($item->organization_id, $parentsOrganizations) && $item->sub_organization))
    )
    {
      throw new \Exception("This type is not in your organization", 403);
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
   * @apiBody {String}       name                             The name of the type of items.
   * @apiBody {Null|Number}  [organization_id]                The id of the organization. If null or not defined, use
   *    the user default organization_id.
   * @apiBody {boolean}      [sub_organization]               Define of the item can be viewed in sub organizations.
   * @apiBody {Boolean}      [tree=false]                     Set if the items of this type are in a tree.
   * @apiBody {Boolean}      [allowtreemultipleroots=false]   Set if the items of this type are in a tree and can
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
    $token = (object)$request->getAttribute('token');

    $data = json_decode($request->getBody());

    $type = $this->createType($data, $token);

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
   * @apiBody {String}  [name]      Name of the type.
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
    $token = (object)$request->getAttribute('token');

    $data = json_decode($request->getBody());
    $type = \App\v1\Models\Config\Type::withTrashed()->find($args['id']);

    if (is_null($type))
    {
      throw new \Exception("The type has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'name' => 'type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);
    if (property_exists($data, 'name'))
    {
      $type->name = $data->name;
    }
    if ($type->trashed())
    {
      $type->restore();
    }
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
    $token = (object)$request->getAttribute('token');

    $type = \App\v1\Models\Config\Type::withTrashed()->find($args['id']);

    if (is_null($type))
    {
      throw new \Exception("The type has not be found", 404);
    }

    $this->denyDeleteType($type->internalname);

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
    $token = (object)$request->getAttribute('token');
    $organizations = \App\v1\Common::getOrganizationsIds($token);
    $parentsOrganizations = \App\v1\Common::getParentsOrganizationsIds($token);

    $type = \App\v1\Models\Config\Type::find($args['id']);
    if (is_null($type))
    {
      throw new \Exception("The type has not be found", 404);
    }
    if (!in_array($type->organization_id, $organizations))
    {
      if (!(in_array($type->organization_id, $parentsOrganizations) && $type->sub_organization))
      {
        throw new \Exception("This type is not in your organization", 403);
      }
    }

    $property = \App\v1\Models\Config\Property::find($args['propertyid']);
    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }
    $parentsOrganizations = \App\v1\Common::getParentsOrganizationsIds($type);
    if (
        !($property->organization_id == $type->organization_id
        || (in_array($property->organization_id, $parentsOrganizations)
          && $property->sub_organization)
        )
    )
    {
      throw new \Exception("This property is not in your organization", 403);
    }
    if (
        $property->organization_id == $type->organization_id
        && $type->sub_organization
        && !$property->sub_organization
    )
    {
      throw new \Exception("This property not sub organization can't be used with type with sub organization", 403);
    }

    // Note : no need to check if relation exist. If exist, return error 'The element already exists'

    $type->properties()->attach($args['propertyid']);
    // use touch() to update updated_at in the type
    $type->touch();

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
    $token = (object)$request->getAttribute('token');

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

    $this->denyDetachProperty($type->internalname, $property->internalname);

    // TODO check if relation exists

    $type->properties()->detach($args['propertyid']);
    // use touch() to update updated_at in the type
    $type->touch();

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
   * @codingStandardsIgnoreStart because break apidocsjs
   * @apiBody {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink","itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  types.propertygroups.properties.valuetype  The type of value.
   * @codingStandardsIgnoreEnd
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
    $token = (object)$request->getAttribute('token');

    $data = json_decode($request->getBody());

    $this->createTemplate($data, $token);

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }


  /********************
   * Private functions
   ********************/

  public function createType($data, $token)
  {
    // Validate the data format
    $dataFormat = [
      'name'                   => 'required|type:string',
      'tree'                   => 'type:boolean|boolean',
      'allowtreemultipleroots' => 'type:boolean|boolean',
      'organization_id'        => 'type:integer|integer',
      'sub_organization'       => 'type:boolean|boolean',
      'modeling'               => 'type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $type = new \App\v1\Models\Config\Type();
    $type->name = $data->name;
    $type->organization_id = $token->organization_id;
    if (property_exists($data, 'organization_id'))
    {
      $type->organization_id = $data->organization_id;
    }
    if (property_exists($data, 'sub_organization'))
    {
      $type->sub_organization = $data->sub_organization;
    }
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
    if (property_exists($data, 'modeling'))
    {
      $type->modeling = $data->modeling;
    }
    $type->save();

    return $type;
  }

  public function createTemplate($data, $token)
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
      $typeItem = \App\v1\Models\Config\Type::where('internalname', $type->internalname)->first();
      if (is_null($typeItem)) {
        $typeItem = $this->createType($type, $token);
      }
      $typeId = $typeItem->id;

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
            $propertyListId[] = $ctrlProperty->createProperty($property, $token);
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

  /**
   * check if the type can be deleted, if not exception is thrown
   */
  private function denyDeleteType($internalname)
  {
    if ($internalname == 'organization' || $internalname == 'users')
    {
      // case Organization and User type cannot be deleted
      throw new \Exception('Cannot delete this type, it is a system type', 403);
    }
  }

  /**
   * check if the property can be detached from type, if not exception is thrown
   */
  private function denyDetachProperty($typeInternalname, $propertyInternalname)
  {
    $properties = \App\v1\Controllers\Config\Property::getProtectedProperties();
    if ($typeInternalname == 'users' && in_array($propertyInternalname, $properties))
    {
      // case User type cannot be detached from userfirstname, userlastname, userrefreshtoken, userjwtid
      throw new \Exception('Cannot detach this property, it is a system property', 403);
    }
  }
}
