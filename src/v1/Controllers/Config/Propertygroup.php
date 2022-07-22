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

final class Propertygroup
{
  /**
   * @api {post} /v1/config/types/:id/propertygroups Create a propertygroup
   * @apiName GetConfigPropertygroups
   * @apiGroup Config/Propertygroups
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}      id            The id of the type.
   *
   * @apiBody {String}      name          The name of the type of items.
   * @apiBody {Integer}     [position]    The TODO.
   * @apiBody {Integer[]}   properties    The regexformat to verify the value is conform (works only with valuetype
   *    is string or list).
   *
   */
  public function postItem(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $data = json_decode($request->getBody());

    // Validate the data format
    $dataFormat = [
      'name'       => 'required|type:string',
      'properties' => 'required|type:string|array'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $propertygroup = new \App\v1\Models\Config\Propertygroup();
    $propertygroup->name = $data->name;
    if (\App\v1\Post::postHasProperties($data, ['position']) === true)
    {
      $propertygroup->position = $data->position;
    }
    $propertygroup->properties = json_encode($data->properties);
    $propertygroup->type_id = $args['id'];
    $propertygroup->save();

    $response->getBody()->write(json_encode(["id" => intval($propertygroup->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }



  // TODO
  public function patchItem(Request $request, Response $response, $args): Response
  {
    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function createPropertygroup($data, $typeId)
  {
    if (\App\v1\Post::postHasProperties($data, ['position']) === false)
    {
      $data->position = 0;
    }

    $propertygroup = \App\v1\Models\Config\Propertygroup::firstOrCreate(
      [
        'name'    => $data->name,
        'type_id' => $typeId
      ],
      [
        'position'   => $data->position,
        'properties' => json_encode($data->properties)
      ]
    );

    return $propertygroup->id;
  }

  /********************
   * Private functions
   ********************/
}
