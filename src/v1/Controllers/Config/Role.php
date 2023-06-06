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

final class Role
{
  use \App\v1\Read;

  /**
   * @api {get} /v1/config/roles Get all roles
   * @apiName GetConfigRoles
   * @apiGroup Config/Roles
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Object[]}  roles                    List of roles.
   * @apiSuccess {Number}    roles.id                 The id of the role.
   * @apiSuccess {String}    roles.name               The name of the role.
   * @apiSuccess {String='grant,none,custom}  roles.permissionstructure  The structure global
   *    permissions (types and properties).
   * @apiSuccess {String='grant,none,custom}  roles.permissiondata       The data (items) global permissions.
   * @apiSuccess {Object[]}  roles.users              The users list.
   * @apiSuccess {Number}    roles.users.id           Id of the user has this role.
   * @apiSuccess {String}    roles.users.name         Name (login) of the user has this role.
   * @apiSuccess {String}    roles.users.first_name   First name of the user has this role.
   * @apiSuccess {String}    roles.users.last_name    Last name of the user has this role.
   * @apiSuccess {Object[]}  roles.permissiondatas    The data permissions in case permissiondata = custom.
   * @apiSuccess {Number}    roles.permissiondatas.id               Id of the permissiondata.
   * @apiSuccess {Object}    roles.permissiondatas.type             The type related to this permission.
   * @apiSuccess {Number}    roles.permissiondatas.type.id          The id of the type.
   * @apiSuccess {String}    roles.permissiondatas.type.name        The name of the type.
   * @apiSuccess {String}    roles.permissiondatas.type.internalname The internalname of the type.
   * @apiSuccess {Boolean}   roles.permissiondatas.view             Can the items of the type can be viewed.
   * @apiSuccess {Boolean}   roles.permissiondatas.create           Can the items of the type can be created.
   * @apiSuccess {Boolean}   roles.permissiondatas.update           Can the items of the type can be updated.
   * @apiSuccess {Boolean}   roles.permissiondatas.softdelete       Can the items of the type can be soft deleted.
   * @apiSuccess {Boolean}   roles.permissiondatas.delete           Can the items of the type can be deleted.
   * @apiSuccess {Boolean}   roles.permissiondatas.propertiescustom Can the properties of the items
   *    have specific permissions.
   * @apiSuccess {Object[]}  roles.permissiondatas.properties  The properties permissions in case propertiescustom true.
   * @apiSuccess {Number}    roles.permissiondatas.properties.id        The id of the property permission.
   * @apiSuccess {Boolean}   roles.permissiondatas.properties.view      Can the property can be viewed.
   * @apiSuccess {Boolean}   roles.permissiondatas.properties.update    Can the property can be updated.
   * @apiSuccess {Object}    roles.permissiondatas.properties.property  The property related to this special permission.
   * @apiSuccess {Number}    roles.permissiondatas.properties.property.id    The id of the property.
   * @apiSuccess {String}    roles.permissiondatas.properties.property.name  The name of the property.
   * @apiSuccess {Number}    roles.permissiondatas.properties.property.internalname  The internalname of the property.
   * @apiSuccess {Object[]}  roles.permissionstructures  The structure permissions in case permissionstructure = custom.
   * @apiSuccess {Number}    roles.permissionstructures.id               Id of the permissionstructure.
   * @apiSuccess {String=config/type,config/property,config/role}  roles.permissionstructures.typeofitem
   *    The type of item.
   * @apiSuccess {Object}    roles.permissionstructures.item             The item related to this permission.
   * @apiSuccess {Number}    roles.permissionstructures.item.id          The id of the item.
   * @apiSuccess {String}    roles.permissionstructures.item.name        The name of the type.
   * @apiSuccess {String}    roles.permissionstructures.item.internalname  The internalname of the type.
   * @apiSuccess {Boolean}   roles.permissionstructures.view             Can the items of the type can be viewed.
   * @apiSuccess {Boolean}   roles.permissionstructures.create           Can the items of the type can be created.
   * @apiSuccess {Boolean}   roles.permissionstructures.update           Can the items of the type can be updated.
   * @apiSuccess {Boolean}   roles.permissionstructures.softdelete       Can the items of the type can be soft deleted.
   * @apiSuccess {Boolean}   roles.permissionstructures.delete           Can the items of the type can be deleted.
   * @apiSuccess {ISO8601}          roles.created_at                    Date of the role creation.
   * @apiSuccess {null|ISO8601}     roles.updated_at                    Date of the last role modification.
   * @apiSuccess {null|ISO8601}     roles.deleted_at                    Date of the soft delete of the role.
   * @apiSuccess {null|Object}      roles.created_by                    User has created the role.
   * @apiSuccess {Number}           roles.created_by.id                 Id of the user has created the role.
   * @apiSuccess {String}           roles.created_by.name               Name (login) of the user has created the role.
   * @apiSuccess {String}           roles.created_by.first_name         First name of the user has created the role.
   * @apiSuccess {String}           roles.created_by.last_name          Last name of the user has created the role.
   * @apiSuccess {null|Object}      roles.updated_by                    User has updated the role.
   * @apiSuccess {Number}           roles.updated_by.id                 Id of the user has updated the role.
   * @apiSuccess {String}           roles.updated_by.name               Name (login) of the user has updated the role.
   * @apiSuccess {String}           roles.updated_by.first_name         First name of the user has updated the role.
   * @apiSuccess {String}           roles.updated_by.last_name          Last name of the user has updated the role.
   * @apiSuccess {null|Object}      roles.deleted_by                    User has soft deleted the role.
   * @apiSuccess {Number}           roles.deleted_by.id                 Id of the user has soft deleted the role.
   * @apiSuccess {String}           roles.deleted_by.name               Name (login) of the user has soft deleted
   *    the role.
   * @apiSuccess {String}           roles.deleted_by.first_name         First name of the user has soft deleted
   *    the role.
   * @apiSuccess {String}           roles.deleted_by.last_name          Last name of the user has soft deleted the role.
   *
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 1,
   *     "name": "admin",
   *     "permissionstructure": "grant",
   *     "permissiondata": "grant",
   *     "users": [
   *       {
   *         "id":2,
   *         "name":"admin",
   *         "first_name":"Steve",
   *         "last_name":"Rogers"
   *       }
   *     ],
   *     "permissiondatas": [],
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
   *   },
   *   {
   *     "id": 2,
   *     "name": "role1",
   *     "permissionstructure": "grant",
   *     "permissiondata": "custom",
   *     "users": [
   *       {
   *         "id": 3,
   *         "name": "user1",
   *         "first_name": "",
   *         "last_name": ""
   *       }
   *     ],
   *     "permissiondatas": [
   *       {
   *         "id": 1,
   *         "view": false,
   *         "create": false,
   *         "update": false,
   *         "softdelete": false,
   *         "delete": false,
   *         "propertiescustom": false,
   *         "type": {
   *           "id": 1,
   *           "name": "Organization",
   *           "internalname": "organization"
   *         },
   *         "properties": []
   *       },
   *       {
   *         "id": 3,
   *         "view": true,
   *         "create": true,
   *         "update": false,
   *         "softdelete": false,
   *         "delete": false,
   *         "propertiescustom": true,
   *         "type": {
   *           "id": 3,
   *           "name": "Laptop",
   *           "internalname": "laptop"
   *         },
   *         "properties": [
   *           {
   *             "id": 1,
   *             "view": true,
   *             "update": false,
   *             "property": {
   *               "id": 10,
   *               "name": "Serial number",
   *               "internalname": "serialnumber"
   *             }
   *           },
   *           {
   *             "id": 2,
   *             "view": true,
   *             "update": true,
   *             "property": {
   *             "id": 11,
   *             "name": "Inventory number",
   *             "internalname": "inventorynumber"
   *           }
   *         ]
   *       }
   *     ],
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
   *   }
   * ]
   *
   */
  public function getAll(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $params = $this->manageParams($request);

    $role = \App\v1\Models\Config\Role::ofSort($params);

    // manage permissions
    \App\v1\Permission::checkPermissionToStructure('view', 'config/role');
    $permissionIds = \App\v1\Permission::getStructureViewIds('config/role');
    if (!is_null($permissionIds))
    {
      $role->where('id', $permissionIds);
    }
    $roles = $role->get();

    $response->getBody()->write($roles->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {get} /v1/config/roles/:id Get one role
   * @apiName GetConfigRole
   * @apiGroup Config/Roles
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Number}    id                 The id of the role.
   * @apiSuccess {String}    name               The name of the role.
   * @apiSuccess {String='grant,none,custom}  permissionstructure  The structure global
   *    permissions (types and properties).
   * @apiSuccess {String='grant,none,custom}  permissiondata       The data (items) global permissions.
   * @apiSuccess {Object[]}  users              The users list.
   * @apiSuccess {Number}    users.id           Id of the user has this role.
   * @apiSuccess {String}    users.name         Name (login) of the user has this role.
   * @apiSuccess {String}    users.first_name   First name of the user has this role.
   * @apiSuccess {String}    users.last_name    Last name of the user has this role.
   * @apiSuccess {Object[]}  permissiondatas    The data permissions in case permissiondata = custom.
   * @apiSuccess {Number}    permissiondatas.id               Id of the permissiondata.
   * @apiSuccess {Object}    permissiondatas.type             The type related to this permission.
   * @apiSuccess {Number}    permissiondatas.type.id          The id of the type.
   * @apiSuccess {String}    permissiondatas.type.name        The name of the type.
   * @apiSuccess {String}    permissiondatas.type.internalname  The internalname of the type.
   * @apiSuccess {Boolean}   permissiondatas.view             Can the items of the type can be viewed.
   * @apiSuccess {Boolean}   permissiondatas.create           Can the items of the type can be created.
   * @apiSuccess {Boolean}   permissiondatas.update           Can the items of the type can be updated.
   * @apiSuccess {Boolean}   permissiondatas.softdelete       Can the items of the type can be soft deleted.
   * @apiSuccess {Boolean}   permissiondatas.delete           Can the items of the type can be deleted.
   * @apiSuccess {Boolean}   permissiondatas.propertiescustom Can the properties of the items
   *    have specific permissions.
   * @apiSuccess {Object[]}  permissiondatas.properties  The properties permissions in case propertiescustom true.
   * @apiSuccess {Number}    permissiondatas.properties.id        The id of the property permission.
   * @apiSuccess {Boolean}   permissiondatas.properties.view      Can the property can be viewed.
   * @apiSuccess {Boolean}   permissiondatas.properties.update    Can the property can be updated.
   * @apiSuccess {Object}    permissiondatas.properties.property  The property related to this special permission.
   * @apiSuccess {Number}    permissiondatas.properties.property.id    The id of the property.
   * @apiSuccess {String}    permissiondatas.properties.property.name  The name of the property.
   * @apiSuccess {Number}    permissiondatas.properties.property.internalname  The internalname of the property.
   * @apiSuccess {Object[]}  permissionstructures    The structure permissions in case permissionstructure = custom.
   * @apiSuccess {Number}    permissionstructures.id               Id of the permissionstructure.
   * @apiSuccess {String=config/type,config/property,config/role}  permissionstructures.typeofitem
   *    The type of item.
   * @apiSuccess {Object}    permissionstructures.item             The item related to this permission.
   * @apiSuccess {Number}    permissionstructures.item.id          The id of the item.
   * @apiSuccess {String}    permissionstructures.item.name        The name of the type.
   * @apiSuccess {String}    permissionstructures.item.internalname  The internalname of the type.
   * @apiSuccess {Boolean}   permissionstructures.view             Can the items of the type can be viewed.
   * @apiSuccess {Boolean}   permissionstructures.create           Can the items of the type can be created.
   * @apiSuccess {Boolean}   permissionstructures.update           Can the items of the type can be updated.
   * @apiSuccess {Boolean}   permissionstructures.softdelete       Can the items of the type can be soft deleted.
   * @apiSuccess {Boolean}   permissionstructures.delete           Can the items of the type can be deleted.
   * @apiSuccess {ISO8601}       created_at                    Date of the role creation.
   * @apiSuccess {null|ISO8601}  updated_at                    Date of the last role modification.
   * @apiSuccess {null|ISO8601}  deleted_at                    Date of the soft delete of the role.
   * @apiSuccess {null|Object}   created_by                    User has created the role.
   * @apiSuccess {Number}        created_by.id                 Id of the user has created the role.
   * @apiSuccess {String}        created_by.name               Name (login) of the user has created the role.
   * @apiSuccess {String}        created_by.first_name         First name of the user has created the role.
   * @apiSuccess {String}        created_by.last_name          Last name of the user has created the role.
   * @apiSuccess {null|Object}   updated_by                    User has updated the role.
   * @apiSuccess {Number}        updated_by.id                 Id of the user has updated the role.
   * @apiSuccess {String}        updated_by.name               Name (login) of the user has updated the role.
   * @apiSuccess {String}        updated_by.first_name         First name of the user has updated the role.
   * @apiSuccess {String}        updated_by.last_name          Last name of the user has updated the role.
   * @apiSuccess {null|Object}   deleted_by                    User has soft deleted the role.
   * @apiSuccess {Number}        deleted_by.id                 Id of the user has soft deleted the role.
   * @apiSuccess {String}        deleted_by.name               Name (login) of the user has soft deleted the role.
   * @apiSuccess {String}        deleted_by.first_name         First name of the user has soft deleted the role.
   * @apiSuccess {String}        deleted_by.last_name          Last name of the user has soft deleted the role.
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 2,
   *   "name": "role1",
   *   "permissionstructure": "grant",
   *   "permissiondata": "custom",
   *   "users": [
   *     {
   *       "id": 3,
   *       "name": "user1",
   *       "first_name": "",
   *       "last_name": ""
   *     }
   *   ],
   *   "permissiondatas": [
   *     {
   *       "id": 1,
   *       "view": false,
   *       "create": false,
   *       "update": false,
   *       "softdelete": false,
   *       "delete": false,
   *       "propertiescustom": false,
   *       "type": {
   *         "id": 1,
   *         "name": "Organization",
   *         "internalname": "organization"
   *       },
   *       "properties": []
   *     },
   *     {
   *       "id": 3,
   *       "view": true,
   *       "create": true,
   *       "update": false,
   *       "softdelete": false,
   *       "delete": false,
   *       "propertiescustom": true,
   *       "type": {
   *         "id": 3,
   *         "name": "Laptop",
   *         "internalname": "laptop"
   *       },
   *       "properties": [
   *         {
   *           "id": 1,
   *           "view": true,
   *           "update": false,
   *           "property": {
   *             "id": 10,
   *             "name": "Serial number",
   *             "internalname": "serialnumber"
   *           }
   *         },
   *         {
   *           "id": 2,
   *           "view": true,
   *           "update": true,
   *           "property": {
   *           "id": 11,
   *           "name": "Inventory number",
   *           "internalname": "inventorynumber"
   *         }
   *       ]
   *     }
   *   ],
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
   * }
   *
   */
  public function getOne(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    // check permissions
    \App\v1\Permission::checkPermissionToStructure('view', 'config/role', $args['id']);

    $role = \App\v1\Models\Config\Role::withTrashed()->find($args['id']);
    if (is_null($role))
    {
      throw new \Exception("This role has not be found", 404);
    }

    $response->getBody()->write($role->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/config/roles Create a role
   * @apiName PostConfigRole
   * @apiGroup Config/Roles
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiBody {String}      name     The name of the role.
   * @apiBody {String=none,grant,custom}   [permissionstructure=none]   The permission of structure (type, properties).
   * @apiBody {String=none,grant,custom}   [permissiondata=none]        The permission of date (items).
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "technician",
   *   "permissiondata": "grant"
   * }
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 2
   * }
   *
   */
  public function postItem(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    // check permissions
    \App\v1\Permission::checkPermissionToStructure('create', 'config/role');

    $data = json_decode($request->getBody());
    // Validate the data format
    $dataFormat = [
      'name'                   => 'required|type:string',
      'permissionstructure'    => 'type:string',
      'permissiondata'         => 'type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $permissions = ['none', 'grant', 'custom'];
    if (
        property_exists($data, 'permissionstructure')
        && !in_array($data->permissionstructure, $permissions)
    )
    {
      throw new \Exception("The permissionstructure value is not allowed", 400);
    }
    if (
        property_exists($data, 'permissiondata')
        && !in_array($data->permissiondata, $permissions)
    )
    {
      throw new \Exception("The permissiondata value is not allowed", 400);
    }

    // TODO
    // if current role associated to the user create the role not have grant access, it will copy his role with
    // the same rights

    $roleId = $this->createRole($data);

    // Add to permissions
    \App\v1\Controllers\Config\Permissionstructure::addEndpointIdToRoles('config/role', $roleId);

    $response->getBody()->write(json_encode(["id" => $roleId]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {patch} /v1/config/roles/:id Update an existing role
   * @apiName PatchConfigRole
   * @apiGroup Config/Roles
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the role.
   *
   * @apiBody {String}     [name]    The name of the role.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "technician bis"
   * }
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   *
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The role has not be found"
   * }
   *
   */
  public function patchItem(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $data = json_decode($request->getBody());
    $role = \App\v1\Models\Config\Role::withTrashed()->find($args['id']);

    if (is_null($role))
    {
      throw new \Exception("The role has not be found", 404);
    }

    // check permissions
    \App\v1\Permission::checkPermissionToStructure('update', 'config/role', $role->id);

    // Validate the data format
    $dataFormat = [
      'name' => 'type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);
    if (property_exists($data, 'name'))
    {
      $role->name = $data->name;
    }
    if ($role->trashed())
    {
      $role->restore();
    }
    $role->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/config/roles/:id Delete a role
   * @apiName DeleteConfigRole
   * @apiGroup Config/Roles
   * @apiVersion 1.0.0-draft
   * @apiDescription The first delete request will do a soft delete. The second delete request will permanently
   *                 delete the item
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the role.
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

    $role = \App\v1\Models\Config\Role::withTrashed()->find($args['id']);

    if (is_null($role))
    {
      throw new \Exception("The type has not be found", 404);
    }

    // If in soft trash, delete permanently
    if ($role->trashed())
    {
      // check permissions
      \App\v1\Permission::checkPermissionToStructure('delete', 'config/role', $role->id);

      $role->users()->detach();
      \App\v1\Controllers\Config\Permissiondata::deleteTypes($role->id);
      $role->forceDelete();

      // Post delete actions
      \App\v1\Controllers\Config\Permissionstructure::deleteEndpointIdToRoles(
        'config/role',
        $args['id']
      );
    }
    else
    {
      // check permissions
      \App\v1\Permission::checkPermissionToStructure(
        'softdelete',
        'config/role',
        $role->id
      );

      $role->delete();
    }

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/config/roles/:id/user/:userid Associate a user to this role
   * @apiName PostConfigRolesUser
   * @apiGroup Config/Roles/user
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id         Unique ID of the role.
   * @apiParam {Number}    userid     Unique ID of the user.
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
   *   "message": "The role has not be found"
   * }
   *
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The user has not be found"
   * }
   *
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The id is not a user"
   * }
   *
   */
  public function postUser(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $role = \App\v1\Models\Config\Role::query()->find($args['id']);
    if (is_null($role))
    {
      throw new \Exception("The role has not be found", 404);
    }

    $item = \App\v1\Models\Item::query()->find($args['userid']);
    if (is_null($item))
    {
      throw new \Exception("The user has not be found", 404);
    }
    if ($item->type_id != 2)
    {
      throw new \Exception("The id is not a user", 404);
    }

    // Note : no need to check if relation exist. If exist, return error 'The element already exists'

    $role->users()->attach($args['userid']);
    // use touch() to update updated_at in the type
    $role->touch();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/config/roles/:id/user/:userid Remove a user of this role
   * @apiName DeleteConfigRolesUser
   * @apiGroup Config/Roles/user
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id         Unique ID of the role.
   * @apiParam {Number}    userid     Unique ID of the user.
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
   *   "message": "The role has not be found"
   * }
   *
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The user has not be found"
   * }
   *
   */
  public function deleteUser(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $role = \App\v1\Models\Config\Role::query()->find($args['id']);
    if (is_null($role))
    {
      throw new \Exception("The role has not be found", 404);
    }

    $item = \App\v1\Models\Item::query()->find($args['userid']);
    if (is_null($item))
    {
      throw new \Exception("The user has not be found", 404);
    }

    // TODO check if relation exists

    $role->users()->detach($args['userid']);
    // use touch() to update updated_at in the type
    $role->touch();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function createRole($data)
  {
    $role = new \App\v1\Models\Config\Role();
    $role->name = $data->name;
    if (property_exists($data, 'permissionstructure'))
    {
      $role->permissionstructure = $data->permissionstructure;
    }
    if (property_exists($data, 'permissiondata'))
    {
      $role->permissiondata = $data->permissiondata;
    }
    $role->save();
    $roleId = intval($role->id);

    // case permissiondata is custom
    // create an permissiondata for each type_id
    if (
        property_exists($data, 'permissiondata')
        && $data->permissiondata == 'custom'
    )
    {
      \App\v1\Controllers\Config\Permissiondata::createTypes($roleId);
    }
    if (
        property_exists($data, 'permissionstructure')
        && $data->permissionstructure == 'custom'
    )
    {
      \App\v1\Controllers\Config\Permissionstructure::createStructures($roleId);
    }
    return $roleId;
  }

  /**
   * Generate permission object that will be cached and used to check the permission
   *
   * return an object
   *
   */
  public static function generatePermission($role_id)
  {
    $permission = [];

    $role = \App\v1\Models\Config\Role::query()->find($role_id);
    if (is_null($role))
    {
      return (object)[];
    }
    $permission['structure'] = $role->permissionstructure;
    $permission['data'] = $role->permissiondata;
    $permission['custom'] = [
      'structure' => [
        'config/type'         => [],
        'config/property'     => [],
        'config/role' => []
      ],
      'data'      => []
    ];

    // get permission data for this type_id
    // $permissionDatas = \App\v1\Models\Config\Permissiondata::where('role_id', $role_id)->get();
    foreach ($role->permissiondatas as $data)
    {
      $permission['custom']['data'][$data->type_id] = [
        'view'       => boolval($data->view),
        'create'     => boolval($data->create),
        'update'     => boolval($data->update),
        'softdelete' => boolval($data->softdelete),
        'delete'     => boolval($data->delete),
        'properties' => [
        ]
      ];
      if ($data->propertiescustom)
      {
        // get custom properties
        foreach ($data->properties as $propPerm)
        {
          $permission['custom']['data'][$data->type_id]['properties'][$propPerm->property_id] = [
            'view'   => boolval($propPerm->view),
            'update' => boolval($propPerm->update),
          ];
        }
      }
    }

    // Case for stucture is custom
    if ($role->permissionstructure == 'custom')
    {
      foreach ($role->permissionstructures as $str)
      {
        $permission['custom']['structure'][$str->endpoint] = [
          'view'       => $str->view,
          'create'     => $str->create,
          'update'     => $str->update,
          'softdelete' => $str->softdelete,
          'delete'     => $str->delete,
          'customs'    => []
/* TODO
[TODELETE] permission: grant, none, custom
view: grant, none, custom
create: grant, none
update: grant, none, custom
softdelete: grant, none, custom
delete: grant, none, custom

when get roles, if view= grant, the view in the custom return null

*/
        ];
        if (
            $str->view == 'custom'
            || $str->update == 'custom'
            || $str->softdelete == 'custom'
            || $str->delete == 'custom'
        )
        {
          // get customs
          foreach ($str->customs as $customProp)
          {
            $permission['custom']['structure'][$str->endpoint]['customs'][$customProp->endpoint_id] = [
              'view'       => boolval($customProp->view),
              'update'     => boolval($customProp->update),
              'softdelete' => boolval($customProp->softdelete),
              'delete'     => boolval($customProp->delete)
            ];
          }
        }
      }
    }
    return (object)$permission;
  }
}
