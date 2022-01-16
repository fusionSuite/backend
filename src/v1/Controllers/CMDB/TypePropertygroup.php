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

final class TypePropertygroup
{

  /**
   * @api {post} /v1/cmdb/types/:id/propertygroups Create a typepropertygroup
   * @apiName GetCMDBTypePropertygroups
   * @apiGroup CMDBTypepropertygroups
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *     
   * @apiSuccess {String}      name          The name of the type of items.
   * @apiSuccess {Integer}     [position]    The TODO.
   * @apiSuccess {Interger[]}  properties   The regexformat to verify the value is conform (works only with valuetype is string or list).
   * 
   */
  public function postItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());
    $keys = ['name', 'properties'];
    if (\App\v1\Post::PostHasProperties($data, $keys) === false)
    {
      throw new \Exception('Post data not conform (missing fields), check the documentation', 400);
    }

    if (\App\v1\Common::checkValueRight($data->name, "string") === false)
    {
      throw new \Exception("Post data not conform (value not allowed in field 'name'), check the documentation", 400);
    }

    $propertygroup = new \App\v1\Models\CMDB\Propertygroup;
    $propertygroup->name = $data->name;
    if (\App\v1\Post::PostHasProperties($data, ['position']) === true)
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

}
