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

final class Permissionstructurecustom
{
  /**
   * @api {patch} /v1/config/roles/:id/permissionstructure/:structureid/custom/:customid Request update the permission
   *    of an id of the custom releated to a role
   * @apiName PatchConfigRolePermissionstructurecustom
   * @apiGroup Config/Roles/Permissiondata/Permissionstructurecustom
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id           Unique ID of the role.
   * @apiParam {Number}    structureid  Unique ID of the structure.
   * @apiParam {Number}    customid     Unique ID of the custom.
   *
   * @apiBody {Boolean}            [view]         the permission to view the item of the structure endpoint.
   * @apiBody {Boolean}            [update]       the permission to update the item of the structure endpoint.
   * @apiBody {Boolean}            [softdelete]   the permission to soft delete the item of the structure endpoint.
   * @apiBody {Boolean}            [delete]       the permission to delete the item of the structure endpoint.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "permission": "view"
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
    $permissionstructurecustom = \App\v1\Models\Config\Permissionstructurecustom::query()->find($args['customid']);
    if (is_null($permissionstructurecustom))
    {
      throw new \Exception("The permissionstructurecustom of this type has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'view'       => 'type:boolean',
      'update'     => 'type:boolean',
      'softdelete' => 'type:boolean',
      'delete'     => 'type:boolean'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $permissions = ['view', 'update', 'softdelete', 'delete'];
    foreach ($permissions as $permission)
    {
      if (property_exists($data, $permission))
      {
        $permissionstructurecustom->{$permission} = $data->{$permission};
      }
    }
    $permissionstructurecustom->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function createCustoms($permissionstructureId, $endpoint)
  {
    // get all types / properties defined on this permissionstructure and for this role
    $permissionCustoms = [];
    $permissionstructurecustoms = \App\v1\Models\Config\Permissionstructurecustom::
      where('permissionstructure_id', $permissionstructureId)
      ->get();
    foreach ($permissionstructurecustoms as $permission)
    {
      $permissionCustoms[$permission->endpoint_id] = true;
    }

    // get all endpointId in DB
    switch ($endpoint) {
      case 'config/type':
        $model = new \App\v1\Models\Config\Type();
          break;

      case 'config/property':
        $model = new \App\v1\Models\Config\Property();
          break;

      case 'config/role':
        $model = new \App\v1\Models\Config\Role();
          break;
    }

    foreach ($model->get() as $item)
    {
      if (!isset($permissionCustoms[$item->id]))
      {
        // add missing properties (default all permissions to none)
        $permissionstructurecustom = new \App\v1\Models\Config\Permissionstructurecustom();
        $permissionstructurecustom->permissionstructure_id = $permissionstructureId;
        $permissionstructurecustom->endpoint_id = $item->id;
        $permissionstructurecustom->save();
      }
    }
  }

  /**
   *
   */
  public static function deleteCustoms($permissionstructureId, $endpoint, $endpointId = null)
  {
    $permissionstructurecustom = \App\v1\Models\Config\Permissionstructurecustom::
      where('permissionstructure_id', $permissionstructureId);
    if (!is_null($endpointId))
    {
      $permissionstructurecustom->where('endpoint_id', $endpointId);
    }
    $items = $permissionstructurecustom->get();
    foreach($items as $item)
    {
      $item->delete();
    }
  }

  /**
   * Get list of propertiesID allowed to view.
   * return to null if all allowed
   * return [] if none
   */
  public static function getPropertiesCanView($typeId)
  {
    // if grant all types/items or not have customproperties, return null
    if ($GLOBALS['permissions']->data == 'grant')
    {
      return null;
    }
    if (count($GLOBALS['permissions']->custom['data'][$typeId]['properties']) == 0)
    {
      return null;
    }
    // else, manage properties to return
    $propertiesIds = [];
    foreach ($GLOBALS['permissions']->custom['data'][$typeId]['properties'] as $propId => $permissions)
    {
      if ($permissions['view'])
      {
        $propertiesIds[] = $propId;
      }
    }
    return $propertiesIds;
  }
}
