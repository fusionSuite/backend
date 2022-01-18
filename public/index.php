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
use DI\NotFoundException;
// use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteContext;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
// use DateTime;

require __DIR__ . '/../vendor/autoload.php';

$config = include('../src/config.php');

$app = AppFactory::create();

$app->addRoutingMiddleware();

// See https://github.com/tuupola/slim-jwt-auth
$container = $app->getContainer();

$container["jwt"] = function ($container) {
    return new StdClass;
};

$prefix = "";
if (strstr($_SERVER['REQUEST_URI'], 'index.php')) {
   $uri_spl = explode('index.php', $_SERVER['REQUEST_URI']);
   $prefix = $uri_spl[0]."index.php";
}
if (strstr($_SERVER['REQUEST_URI'], '/v1/')) {
   $uri_spl = explode('/v1/', $_SERVER['REQUEST_URI']);
   $prefix = $uri_spl[0];
}
if (strstr($_SERVER['REQUEST_URI'], '/ping')) {
   $uri_spl = explode('/ping', $_SERVER['REQUEST_URI']);
   $prefix = $uri_spl[0];
}

$app->add(new Tuupola\Middleware\JwtAuthentication([
   "ignore" => [$prefix."/v1/token", $prefix."/ping", $prefix."/v1/fusioninventory", $prefix."/v1/status"],
   "secure" => false,
   "secret" => "123456789helo_secret",
   // "callback" => function ($request, $response, $arguments) use ($container) { ???
   "before" => function ($request, $arguments) {
      $myUser = \App\v1\Models\User::find($arguments['decoded']['user_id']);
      if ($myUser['jwtid'] != $arguments['decoded']['jti']) {
         throw new Exception("jti changed, ask for a new token ".$myUser['jwtid'].' != '.$arguments['decoded']['jti'], 401);
      }
   },
   "error" => function ($response, $arguments) {
      $data["status"] = "error";
      $data["message"] = $arguments["message"];
      return $response
         ->withHeader("Content-Type", "application/json")
         ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES));
   }    
]));


$capsule = new Capsule;
$capsule->addConnection($config['db']);
$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Define Custom Error Handler
$customErrorHandler = function (
    Request $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
   if ($exception->getCode() == 23000)
   {
      $error = [
         "status"  => "error",
         "message" => "The element already exists"
      ];
      $response = $app->getResponseFactory()->createResponse();
      $response->getBody()->write(json_encode($error));
      return $response->withStatus(409)->withHeader('Content-Type', 'application/json');
   }
   else if ($exception->getCode() > 0)
   {
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

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("wazaa");
    return $response;
});

// Define routes
\App\v1\Route::setRoutes($app, $prefix);

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();
