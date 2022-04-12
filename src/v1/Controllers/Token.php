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
use DateTime;
use Firebase\JWT\JWT;
use Tuupola\Base62;

final class Token
{
  /**
   * @api {post} /v1/token Request a JWT token for authentication
   * @apiName PostToken
   * @apiGroup Authentication
   * @apiVersion 1.0.0-draft
   *
   * @apiParam (Request body - login)   {String} login         The username to login.
   * @apiParam (Request body - login)   {String} password      The password of the login.
   * @apiParam (Request body - refresh) {String} refreshtoken  The token (refresh_token) sent previously when you
   *                                                           post in this endpoint to get the token.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "login": "david",
   *   "password": "xxxxxx"
   * }
   *
   * @apiSuccess {String}  token         The token string.
   * @apiSuccess {String}  refreshtoken  The token string can be used to refresh / regenerate a new token when this
   *                                     token expire.
   * @apiSuccess {Integer} expires       The expiration timestamp.
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODMxMzQ0NzIsImV4cCI6MTU4MzIyMDg3MiwianRpIjoiNE5odXg0RWY5WmdEVk9FVXRDNFg2ViIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdfQ.m4qf3e9M3Nwrl5A3wCrZ2l84HO1wB3d4oJr_1ZekYVk",
   *   "refreshtoken": "zE6vnZIyeWubw1X1toEbZ2yErdK9f5oYbcuFxzSf",
   *   "expires": 1583220872
   * }
   *
   * @apiError (Error 400) DataNotConform The data sent are not conform.
   *
   * @apiErrorExample (Error 400) Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status": "error",
   *   "message": "Missing request body, check the documentation"
   * }
   *
   * @apiError (Error 401) LoginError The authentication can't be processed because login or password invalid.
   * @apiError (Error 401) LoginErrorrefresh The authentication can't be processed because refresh_token invalid.
   *
   * @apiErrorExample (Error 401) Error-Response:
   * HTTP/1.1 401 Unauthorized
   * {
   *   "status": "error",
   *   "message": "Error when authentication, login or password not right"
   * }
   *
   */
  public function postToken(Request $request, Response $response, $args): Response
  {
    /* Here generate and return JWT to the client. */
    $data = json_decode($request->getBody());
    $model = new \App\v1\Models\User();

    if (\App\v1\Post::postHasProperties($data, ['refresh_token']) === true)
    {
      if ($data->refresh_token == '' || is_null($data->refresh_token))
      {
        throw new \Exception('Error when authentication, refresh_token not right', 401);
      }

      // Verify the token
      $user = \App\v1\Models\User::where([['refreshtoken', $data->refresh_token]])->get()->toArray();
      if (count($user) == 0)
      {
        throw new \Exception('Error when authentication, refresh_token not right', 401);
      }
    }
    elseif (
        \App\v1\Post::postHasProperties($data, ['login']) === true
        && \App\v1\Post::postHasProperties($data, ['password']) === true
        && trim($data->login !== '')
        && trim($data->password !== '')
    )
    {
      // Verify the account
      $user = \App\v1\Models\User::where(
        [
          ['login', $data->login]
        ]
      )->get()
      ->makeHidden(
        \App\v1\Common::getFieldsToHide(
          $model->getVisible(),
          ['id', 'login', 'password', 'jwtid', 'refreshtoken', 'firstname', 'lastname', 'displayname']
        )
      )->makeVisible(["password", "jwtid"])->toArray();

      if (count($user) == 0)
      {
        throw new \Exception('Error when authentication, login or password not right', 401);
      }
      elseif (password_verify($data->password, $user[0]['password']) === false)
      {
        throw new \Exception('Error when authentication, login or password not right', 401);
      }
    }
    else {
        throw new \Exception('Missing request body, check the documentation', 400);
    }

    // Generate a new refreshtoken and save in DB
    $refreshtoken = $this->generateToken();
    $myUser = \App\v1\Models\User::find($user[0]['id']);
    $myUser->refreshtoken = $refreshtoken;
    $myUser->save();

    // the jwtid (jit), used to revoke the jwt by server (for example when change rights, disable user...)
    if (!isset($user[0]) || !isset($user[0]['refreshtoken']) || $user[0]['refreshtoken'] == '')
    {
      $jti = $this->generateToken();
      $myUser = \App\v1\Models\User::find($user[0]['id']);
      $myUser->jwtid = $jti;
      $myUser->save();
    } else {
      $jti = $user[0]['refreshtoken'];
    }

    $now = new DateTime();
    $future = new DateTime("+20 minutes");
    // For test / DEBUG
    // $future = new DateTime("+30 seconds");

    $payload = [
      "iat" => $now->getTimeStamp(),
      "exp" => $future->getTimeStamp(),
      "jti" => $jti,
      "sub" => '',
      "scope" => $this->getScope($user[0]['id']),
      "user_id" => $user[0]['id'],
      "firstname" => $user[0]['firstname'],
      "lastname" => $user[0]['lastname'],
      "displayname" => $user[0]['displayname'],
      "apiversion" => "v1"
    ];
    $configSecret = include(__DIR__ . '/../../../config/current/config.php');
    // $secret = "123456789helo_secret";
    $secret = $configSecret['jwtsecret'];
    $token = JWT::encode($payload, $secret, "HS256");
    $responseData = [
      "token"        => $token,
      "refreshtoken" => $refreshtoken,
      "expires"      => $future->getTimeStamp()
    ];
    $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_SLASHES));
    return $response->withHeader("Content-Type", "application/json");
  }

  // get rights of this user.
  private function getScope($userId)
  {
    $scope = [
      'tickets' => [
        'readAll'    => false,
        'readOwn'    => false,
        'create'     => false,
        'update'     => false,
        'softDelete' => false,
        'delete'     => false
      ],
      'tickets/followups' => [
        'readPublic'  => false,
        'readPrivate' => false,
        'create'      => false,
        'update'      => false,
        'softDelete'  => false,
        'delete'      => false,
      ],
      'servicecatalogs' => [
        'read'         => false,
        'create'       => false,
        'update'       => false,
        'softDelete'   => false,
        'delete'       => false,
        'createAnswer' => false
      ],
      'users' => [
        'read'         => false,
        'create'       => false,
        'update'       => false,
        'softDelete'   => false,
        'delete'       => false,
      ],
      'groups' => [
        'read'         => false,
        'create'       => false,
        'update'       => false,
        'softDelete'   => false,
        'delete'       => false,
      ],
      'news' => [
        'read'         => false,
        'create'       => false,
        'update'       => false,
        'softDelete'   => false,
        'delete'       => false,
      ],
      'knowledges' => [
        'read'         => false,
        'readFAQ'      => false,
        'create'       => false,
        'update'       => false,
        'softDelete'   => false,
        'delete'       => false,
      ]
    ];

    // // get default profile of the user
    // // Get rights of this profile
    // $user = new \User();
    // $profile = new \Profile();
    // $user->getFromDB($userId);
    // $profile->getFromDB($user->fields['profiles_id']);

    // // ***** tickets ***** //
    // if (intval($profile->fields['ticket']) & \Ticket::READALL) {
    //   $scope['tickets']['readAll'] = true;
    // }
    // if (intval($profile->fields['ticket']) & \Ticket::READMY) {
    //   $scope['tickets']['readOwn'] = true;
    // }
    // if (intval($profile->fields['ticket']) & CREATE) {
    //   $scope['tickets']['create'] = true;
    // }
    // if (intval($profile->fields['ticket']) & UPDATE) {
    //   $scope['tickets']['update'] = true;
    // }
    // if (intval($profile->fields['ticket']) & DELETE) {
    //   $scope['tickets']['softDelete'] = true;
    // }
    // if (intval($profile->fields['ticket']) & PURGE) {
    //   $scope['tickets']['delete'] = true;
    // }

    // if (intval($profile->fields['followup']) & \ITILFollowup::SEEPUBLIC) {
    //   $scope['tickets/followups']['readPublic'] = true;
    // }
    // if (intval($profile->fields['followup']) & \ITILFollowup::SEEPRIVATE) {
    //   $scope['tickets/followups']['readPrivate'] = true;
    // }
    // if (intval($profile->fields['followup']) & CREATE) {
    //   $scope['tickets/followups']['create'] = true;
    // }
    // if (intval($profile->fields['followup']) & UPDATE) {
    //   $scope['tickets/followups']['update'] = true;
    // }
    // if (intval($profile->fields['followup']) & DELETE) {
    //   $scope['tickets/followups']['softDelete'] = true;
    // }
    // if (intval($profile->fields['followup']) & PURGE) {
    //   $scope['tickets/followups']['delete'] = true;
    // }

    // // ***** servicecatalogs ***** //
    // if (intval($profile->fields['ticket']) & CREATE) {
    //   $scope['servicecatalogs']['read'] = true;
    // }
    // if (intval($profile->fields['entity']) & CREATE) {
    //   $scope['servicecatalogs']['create'] = true;
    // }
    // if (intval($profile->fields['entity']) & UPDATE) {
    //   $scope['servicecatalogs']['update'] = true;
    // }
    // if (intval($profile->fields['entity']) & DELETE) {
    //   $scope['servicecatalogs']['softDelete'] = true;
    // }
    // if (intval($profile->fields['entity']) & PURGE) {
    //   $scope['servicecatalogs']['delete'] = true;
    // }
    // // Allow for all
    // $scope['servicecatalogs']['createAnswer'] = true;

    // // ***** users ***** //
    // if (intval($profile->fields['user']) & READ) {
    //   $scope['users']['read'] = true;
    // }
    // if (intval($profile->fields['user']) & CREATE) {
    //   $scope['users']['create'] = true;
    // }
    // if (intval($profile->fields['user']) & UPDATE) {
    //   $scope['users']['update'] = true;
    // }
    // if (intval($profile->fields['user']) & DELETE) {
    //   $scope['users']['softDelete'] = true;
    // }
    // if (intval($profile->fields['user']) & PURGE) {
    //   $scope['users']['delete'] = true;
    // }

    // // ***** groups ***** //
    // if (intval($profile->fields['group']) & READ) {
    //   $scope['groups']['read'] = true;
    // }
    // if (intval($profile->fields['group']) & CREATE) {
    //   $scope['groups']['create'] = true;
    // }
    // if (intval($profile->fields['group']) & UPDATE) {
    //   $scope['groups']['update'] = true;
    // }
    // if (intval($profile->fields['group']) & DELETE) {
    //   $scope['groups']['softDelete'] = true;
    // }
    // if (intval($profile->fields['group']) & PURGE) {
    //   $scope['groups']['delete'] = true;
    // }

    // // ***** news ***** //
    // // Allow for all
    // $scope['news']['read'] = true;
    // if (intval($profile->fields['entity']) & CREATE) {
    //   $scope['news']['create'] = true;
    // }
    // if (intval($profile->fields['entity']) & UPDATE) {
    //   $scope['news']['update'] = true;
    // }
    // if (intval($profile->fields['entity']) & DELETE) {
    //   $scope['news']['softDelete'] = true;
    // }
    // if (intval($profile->fields['entity']) & PURGE) {
    //   $scope['news']['delete'] = true;
    // }

    // // ***** knowledges ***** //
    // if (intval($profile->fields['knowbase']) & READ) {
    //   $scope['knowledges']['read'] = true;
    // }
    // if (intval($profile->fields['knowbase']) & \KnowbaseItem::READFAQ) {
    //   $scope['knowledges']['readFAQ'] = true;
    // }
    // if (intval($profile->fields['knowbase']) & CREATE) {
    //   $scope['knowledges']['create'] = true;
    // }
    // if (intval($profile->fields['knowbase']) & UPDATE) {
    //   $scope['knowledges']['update'] = true;
    // }
    // if (intval($profile->fields['knowbase']) & DELETE) {
    //   $scope['knowledges']['softDelete'] = true;
    // }
    // if (intval($profile->fields['knowbase']) & PURGE) {
    //   $scope['knowledges']['delete'] = true;
    // }

    return $scope;
  }

  /********************
   * Private functions
   ********************/

  private function generateToken()
  {
     return (new Base62())->encode(random_bytes(16));
  }
}
