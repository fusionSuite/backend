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
   * @apiParam {String} login         The username to login.
   * @apiParam {String} password      The password of the login.
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
    $data = json_decode($request->getBody());
    $GLOBALS['user_id'] = null;
    if (
        \App\v1\Post::postHasProperties($data, ['login']) === true
        && \App\v1\Post::postHasProperties($data, ['password']) === true
        && trim($data->login !== '')
        && trim($data->password !== '')
    )
    {
      // Verify the account
      $user = \App\v1\Models\Item::
          where('name', $data->login)
        ->where('type_id', TYPE_USER_ID)
        ->first();
      if (is_null($user))
      {
        throw new \Exception('Error when authentication, login or password not right', 401);
      }
      // check if user account is activated
      $hashPassword = '';
      foreach ($user->properties()->get() as $property)
      {
        if ($property->internalname == 'activated' && !$property->value)
        {
          throw new \Exception('Error when authentication, account not activated', 403);
        }
        elseif ($property->internalname == 'userpassword')
        {
          $hashPassword = $property->pivot->value_passwordhash;
        }
      }

      if ($hashPassword == '')
      {
        throw new \Exception('Error when authentication, login or password not right', 401);
      }
      if (!sodium_crypto_pwhash_str_verify($hashPassword, $data->password))
      {
        throw new \Exception('Error when authentication, login or password not right', 401);
      }
    }
    else
    {
      throw new \Exception('Missing request body, check the documentation', 400);
    }

    $responseData = $this->generateJWTToken($user);
    $GLOBALS['user_id'] = $user->id;

    \App\v1\Controllers\Log\Audit::addEntry($request, 'CONNECTION', '', 'User', $user->id);
    $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_SLASHES));
    return $response->withHeader("Content-Type", "application/json");
  }

  /**
   * @api {post} /v1/refreshtoken Request a JWT token for authentication with refreshtoken
   * @apiName PostRefreshToken
   * @apiGroup Authentication
   * @apiVersion 1.0.0-draft
   *
   * @apiParam {String} token         The JWT token.
   * @apiParam {String} refreshtoken  The token (refreshtoken) sent previously when you
   *                                  post in the endpoint /v1/token to get the token.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjcwMjkxMzksImV4cCI6MTY2NzE0OTEzOSwianRpIjoiIiwic3ViIjoiIiwic2NvcGUiOltdLCJ1c2VyX2lkIjoyLCJyb2xlX2lkIjoxLCJmaXJzdG5hbWUiOiJTdGV2ZSIsImxhc3RuYW1lIjoiUm9nZXJzIiwiYXBpdmVyc2lvbiI6InYxIiwib3JnYW5pemF0aW9uX2lkIjoxLCJzdWJfb3JnYW5pemF0aW9uIjp0cnVlfQ.E_Vrb4n-Yr_37mBUGVvTQ7rUAAYhTG_V2CZOsuI",
   *   "refreshtoken": "3laDvs4PSTJ4tYkm3PuR5q"
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
   * @apiError (Error 401) LoginError The token is invalid.
   * @apiError (Error 401) LoginErrorrefresh The authentication can't be processed because refreshtoken invalid.
   *
   * @apiErrorExample (Error 401) Error-Response:
   * HTTP/1.1 401 Unauthorized
   * {
   *   "status": "error",
   *   "message": "Error when authentication, The token is invalid"
   * }
   *
   */
  public function postRefreshToken(Request $request, Response $response, $args): Response
  {
    $data = json_decode($request->getBody());
    $GLOBALS['user_id'] = null;

    $configSecret = include(__DIR__ . '/../../../config/current/config.php');
    $secret = sodium_base642bin($configSecret['jwtsecret'], SODIUM_BASE64_VARIANT_ORIGINAL);

    $dataFormat = [
      'token'            => 'required|type:string',
      'refreshtoken'     => 'required|type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    if ($data->refreshtoken == '' || is_null($data->refreshtoken))
    {
      throw new \Exception('Error when authentication, refreshtoken not right', 401);
    }

    // we check the validity of the token (JWT)
    try {
      $decoded = JWT::decode(
        $data->token,
        $secret,
        ['HS256']
      );
    } catch (\Exception $exception) {
      // we only want have the encryption and the data OK, no need to validate the time
      if ($exception->getMessage() != 'Expired token')
      {
        throw new \Exception($exception->getMessage(), 400);
      }
    }
    // decode the paylod
    $tks = \explode('.', $data->token);
    $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($tks[1]));

    // Verify the token
    $user = \App\v1\Models\Item::find($payload->user_id);
    if (is_null($user))
    {
      throw new \Exception('Error when authentication, account not found', 401);
    }
    $refreshtokenPropId = $user->getPropertyAttribute('userrefreshtoken', 'id');
    if (is_null($refreshtokenPropId))
    {
      throw new \Exception('The database is corrupted', 500);
    }
    foreach ($user->properties()->get() as $property)
    {
      if ($property->internalname == 'activated' && !$property->value)
      {
        throw new \Exception('Error when authentication, account not activated', 403);
      }

      if ($property->id == $refreshtokenPropId && $data->refreshtoken != $property->value)
      {
        throw new \Exception('Error when authentication, refreshtoken not right', 401);
      }
    }

    $GLOBALS['user_id'] = $user->id;

    $responseData = $this->generateJWTToken($user);

    \App\v1\Controllers\Log\Audit::addEntry($request, 'CONNECTION', 'refresh token', 'User', $user->id);

    $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_SLASHES));
    return $response->withHeader("Content-Type", "application/json");
  }

  private function generateJWTToken(\App\v1\Models\Item $user)
  {
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

    // the jwtid (jit), used to revoke the JWT by server (for example when change rights, disable user...)
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
    $secret = sodium_base642bin($configSecret['jwtsecret'], SODIUM_BASE64_VARIANT_ORIGINAL);
    $token = JWT::encode($payload, $secret, "HS256");
    $responseData = [
      "token"        => $token,
      "refreshtoken" => $refreshtoken,
      "expires"      => $future->getTimeStamp()
    ];
    return $responseData;
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
