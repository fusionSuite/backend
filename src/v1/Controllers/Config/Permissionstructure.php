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

final class Permissionstructure
{
  public static $models = [
    'structuretypes'      => 'App\v1\Models\Config\Type',
    'structureproperties' => 'App\v1\Models\Config\Property'
  ];

  /**
   * @api {patch} /v1/config/roles/:id/permissionstructure/:structureid Request update the permission
   *    of a structure endpoint releated to a role
   * @apiName PatchConfigRolePermissionstructure
   * @apiGroup Config/Roles/Permissionstructure
   * @apiVersion 1.0.0-draft
   * @apiDescription It's possible to use permission=none and view=true, it will disable all and allow
   *    only view, same behavior for permission=grant and delete=false
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}                  id            Unique ID of the role.
   * @apiParam {Number}                  structureid   Unique ID of the structure ID.
   *
   * @apiBody {String=grant,none}          [permission]   The permission for items of this type (will allow all
   *    or remove all permissions).
   * @apiBody {String=grant,none,custom}   [view]         the permission to view the items of this type.
   * @apiBody {String=grant,none}          [create]       the permission to create items of this type.
   * @apiBody {String=grant,none,custom}   [update]       the permission to update items of this type.
   * @apiBody {String=grant,none,custom}   [softdelete]   the permission to soft delete the items of this type.
   * @apiBody {String=grant,none,custom}   [delete]       the permission to hard delete the items of this type.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "view": "grant",
   *   "create": "grant",
   *   "update": "grant",
   *   "softdelete": "none"
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
    $permissionstructure = \App\v1\Models\Config\Permissionstructure::find($args['structureid']);
    if (is_null($permissionstructure))
    {
      throw new \Exception("The permissionstructure of this type has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'permission' => 'type:string',
      'view'       => 'type:string',
      'create'     => 'type:string',
      'update'     => 'type:string',
      'softdelete' => 'type:string',
      'delete'     => 'type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);
    if (
        property_exists($data, 'permission')
        && !in_array($data->permission, ['grant', 'none'])
    )
    {
      throw new \Exception("The value of permission is wrong, allow only grant or none", 400);
    }
    $permissions = ['view', 'update', 'softdelete', 'delete'];
    foreach ($permissions as $permission)
    {
      if (
          property_exists($data, $permission)
          && !in_array($data->{$permission}, ['grant', 'none', 'custom'])
      )
      {
        throw new \Exception("The value of " . $permission . " is wrong, allow only grant, none or custom", 400);
      }
    }
    if (
        property_exists($data, 'create')
        && !in_array($data->create, ['grant', 'none'])
    )
    {
      throw new \Exception("The value of create is wrong, allow only grant or none", 400);
    }

    $perms = [];
    if (property_exists($data, 'permission'))
    {
      if ($data->permission == 'grant')
      {
        $permissionstructure->view = 'grant';
        $permissionstructure->create = 'grant';
        $permissionstructure->update = 'grant';
        $permissionstructure->softdelete = 'grant';
        $permissionstructure->delete = 'grant';
      }
      else // none
      {
        $permissionstructure->view = 'none';
        $permissionstructure->create = 'none';
        $permissionstructure->update = 'none';
        $permissionstructure->softdelete = 'none';
        $permissionstructure->delete = 'none';
      }
    }
    $permissions = ['view', 'create', 'update', 'softdelete', 'delete'];
    $isCustom = false;
    foreach ($permissions as $permission)
    {
      if (property_exists($data, $permission))
      {
        $permissionstructure->{$permission} = $data->{$permission};
        if ($data->{$permission} == 'custom')
        {
          $isCustom = true;
        }
      }
    }

    if ($isCustom)
    {
      \App\v1\Controllers\Config\Permissionstructurecustom::
        createCustoms($args['structureid'], $permissionstructure->endpoint);
    }

    $permissionstructure->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * create structure (types, properties, roles)
   */
  public static function createStructures($roleId)
  {
    $endpoints = [
      'config/type',
      'config/property',
      'config/role'
    ];
    $items = \App\v1\Models\Config\Permissionstructure::where('role_id', $roleId)->get();
    foreach ($items as $item)
    {
      if (in_array($item->endpoint, $endpoints))
      {
        unset($endpoints[array_search($item->endpoint, $endpoints)]);
      }
    }

    foreach ($endpoints as $endpoint)
    {
      $structure = new \App\v1\Models\Config\Permissionstructure();
      $structure->role_id = $roleId;
      $structure->endpoint = $endpoint;
      $structure->save();
    }
    return;
  }


  /**
   * delete structure (types, properties, roles)
   * if $endpoint is null, delete all, otherwise only this type
   */
  public static function deleteStructures($roleId, $endpoint = null)
  {
    $permissionstructure = \App\v1\Models\Config\Permissionstructure::where('role_id', $roleId);
    if (!is_null($endpoint))
    {
      $permissionstructure->where('endpoint', $endpoint);
    }
    $permissionstructures = $permissionstructure->get();
    foreach ($permissionstructures as $item)
    {
      $item->delete();
    }
  }

  /**
   * Add permission structure to endpoint in case 'custom' permission
   *
   * $endpoint string config/type, config/properties, config/role
   */
  public static function addEndpointIdToRoles($endpoint, $endpointId)
  {
    $roles = \App\v1\Models\Config\Role::get();

    foreach ($roles as $role)
    {
      foreach ($role->permissionstructures as $str)
      {
        if (
            $str->endpoint == $endpoint
            && (
              $str->view == 'custom'
              || $str->update == 'custom'
              || $str->softdelete == 'custom'
              || $str->delete == 'custom'
            )
        )
        {
          \App\v1\Controllers\Config\Permissionstructurecustom::
            createCustoms($str->id, $str->endpoint);
        }
      }
    }
  }

  /**
   *
   * $endpoint string config/type, config/properties, config/role
   */
  public static function deleteEndpointIdToRoles($endpoint, $endpointId)
  {
    $roles = \App\v1\Models\Config\Role::get();

    foreach ($roles as $role)
    {
      foreach ($role->permissionstructures as $str)
      {
        if (
            $str->endpoint == $endpoint
            && (
              $str->view == 'custom'
              || $str->update == 'custom'
              || $str->softdelete == 'custom'
              || $str->delete == 'custom'
            )
        )
        {
          \App\v1\Controllers\Config\Permissionstructurecustom::
            deleteCustoms($str->id, $str->endpoint, $endpointId);
        }
      }
    }
  }
}
