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
namespace App\v1;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

final class Route
{
  static function setRoutes(&$app, $prefix)
  {

    // Enable OPTIONS method for all routes
    $app->options($prefix.'/{routes:.+}', function ($request, $response, $args)
    {
      return $response;
    });

    // The ping - pong ;)
    $app->get($prefix.'/ping', \App\v1\Controllers\Ping::class . ':getPing');

    $app->group($prefix.'/v1', function (RouteCollectorProxy $v1)
    {
      $v1->post("/token", \App\v1\Controllers\Token::class . ':postToken');
      $v1->get("/status", \App\v1\Controllers\Status::class . ':getStatus');

      $v1->group('/fusioninventory', function (RouteCollectorProxy $fusion)
      {
        $fusion->map(['POST'], '/register', \App\v1\Controllers\Fusioninventory::class . ':postRegister');
        $fusion->map(['GET'], '/configuration', \App\v1\Controllers\Fusioninventory::class . ':getConfig');

        $fusion->map(['GET'], '/localinventory', \App\v1\Controllers\Fusioninventory::class . ':getLocalinventoryConfig');
        $fusion->map(['POST'], '/localinventory', \App\v1\Controllers\Fusioninventory::class . ':postLocalinventoryInventory');
      });


      // Manage users
      // $v1->get('/users', \App\v1\Controllers\User::class . ':getAll');

      // Manage items
      $v1->group("/items", function (RouteCollectorProxy $item)
      {
        $item->map(['GET'], '/type/{typeid:[0-9]+}', \App\v1\Controllers\Item::class . ':getAll');
        $item->map(['POST'], '', \App\v1\Controllers\Item::class . ':postItem');

        $item->group("/{id:[0-9]+}", function (RouteCollectorProxy $itemId)
        {
          $itemId->map(['GET'], '', \App\v1\Controllers\Item::class . ':getOne');
          $itemId->map(['PATCH'], '', \App\v1\Controllers\Item::class . ':patchItem');
          $itemId->map(['DELETE'], '', \App\v1\Controllers\Item::class . ':deleteItem');

          $itemId->group("/property", function (RouteCollectorProxy $property)
          {
            $property->group("/{propertyid:[0-9]+}", function (RouteCollectorProxy $propertyId)
            {
              $propertyId->map(['PATCH'], '', \App\v1\Controllers\Item::class . ':patchProperty');
              $propertyId->map(['DELETE'], '', \App\v1\Controllers\Item::class . ':deleteProperty');
            });
          });
        });
      });
      $v1->group("/config", function (RouteCollectorProxy $config)
      {
        // Manage types
        $config->group("/types", function (RouteCollectorProxy $type)
        {
          $type->map(['GET'], '', \App\v1\Controllers\Config\Type::class . ':getAll');
          $type->map(['POST'], '', \App\v1\Controllers\Config\Type::class . ':postItem');
          $type->group("/{id:[0-9]+}", function (RouteCollectorProxy $typeid)
          {
            $typeid->map(['GET'], '', \App\v1\Controllers\Config\Type::class . ':getOne');
            $typeid->map(['PATCH'], '', \App\v1\Controllers\Config\Type::class . ':patchItem');
            $typeid->map(['DELETE'], '', \App\v1\Controllers\Config\Type::class . ':deleteItem');
            $typeid->group("/property", function (RouteCollectorProxy $property)
            {
              $property->map(['POST'], '/{propertyid:[0-9]+}', \App\v1\Controllers\Config\Type::class . ':postProperty');
              $property->map(['DELETE'], '/{propertyid:[0-9]+}', \App\v1\Controllers\Config\Type::class . ':deleteProperty');
            });

            // TODO Must have route to delete a property

            $typeid->group("/propertygroups", function (RouteCollectorProxy $propertygroup)
            {
              $propertygroup->map(['POST'], '', \App\v1\Controllers\Config\TypePropertygroup::class . ':postItem');
              // $propertygroup->map(['PATCH'], '/propertygroupid:[0-9]+', \App\v1\Controllers\Config\TypePropertygroup::class . ':patchItem');
            });
          });
          $type->map(['POST'], '/templates', \App\v1\Controllers\Config\Type::class . ':postTemplate');
        });
        $config->group("/typeproperties", function (RouteCollectorProxy $type)
        {
          $type->map(['GET'], '', \App\v1\Controllers\Config\TypeProperty::class . ':getAll');
          $type->map(['POST'], '', \App\v1\Controllers\Config\TypeProperty::class . ':postItem');
          $type->map(['PATCH'], '', \App\v1\Controllers\Config\TypeProperty::class . ':patchItem');
        });
      });

      /*
      * itemstate: get/post/put/delete 
      * property: get/post/put/delete
      * propertylistvalue: get/post/put/delete
      * item_property: put
      */

      $v1->group("/rules/{type:searchitem|rewritefield|actionscript}", function (RouteCollectorProxy $rule)
      {
        $rule->map(['GET'], '', \App\v1\Controllers\Rule::class . ':getAll');
        $rule->map(['POST'], '', \App\v1\Controllers\Rule::class . ':postRule');
        $rule->group('/{id:[0-9]+}', function (RouteCollectorProxy $ruleOne)
        {
          $ruleOne->map(['GET'], '', \App\v1\Controllers\Rule::class . ':getOne');
          $ruleOne->map(['PATCH'], '', \App\v1\Controllers\Rule::class . ':updateOne');
          $ruleOne->map(['DELETE'], '', \App\v1\Controllers\Rule::class . ':deleteItem');
          $ruleOne->map(['POST'], '/criteria', \App\v1\Controllers\Rule::class . ':postCriterium');
          $ruleOne->group('/criteria/{idCriterium:[0-9]+}', function (RouteCollectorProxy $ruleCriteria)
          {
            $ruleCriteria->map(['PATCH'], '', \App\v1\Controllers\Rule::class . ':updateCriterium');
            $ruleCriteria->map(['DELETE'], '', \App\v1\Controllers\Rule::class . ':deleteCriterium');
          });
          $ruleOne->map(['POST'], '/actions', \App\v1\Controllers\Rule::class . ':postAction');
          $ruleOne->group('/actions/{idAction:[0-9]+}', function (RouteCollectorProxy $ruleAction)
          {
            $ruleAction->map(['PATCH'], '', \App\v1\Controllers\Rule::class . ':updateAction');
            $ruleAction->map(['DELETE'], '', \App\v1\Controllers\Rule::class . ':deleteAction');
          });
        });
      });
    });
  }
}
