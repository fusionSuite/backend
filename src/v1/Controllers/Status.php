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
namespace App\v1\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as DB;

final class Status
{

  /**
   * @api {get} /v1/status GET - Get the status of the elements of the backend
   * @apiName GetStatus
   * @apiGroup Status
   * @apiVersion 1.0.0
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Object[]}  connections            The status of the connections.
   * @apiSuccess {Boolean}   connections.database   true if the connection to the database is OK
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "connections":
   *   {
   *     "database": true
   *   }
   * }
   *
   */
  public function getStatus(Request $request, Response $response, $args): Response
  {
    $status = [
      "connections" => [
        "database" => true
      ]
    ];
    try {
      DB::connection()->getPdo();
    } catch (\Exception $e) {
      $status['connections']['database'] = false;
    }
    $response->getBody()->write(json_encode($status));
    return $response->withHeader('Content-Type', 'application/json');
  }
}
