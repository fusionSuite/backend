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

namespace App\v1\Controllers\Log;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use stdClass;

final class Audit
{
  use \App\v1\Read;

  /**
   * @api {get} /v1/log/audits Get all audits logs
   * @apiName GetLogAudits
   * @apiGroup Log/Audits
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Object[]}      logs                  List of audits logs.
   * @apiSuccess {Number}        logs.id               The id of the audit log.
   * @apiSuccess {null|String}   logs.username         The name of the user has made the action.
   * @apiSuccess {String}        logs.ip               The IP address of the user has made the action.
   * @apiSuccess {String="GET","POST","PATCH","DELETE"}   logs.httpmethod    The HTTP method of the REST API request.
   * @apiSuccess {String}        logs.endpoint         The endpoint of the REST API request.
   * @apiSuccess {Number}        logs.httpcode         The HTTP code returned by the backend.
   * @apiSuccess {String}        logs.action           The action name like CONNECTION, CREATE...
   * @apiSuccess {null|String}   logs.model            The model used.
   * @apiSuccess {null|Number}   logs.item_id          The id of the item concerned (to be linked with model).
   * @apiSuccess {String}        logs.message          The message for more information.
   * @apiSuccess {ISO8601}       logs.created_at       Date of the creation.
   * @apiSuccess {null|Object}   logs.user             User do the action.
   * @apiSuccess {Number}        logs.user.id          Id of the user who did the action.
   * @apiSuccess {String}        logs.user.name        Name (login) of the user who did the action.
   * @apiSuccess {String}        logs.user.first_name  First name of the user who did the action.
   * @apiSuccess {String}        logs.user.last_name   Last name of the user who did the action.
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 2,
   *     "username": "admin",
   *     "ip": "192.168.1.82",
   *     "httpmethod": "POST",
   *     "endpoint": "/fusionsuite/backend/v1/token",
   *     "httpcode": 200,
   *     "action": "CONNECTION",
   *     "model": "User",
   *     "item_id": 2,
   *     "message": "",
   *     "created_at": "2022-10-02T06:16:42.000000Z",
   *     "user": {
   *       "id": 2,
   *       "name": "admin",
   *       "first_name": "Steve",
   *       "last_name": "Rogers"
   *     }
   *   }
   * ]
   *
   */
  public function getAll(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $paramsQuery = $request->getQueryParams();
    $pagination = $this->paramPagination($paramsQuery);

    $params = $this->manageParams($request);

    $logs = \App\v1\Models\Log\Audit::ofSort($params);
    $logs = $this->paramFilters($paramsQuery, $logs);
    $totalCnt = $logs->count();
    $logs->skip(($params['skip'] * $params['take']))->take($params['take']);
    $allLogs = $logs->get();

    $response->getBody()->write($allLogs->toJson());
    $response = $response->withAddedHeader('X-Total-Count', $totalCnt);
    $response = $response->withAddedHeader('Link', $this->createLink($request, $pagination, $totalCnt));
    $response = $response->withAddedHeader(
      'Content-Range',
      $this->createContentRange($request, $pagination, $totalCnt)
    );
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function addEntry(Request $request, $action, $message, $model, $itemId, $httpcode = 200)
  {
    $audit = new \App\v1\Models\Log\Audit();
    if (!is_null($GLOBALS['user_id']))
    {
      $audit->userid = $GLOBALS['user_id'];
      $user = \App\v1\Models\Item::query()->find($GLOBALS['user_id']);
      // Store the name in case the user account deleted later
      $audit->username = $user->name;
    }
    $audit->ip = $request->getServerParams()['REMOTE_ADDR'];
    $audit->endpoint = $request->getUri()->getPath();
    $audit->httpmethod = $request->getMethod();
    $audit->httpcode = $httpcode;
    $audit->action = $action;
    $audit->model = $model;
    $audit->item_id = $itemId;
    $audit->message = $message;
    $audit->save();
  }
}
