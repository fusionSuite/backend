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

final class Permissiondataproperty
{
  /**
   * @api {patch} /v1/config/roles/:id/permissiondata/:typeid/property/:propertyid Request update the permission
   *    of a type releated to a role
   * @apiName PatchConfigRolePermissiondataproperty
   * @apiGroup Config/Roles/Permissiondata/Permissiondataproperty
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id         Unique ID of the role.
   * @apiParam {Number}    typeid     Unique ID of the type (/config/types).
   * @apiParam {Number}    propertyid Unique ID of the type (/config/types).
   *
   * @apiBody {Boolean}            [view]         the permission to view the property of the items.
   * @apiBody {Boolean}            [update]       the permission to update the property value of the item.
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
    $permissiondataproperty = \App\v1\Models\Config\Permissiondataproperty::query()->find($args['propertyid']);
    if (is_null($permissiondataproperty))
    {
      throw new \Exception("The permissiondataproperty of this type has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'view'             => 'type:boolean',
      'update'           => 'type:boolean'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $permissions = ['view', 'update'];
    foreach ($permissions as $permission)
    {
      if (property_exists($data, $permission))
      {
        $permissiondataproperty->{$permission} = $data->{$permission};
      }
    }
    $permissiondataproperty->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function createProperties($permissiondataId, $typeId)
  {
    // get all types defined on this permissiondata and for this role
    $permissionProperties = [];
    $permissiondataproperties = \App\v1\Models\Config\Permissiondataproperty::
      where('permissiondata_id', $permissiondataId)
      ->get();
    foreach ($permissiondataproperties as $permission)
    {
      $permissionProperties[$permission->property->id] = true;
    }

    // get all types in DB
    $type = \App\v1\Models\Config\Type::query()->find($typeId);
    foreach ($type->properties()->get() as $property)
    {
      if (!isset($permissionProperties[$property->id]))
      {
        // add missing properties (default all permissions to none)
        $permissiondataproperty = new \App\v1\Models\Config\Permissiondataproperty();
        $permissiondataproperty->permissiondata_id = $permissiondataId;
        $permissiondataproperty->property_id = $property->id;
        $permissiondataproperty->save();
      }
    }
  }

  /**
   * Delete type
   * if $propertyId is null, delete all, otherwise only this property
   */
  public static function deleteProperties($datapermissionId, $propertyId = null)
  {
    $permissiondataproperty = \App\v1\Models\Config\Permissiondataproperty::
      where('permissiondata_id', $datapermissionId);
    if (!is_null($propertyId))
    {
      $permissiondataproperty->where('property_id', $propertyId);
    }
    $permissiondataproperties = $permissiondataproperty->get();
    foreach ($permissiondataproperties as $permissionitem)
    {
      $permissionitem->delete();
    }
  }

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
