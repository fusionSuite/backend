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

namespace App\v1;

/**
 * @apiDefine AutorizationHeader
 * @apiError (Error 401) AutorizationFailure The JWT token is not valid.
 *
 * @apiHeader {String} Authorization The JWT token.
 * @apiHeaderExample {Header} Header-Example
 *     "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs"
 *
 * @apiError (Error 401) AutorizationFailure The JWT token is not valid.
 *
 * @apiErrorExample {json} (Error 401) Error-Response:
 *     HTTP/1.1 401 Not Found
 *     {
 *         "status": "error",
 *         "message": "Signature verification of the token failed"
 *     }
 */


class Permission
{
  /**
   *
   * $permission:    view,create,update,softdelete,delete
   * $structureType: config/type, config/property, config/role
   */
  public static function checkPermissionToStructure($permission, $endpoint, $structureId = null)
  {
    if ($GLOBALS['permissions']->structure == 'grant')
    {
      return true;
    }
    elseif ($GLOBALS['permissions']->structure == 'none')
    {
      throw new \Exception("No permission on this " . $endpoint, 401);
    }
    // Case custom (first level)
    if ($GLOBALS['permissions']->custom['structure'][$endpoint][$permission] == 'grant')
    {
      return true;
    }
    elseif ($GLOBALS['permissions']->custom['structure'][$endpoint][$permission] == 'none')
    {
      throw new \Exception("No permission on this " . $endpoint, 401);
    }

    // Cases custom (second level)

    //   case not have the id (used by permission on getAll functions)
    if (
        is_null($structureId)
    )
    {
      foreach ($GLOBALS['permissions']->custom['structure'][$endpoint]['customs'] as $perms)
      {
        if ($perms[$permission])
        {
          // one custom is allowed, so return allowed
          return true;
        }
      }
    }

    // case of have the id
    if (
        !is_null($structureId)
        && isset($GLOBALS['permissions']->custom['structure'][$endpoint]['customs'][$structureId])
        && $GLOBALS['permissions']->custom['structure'][$endpoint]['customs'][$structureId][$permission]
    )
    {
      return true;
    }
    throw new \Exception("No permission on this " . $endpoint, 401);
  }

  /**
   *
   * $permission:    view,create,update,softdelete,delete
   * $type_id:       the type_id of the item
   */
  public static function checkPermissionToData($permission, $type_id, $property_id = null)
  {
    if ($GLOBALS['permissions']->data == 'grant')
    {
      return true;
    }
    elseif ($GLOBALS['permissions']->data == 'none')
    {
      throw new \Exception("No permission on this item", 401);
    }
    // manage custom types
    if (
        !is_null($property_id)
        && count($GLOBALS['permissions']->custom['data'][$type_id]['properties']) > 0
    )
    {
      // custom properties permissions
      if (
          isset($GLOBALS['permissions']->custom['data'][$type_id]['properties'][$property_id])
          && $GLOBALS['permissions']->custom['data'][$type_id]['properties'][$property_id][$permission]
      )
      {
        return true;
      }
    }
    elseif ($GLOBALS['permissions']->custom['data'][$type_id][$permission])
    {
      // type permissions
      return true;
    }
    throw new \Exception("No permission on this item", 401);
  }

  /**
   * return:
   *   - null if grant all
   *   - [4,5] if custom
   */
  public static function getStructureViewIds($endpoint)
  {
    if ($GLOBALS['permissions']->structure == 'grant')
    {
      return null;
    }
    elseif ($GLOBALS['permissions']->structure == 'none')
    {
      throw new \Exception("No permission on this " . $endpoint, 403);
    }
    if ($GLOBALS['permissions']->custom['structure'][$endpoint]['view'] == 'grant')
    {
      return null;
    }
    $ids = [];
    foreach ($GLOBALS['permissions']->custom['structure'][$endpoint]['customs'] as $id => $permission)
    {
      if ($permission['view'])
      {
        $ids[] = $id;
      }
    }
    return $ids;
  }
}
