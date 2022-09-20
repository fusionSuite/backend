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

    if (\App\v1\Post::postHasProperties($data, ['refresh_token']) === true)
    {
      if ($data->refresh_token == '' || is_null($data->refresh_token))
      {
        throw new \Exception('Error when authentication, refresh_token not right', 401);
      }

      // Verify the token
      // TODO
      // $user = \App\v1\Models\User::where([['refreshtoken', $data->refresh_token]])->get()->toArray();
      // if (count($user) == 0)
      // {
      //   throw new \Exception('Error when authentication, refresh_token not right', 401);
      // }
    }
    elseif (
        \App\v1\Post::postHasProperties($data, ['login']) === true
        && \App\v1\Post::postHasProperties($data, ['password']) === true
        && trim($data->login !== '')
        && trim($data->password !== '')
    )
    {
      // Verify the account
      $user = \App\v1\Models\Item::where('name', $data->login)->where('type_id', TYPE_USER_ID)->first();
      if (is_null($user))
      {
        throw new \Exception('Error when authentication, login or password not right', 401);
      }
      // check if user account is activated
      foreach ($user->properties()->get() as $property)
      {
        if ($property->internalname == 'activated' && !$property->value)
        {
          throw new \Exception('Error when authentication, account not activated', 403);
        }
      }
      // TODO check password in property (need manage properties with password)

      // $user = \App\v1\Models\User::where([['login', $data->login]])->get()->makeHidden(
      //   \App\v1\Common::getFieldsToHide($model->getVisible(), ['id', 'login', 'password',
      //   'jwtid', 'refreshtoken', 'firstname', 'lastname', 'displayname']))
      //   ->makeVisible(["password", "jwtid"])->toArray();
      // if (count($user) == 0)
      // {
      //   throw new \Exception('Error when authentication, login or password not right', 401);
      // }
      // else if (password_verify($data->password, $user[0]['password']) === false)
      // {
      //   throw new \Exception('Error when authentication, login or password not right', 401);
      // }
    }
    else
    {
        throw new \Exception('Missing request body, check the documentation', 400);
    }

    $firstName = $user->getPropertyAttribute('userfirstname');
    $lastName = $user->getPropertyAttribute('userlastname');
    $jwtid = $user->getPropertyAttribute('userjwtid');
    $jwtidId = $user->getPropertyAttribute('userjwtid', 'id');
    $refreshtokenPropId = $user->getPropertyAttribute('userrefreshtoken', 'id');
    if (is_null($jwtidId) || is_null($refreshtokenPropId))
    {
      throw new \Exception('The database is corrupted', 500);
    }

    // Generate a new refreshtoken and save in DB
    $refreshtoken = $this->generateToken();
    $user->properties()->updateExistingPivot($refreshtokenPropId, ['value_string' => $refreshtoken]);

    // the jwtid (jit), used to revoke the jwt by server (for example when change rights, disable user...)
    if (is_null($jwtid)) {
      $jti = $this->generateToken();
      $user->properties()->updateExistingPivot($jwtidId, ['value_string' => $jti]);
    } else {
      $jti = $jwtid;
    }

    $now = new DateTime();
    $future = new DateTime("+20 minutes");
    // For test / DEBUG
    // $future = new DateTime("+30 seconds");
    // Get roles
    $role = $user->roles()->first();

    if (is_null($role))
    {
      throw new \Exception('No role assigned to the user', 401);
    }

    $payload = [
      'iat'              => $now->getTimeStamp(),
      'exp'              => $future->getTimeStamp(),
      'jti'              => $jti,
      'sub'              => '',
      'scope'            => $this->getScope($user->id),
      'user_id'          => $user->id,
      'role_id'          => $role->id,
      'firstname'        => $firstName,
      'lastname'         => $lastName,
      'apiversion'       => "v1",
      'organization_id'  => $user->organization_id,
      'sub_organization' => true
    ];
    $configSecret = include(__DIR__ . '/../../../config/current/config.php');
    $secret = $configSecret['jwtsecret'];
    $token = JWT::encode($payload, $secret, "HS256");
    $responseData = [
      "token"        => $token,
      "refreshtoken" => $refreshtoken,
      "expires"      => $future->getTimeStamp()
    ];
    $GLOBALS['user_id'] = $user->id;
    \App\v1\Controllers\Log\Audit::addEntry($request, 'CONNECTION', '', 'User', $user->id);

    $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_SLASHES));
    return $response->withHeader("Content-Type", "application/json");
  }

  // get rights of this user.
  private function getScope($userId)
  {
    $scope = [
    ];

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
