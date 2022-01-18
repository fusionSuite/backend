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
      'name' => 'required'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    // TODO manage modeling

    $type = new \App\v1\Models\Config\Type;
    $type->name = $data->name;
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
      'name' => 'required'
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

}
