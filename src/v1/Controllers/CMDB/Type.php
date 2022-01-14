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
namespace App\v1\Controllers\CMDB;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class Type
{

  /**
   * @api {get} /v1/cmdb/types GET - Get all types of items in the CMDB
   * @apiName GetCMDBTypes
   * @apiGroup CMDBTypes
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Integer}  id      The id of the item.
   * @apiSuccess {String}   name    The name of the item.
   * @apiSuccess {String}       created_at                    Date of the item creation.
   * @apiSuccess {String|null}  updated_at                    Date of the last item modification.
   *
   * @apiSuccessExample Success-Response:
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
    $items = \App\v1\Models\CMDB\Type::all();
    $response->getBody()->write($items->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {get} /v1/cmdb/types/:id GET - Get one type
   * @apiName GetCMDBType
   * @apiGroup CMDBTypes
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} id Rule unique ID.
   *
   * @apiSuccess {String}       name                  The name of the item.
   * @apiSuccess {String}       created_at            Date of the item creation.
   * @apiSuccess {String|null}  updated_at            Date of the last item modification.
   * @apiSuccess {Object[]}     properties              The properties list.
   * @apiSuccess {Integer}      properties.id           The property id.
   * 
   *
   * @apiSuccessExample Success-Response:
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
   * @apiUse AutorizationError
   *
   */
  public function getOne(Request $request, Response $response, $args): Response
  {
    $item = \App\v1\Models\CMDB\Type::with('properties.listvalues')->find($args['id']);
    if (is_null($item))
    {
      throw new \Exception("This type has not be found", 404);
    }
    $response->getBody()->write($item->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }


  /**
   * @api {post} /v1/cmdb/type POST - Create a new type of items
   * @apiName PostCMDBTypes
   * @apiGroup CMDBTypes
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {String}  name     The name of the type of items.
   */
  public function postItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());
    if (\App\v1\Post::PostHasProperties($data, ['name']) === false)
    {
      throw new \Exception('Post data not conform (missing fields), check the documentation', 400);
    }

    if (\App\v1\Common::checkValueRight($data->name, "string") === false)
    {
      throw new \Exception("Post data not conform (value not allowed in field 'name'), check the documentation", 400);
    }

    // TODO manage modeling

    $type = new \App\v1\Models\CMDB\Type;
    $type->name = $data->name;
    $type->save();

    $response->getBody()->write(json_encode(["id" => intval($type->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {patch} /v1/cmdb/type/:id PATCH - Update an existing type of items
   * @apiName PatchCMDBTypes
   * @apiGroup CMDBTypes
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the type.
   *
   * @apiSuccess {String}  name      Name of the type.
   */
  public function patchItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());
    $type = \App\v1\Models\CMDB\Type::find($args['id']);

    if (is_null($type))
    {
      throw new \Exception("The type has not be found", 404);
    }

    if (\App\v1\Post::PostHasProperties($data, ['name']) === false)
    {
      throw new \Exception('Patch data not conform (missing field name), check the documentation', 400);
    }

    $type->name = $data->name;
    $type->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/cmdb/type/:id/property/:propertyid POST - Associate a property of this type
   * @apiName PostCMDBTypesProperty
   * @apiGroup CMDBTypes
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *
   */
  public function postProperty(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $type = \App\v1\Models\CMDB\Type::find($args['id']);
    if (is_null($type))
    {
      throw new \Exception("The type has not be found", 404);
    }

    $property = \App\v1\Models\CMDB\Property::find($args['propertyid']);
    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }

    // TODO check if relation exists

    $type->properties()->attach($args['propertyid']);

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

}
