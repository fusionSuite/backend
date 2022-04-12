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

namespace App\v1\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class Ping
{
  /**
   * @api {get} /ping Check if backend answer
   * @apiName GetPing
   * @apiGroup Ping
   * @apiVersion 1.0.0-draft
   *
   * @apiSuccessExample {text} Success-Response:
   * HTTP/1.1 200 OK
   * pong
   *
   */
  public function getPing(Request $request, Response $response, $args): Response
  {
    $response->getBody()->write('pong');
    return $response;
  }
}
