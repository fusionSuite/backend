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

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

// use DateTime;

include('../src/constant.php');
require __DIR__ . '/../vendor/autoload.php';
// Autoload in ActionScripts folder
require __DIR__ . '/../ActionScripts/autoload.php';

$config = include('../src/config.php');

$app = AppFactory::create();

$app->addRoutingMiddleware();

// See https://github.com/tuupola/slim-jwt-auth
$container = $app->getContainer();

$container["jwt"] = function ($container)
{
  return new StdClass();
};

$prefix = "";
if (strstr($_SERVER['REQUEST_URI'], 'index.php'))
{
  $uri_spl = explode('index.php', $_SERVER['REQUEST_URI']);
  $prefix = $uri_spl[0] . "index.php";
}
if (strstr($_SERVER['REQUEST_URI'], '/v1/'))
{
  $uri_spl = explode('/v1/', $_SERVER['REQUEST_URI']);
  $prefix = $uri_spl[0];
}
if (strstr($_SERVER['REQUEST_URI'], '/ping'))
{
  $uri_spl = explode('/ping', $_SERVER['REQUEST_URI']);
  $prefix = $uri_spl[0];
}

$configSecret = include(__DIR__ . '/../config/current/config.php');
// $secret = "123456789helo_secret";
$secret = $configSecret['jwtsecret'];

$app->add(new Tuupola\Middleware\JwtAuthentication([
  "ignore" => [
    $prefix . "/v1/token",
    $prefix . "/v1/refreshtoken",
    $prefix . "/ping",
    $prefix . "/v1/fusioninventory",
    $prefix . "/v1/status"
  ],
  "secure" => false,
  "secret" => $secret,
  // "callback" => function ($request, $response, $arguments) use ($container) { ???
  "before" => function ($request, $arguments)
  {
    $myUser = \App\v1\Models\Item::find($arguments['decoded']['user_id']);
    $jwtid = $myUser->getPropertyAttribute('userjwtid');
    if (is_null($jwtid) || $jwtid != $arguments['decoded']['jti'])
    {
      throw new Exception('jti changed, ask for a new token ' . $myUser['jwtid'] . ' != ' .
                          $arguments['decoded']['jti'], 401);
    }
    $GLOBALS['user_id'] = $arguments['decoded']['user_id'];
    // Load permissions
    $GLOBALS['permissions'] = \App\v1\Controllers\Config\Role::generatePermission(
      $arguments['decoded']['role_id']
    );
  },
  "error" => function ($response, $arguments)
  {
    $GLOBALS['user_id'] = null;
    throw new Exception($arguments["message"], 401);
  }
]));

$capsule = new Capsule();
$capsule->addConnection($config['db']);
$capsule->setEventDispatcher(new Dispatcher(new Container()));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Define Custom Error Handler
$customErrorHandler = function (
  Request $request,
  Throwable $exception,
  bool $displayErrorDetails,
  bool $logErrors,
  bool $logErrorDetails
) use ($app)
{
  if ($exception->getCode() == 23000 || $exception->getCode() == 23505)
  {
    $error = [
      "status"  => "error",
      "message" => "The element already exists"
    ];
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(json_encode($error));
    return $response->withStatus(409)->withHeader('Content-Type', 'application/json');
  }
  elseif (is_int($exception->getCode()) && $exception->getCode() > 0)
  {
    if (strstr($request->getUri()->getPath(), 'refreshtoken'))
    {
      \App\v1\Controllers\Log\Audit::addEntry(
        $request,
        'CONNECTION',
        'fail on refreshtoken',
        'User',
        null,
        $exception->getCode()
      );
    } elseif (strstr($request->getUri()->getPath(), 'token'))
    {
      $data = json_decode($request->getBody());
      \App\v1\Controllers\Log\Audit::addEntry(
        $request,
        'CONNECTION',
        'fail, login: ' . $data->login,
        'User',
        null,
        $exception->getCode()
      );
    } else {
      \App\v1\Controllers\Log\Audit::addEntry(
        $request,
        '',
        $exception->getMessage(),
        null,
        null,
        $exception->getCode()
      );
    }

    $error = [
      "status"  => "error",
      "message" => $exception->getMessage()
    ];
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(json_encode($error));
    return $response->withStatus($exception->getCode())->withHeader('Content-Type', 'application/json');
  }
  // else error 500
  $error = [
    "status"  => "error",
    "message" => $exception->getMessage()
  ];
  $response = $app->getResponseFactory()->createResponse();
  $response->getBody()->write(json_encode($error));
  return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
};

$app->get('/', function (Request $request, Response $response, $args)
{
  $response->getBody()->write("wazaa");
  return $response;
});

// Define routes
\App\Route::setRoutes($app, $prefix);

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();
