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

namespace App;

use Slim\Routing\RouteCollectorProxy;

final class Route
{
  public static function setRoutes(&$app, $prefix)
  {
    // Enable OPTIONS method for all routes
    $app->options($prefix . '/{routes:.+}', function ($request, $response, $args)
    {
      return $response;
    });

    // The ping - pong ;)
    $app->get($prefix . '/ping', \App\v1\Controllers\Ping::class . ':getPing');

    $app->group($prefix . '/v1', function (RouteCollectorProxy $v1)
    {
      $v1->post("/token", \App\v1\Controllers\Token::class . ':postToken');
      $v1->post("/refreshtoken", \App\v1\Controllers\Token::class . ':postRefreshToken');
      $v1->get("/status", \App\v1\Controllers\Status::class . ':getStatus');

      $v1->group('/fusioninventory', function (RouteCollectorProxy $fusion)
      {
        $fusion->map(['POST'], '/register', \App\v1\Controllers\Fusioninventory::class . ':postRegister');
        $fusion->map(['GET'], '/configuration', \App\v1\Controllers\Fusioninventory::class . ':getConfig');

        $fusion->map(
          ['GET'],
          '/localinventory',
          \App\v1\Controllers\Fusioninventory::class . ':getLocalinventoryConfig'
        );
        $fusion->map(
          ['POST'],
          '/localinventory',
          \App\v1\Controllers\Fusioninventory::class . ':postLocalinventoryInventory'
        );
      });

      // Manage users
      $v1->get('/userparams', \App\v1\Controllers\User::class . ':getUserparams');

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

          // TODO manage functions in Item class:
          $itemId->group("/property", function (RouteCollectorProxy $property)
          {
            $property->group("/{propertyid:[0-9]+}", function (RouteCollectorProxy $propertyId)
            {
              $propertyId->map(['PATCH'], '', \App\v1\Controllers\Item::class . ':patchProperty');
              $propertyId->group("/itemlinks", function (RouteCollectorProxy $propertyItemlinkId)
              {
                $propertyItemlinkId->map(['POST'], '', \App\v1\Controllers\Item::class . ':postPropertyItemlink');
                $propertyItemlinkId->map(
                  ['DELETE'],
                  '/{itemlinkid:[0-9]+}',
                  \App\v1\Controllers\Item::class . ':deletePropertyItemlink'
                );
              });
              $propertyId->group("/typelinks", function (RouteCollectorProxy $propertyTypelinkId)
              {
                $propertyTypelinkId->map(['POST'], '', \App\v1\Controllers\Item::class . ':postPropertyTypelink');
                $propertyTypelinkId->map(
                  ['DELETE'],
                  '/{typelinkid:[0-9]+}',
                  \App\v1\Controllers\Item::class . ':deletePropertyTypelink'
                );
              });
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
              $property->map(
                ['POST'],
                '/{propertyid:[0-9]+}',
                \App\v1\Controllers\Config\Type::class . ':postProperty'
              );
              $property->map(
                ['DELETE'],
                '/{propertyid:[0-9]+}',
                \App\v1\Controllers\Config\Type::class . ':deleteProperty'
              );
            });
          });
          $type->map(['POST'], '/templates', \App\v1\Controllers\Config\Type::class . ':postTemplate');
        });
        $config->group("/properties", function (RouteCollectorProxy $properties)
        {
          $properties->map(['GET'], '', \App\v1\Controllers\Config\Property::class . ':getAll');
          $properties->map(['POST'], '', \App\v1\Controllers\Config\Property::class . ':postItem');
          $properties->group("/{id:[0-9]+}", function (RouteCollectorProxy $propertyId)
          {
            $propertyId->map(['GET'], '', \App\v1\Controllers\Config\Property::class . ':getOne');
            $propertyId->map(['PATCH'], '', \App\v1\Controllers\Config\Property::class . ':patchItem');
            $propertyId->map(['DELETE'], '', \App\v1\Controllers\Config\Property::class . ':deleteItem');

            $propertyId->group("/listvalues", function (RouteCollectorProxy $propertyValuetypeId)
            {
              $propertyValuetypeId->map(
                ['POST'],
                '',
                \App\v1\Controllers\Config\Property::class . ':postPropertyListvalue'
              );
              $propertyValuetypeId->map(
                ['DELETE'],
                '/{listvalueid:[0-9]+}',
                \App\v1\Controllers\Config\Property::class . ':deletePropertyListvalue'
              );
            });
          });
        });
        $config->group("/roles", function (RouteCollectorProxy $role)
        {
          $role->map(['GET'], '', \App\v1\Controllers\Config\Role::class . ':getAll');
          $role->map(['POST'], '', \App\v1\Controllers\Config\Role::class . ':postItem');
          $role->group("/{id:[0-9]+}", function (RouteCollectorProxy $roleid)
          {
            $roleid->map(['GET'], '', \App\v1\Controllers\Config\Role::class . ':getOne');
            $roleid->map(['PATCH'], '', \App\v1\Controllers\Config\Role::class . ':patchItem');
            $roleid->map(['DELETE'], '', \App\v1\Controllers\Config\Role::class . ':deleteItem');
            $roleid->group("/user", function (RouteCollectorProxy $user)
            {
              $user->map(
                ['POST'],
                '/{userid:[0-9]+}',
                \App\v1\Controllers\Config\Role::class . ':postUser'
              );
              $user->map(
                ['DELETE'],
                '/{userid:[0-9]+}',
                \App\v1\Controllers\Config\Role::class . ':deleteUser'
              );
            });
            $roleid->map(
              ['PATCH'],
              '/permissiondata/{typeid:[0-9]+}',
              \App\v1\Controllers\Config\Permissiondata::class . ':patchItem'
            );
            $roleid->map(
              ['PATCH'],
              '/permissiondata/{typeid:[0-9]+}/property/{propertyid:[0-9]+}',
              \App\v1\Controllers\Config\Permissiondataproperty::class . ':patchItem'
            );
            $roleid->map(
              ['PATCH'],
              '/permissionstructure/{structureid:[0-9]+}',
              \App\v1\Controllers\Config\Permissionstructure::class . ':patchItem'
            );
            $roleid->map(
              ['PATCH'],
              '/permissionstructure/{structureid:[0-9]+}/custom/{customid:[0-9]+}',
              \App\v1\Controllers\Config\Permissionstructurecustom::class . ':patchItem'
            );
          });
        });
      });

      $v1->group("/log", function (RouteCollectorProxy $log)
      {
        $log->group("/audits", function (RouteCollectorProxy $properties)
        {
          $properties->map(['GET'], '', \App\v1\Controllers\Log\Audit::class . ':getAll');
        });
      });

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

      // Manage display
      $v1->group("/display", function (RouteCollectorProxy $display)
      {
        $display->group("/menu", function (RouteCollectorProxy $menu)
        {
          $menu->map(['GET'], '', \App\v1\Controllers\Display\Menu\Menu::class . ':routeGetAll');
          $menu->map(['POST'], '', \App\v1\Controllers\Display\Menu\Menu::class . ':routePost');
          $menu->group("/{id:[0-9]+}", function (RouteCollectorProxy $menuId)
          {
            $menuId->map(['GET'], '', \App\v1\Controllers\Display\Menu\Menu::class . ':routeGetOne');
            $menuId->map(['PATCH'], '', \App\v1\Controllers\Display\Menu\Menu::class . ':routePatch');
            $menuId->map(['DELETE'], '', \App\v1\Controllers\Display\Menu\Menu::class . ':routeDelete');
          });
          $menu->group("/item", function (RouteCollectorProxy $item)
          {
            $item->map(['GET'], '', \App\v1\Controllers\Display\Menu\Menuitem::class . ':routeGetAll');
            $item->map(['POST'], '', \App\v1\Controllers\Display\Menu\Menuitem::class . ':routePost');
            $item->group("/{id:[0-9]+}", function (RouteCollectorProxy $itemId)
            {
              $itemId->map(['GET'], '', \App\v1\Controllers\Display\Menu\Menuitem::class . ':routeGetOne');
              $itemId->map(['PATCH'], '', \App\v1\Controllers\Display\Menu\Menuitem::class . ':routePatch');
              $itemId->map(['DELETE'], '', \App\v1\Controllers\Display\Menu\Menuitem::class . ':routeDelete');
            });
          });
          $menu->group("/custom", function (RouteCollectorProxy $custom)
          {
            $custom->map(['GET'], '', \App\v1\Controllers\Display\Menu\Menuitemcustom::class . ':routeGetAll');
            $custom->map(['POST'], '', \App\v1\Controllers\Display\Menu\Menuitemcustom::class . ':routePost');
            $custom->group("/{id:[0-9]+}", function (RouteCollectorProxy $customId)
            {
              $customId->map(['GET'], '', \App\v1\Controllers\Display\Menu\Menuitemcustom::class . ':routeGetOne');
              $customId->map(['PATCH'], '', \App\v1\Controllers\Display\Menu\Menuitemcustom::class . ':routePatch');
              $customId->map(['DELETE'], '', \App\v1\Controllers\Display\Menu\Menuitemcustom::class . ':routeDelete');
            });
          });
        });
        $display->group("/type", function (RouteCollectorProxy $type)
        {
          $type->map(
            ['GET'],
            '/{typeId:[0-9]+}/panels',
            \App\v1\Controllers\Display\Type\Typepanel::class . ':routeGetAllOfType'
          );
          $type->group("/panels", function (RouteCollectorProxy $panels)
          {
            $panels->map(['POST'], '', \App\v1\Controllers\Display\Type\Typepanel::class . ':routePost');
            $panels->group("/{panelId:[0-9]+}", function (RouteCollectorProxy $panel)
            {
              $panel->map(['GET'], '', \App\v1\Controllers\Display\Type\Typepanel::class . ':routeGetOne');
              $panel->map(['PATCH'], '', \App\v1\Controllers\Display\Type\Typepanel::class . ':routePatch');
              $panel->map(['DELETE'], '', \App\v1\Controllers\Display\Type\Typepanel::class . ':routeDelete');
              $panel->map(
                ['GET'],
                '/panelitems',
                \App\v1\Controllers\Display\Type\Typepanel::class . ':routeGetAllOfPanel'
              );
            });
          });
          $type->group('/panelitems/{panelitemId:[0-9]+}', function (RouteCollectorProxy $panelitem)
          {
            $panelitem->map(['GET'], '', \App\v1\Controllers\Display\Type\Typepanelitem::class . ':routeGetOne');
            $panelitem->map(['PATCH'], '', \App\v1\Controllers\Display\Type\Typepanelitem::class . ':routePatch');
          });
        });
      });
    });
  }
}
