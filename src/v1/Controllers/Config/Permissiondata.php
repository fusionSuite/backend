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

final class Permissiondata
{
  /**
   * @api {patch} /v1/config/roles/:id/permissiondata/:typeid Request update the permission of a type releated to a role
   * @apiName PatchConfigRolePermissiondata
   * @apiGroup Config/Roles/Permissiondata
   * @apiVersion 1.0.0-draft
   * @apiDescription It's possible to use permission=none and view=true, it will disable all and allow
   *    only view, same behavior for permission=grant and delete=false
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the role.
   * @apiParam {Number}    typeid    Unique ID of the type (/config/types).
   *
   * @apiBody {String=grant,none}  [permission]   The permission for items of this type (will allow all
   *    or remove all permissions).
   * @apiBody {Boolean}            [view]         the permission to view the items of this type.
   * @apiBody {Boolean}            [create]       the permission to create items of this type.
   * @apiBody {Boolean}            [update]       the permission to update items of this type.
   * @apiBody {Boolean}            [softdelete]   the permission to soft delete the items of this type.
   * @apiBody {Boolean}            [delete]       the permission to hard delete the items of this type.
   * @apiBody {Boolean}            [propertiescustom]   Set to true if want custom permissions
   *    on properties of the type.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "view": true,
   *   "create": true,
   *   "update": true,
   *   "softdelete": false
   * }
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
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
    if ($role->trashed())
    {
      throw new \Exception("The role is soft deleted, can't modify permissions in this state", 400);
    }
    $permissiondata = \App\v1\Models\Config\Permissiondata::find($args['typeid']);
    if (is_null($permissiondata))
    {
      throw new \Exception("The permissiondata of this type has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'permission'       => 'type:string',
      'view'             => 'type:boolean',
      'create'           => 'type:boolean',
      'update'           => 'type:boolean',
      'softdelete'       => 'type:boolean',
      'delete'           => 'type:boolean',
      'propertiescustom' => 'type:boolean',
    ];
    \App\v1\Common::validateData($data, $dataFormat);
    if (
        property_exists($data, 'permission')
        && !in_array($data->permission, ['grant', 'none'])
    )
    {
      throw new \Exception("The value of permission is wrong, allow only grant or none", 400);
    }

    if (property_exists($data, 'permission'))
    {
      if ($data->permission == 'grant')
      {
        $permissiondata->view = true;
        $permissiondata->create = true;
        $permissiondata->update = true;
        $permissiondata->softdelete = true;
        $permissiondata->delete = true;
      }
      else // none
      {
        $permissiondata->view = false;
        $permissiondata->create = false;
        $permissiondata->update = false;
        $permissiondata->softdelete = false;
        $permissiondata->delete = false;
      }
    }
    $permissions = ['view', 'create', 'update', 'softdelete', 'delete'];
    foreach ($permissions as $permission)
    {
      if (property_exists($data, $permission))
      {
        $permissiondata->{$permission} = $data->{$permission};
      }
    }
    if (property_exists($data, 'propertiescustom'))
    {
      $permissiondata->propertiescustom = $data->propertiescustom;
      if ($data->propertiescustom)
      {
        \App\v1\Controllers\Config\Permissiondataproperty::
          createProperties($args['typeid'], $permissiondata->type_id);
      }
    }
    $permissiondata->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function createTypes($roleId)
  {
    // get all types defined on this permissiondata and for this role
    $permissionTypes = [];
    $permissiondatas = \App\v1\Models\Config\Permissiondata::where('role_id', $roleId)->get();
    foreach ($permissiondatas as $permission)
    {
      $permissionTypes[$permission->type->id] = true;
    }

    // get all types in DB
    $types = \App\v1\Models\Config\Type::all();
    foreach ($types as $type)
    {
      if (!isset($permissionTypes[$type->id]))
      {
        // add missing types (default all permissions to false)
        $permissiondata = new \App\v1\Models\Config\Permissiondata();
        $permissiondata->role_id = $roleId;
        $permissiondata->type_id = $type->id;
        $permissiondata->save();
      }
    }
  }

  /**
   * Delete type
   * if typeId is null, delete all, otherwise only this type
   */
  public static function deleteTypes($roleId, $typeId = null)
  {
    $permissiondata = \App\v1\Models\Config\Permissiondata::where('role_id', $roleId);
    if (!is_null($typeId))
    {
      $permissiondata->where('type_id', $typeId);
    }
    $permissiondatas = $permissiondata->get();
    foreach ($permissiondatas as $permissionitem)
    {
      \App\v1\Controllers\Config\Permissiondataproperty::deleteProperties($permissionitem->id);
      $permissionitem->delete();
    }
  }
}
