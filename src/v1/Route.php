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
  static function setRoutes(&$app, $prefix) {

    // Enable OPTIONS method for all routes
    $app->options($prefix.'/{routes:.+}', function ($request, $response, $args) {
      return $response;
    });

    // The ping - pong ;)
    $app->get($prefix.'/ping', function (Request $request, Response $response, array $args) {
      $name = $args['name'];
      $response->getBody()->write("pong");
      return $response;
    });

    $app->group($prefix.'/v1', function (RouteCollectorProxy $v1)
    {
      $v1->post("/token", \App\v1\Controllers\Token::class . ':postToken');

      $v1->group('/fusioninventory', function (RouteCollectorProxy $fusion)
      {
        $fusion->map(['POST'], '/register', \App\v1\Controllers\Fusioninventory::class . ':postRegister');
        $fusion->map(['GET'], '/configuration', \App\v1\Controllers\Fusioninventory::class . ':getConfig');

        $fusion->map(['GET'], '/localinventory', \App\v1\Controllers\Fusioninventory::class . ':getLocalinventoryConfig');
        $fusion->map(['POST'], '/localinventory', \App\v1\Controllers\Fusioninventory::class . ':postLocalinventoryInventory');
      });


      // Manage users
      $v1->get('/users', \App\v1\Controllers\User::class . ':getAll');

      $v1->group("/cmdb", function (RouteCollectorProxy $cmdb)
      {
        $cmdb->group("/items/{id:[0-9]+}", function (RouteCollectorProxy $item)
        {
          $item->map(['GET'], '', \App\v1\Controllers\CMDB\Item::class . ':getOne');
          $item->map(['PATCH'], '', \App\v1\Controllers\CMDB\Item::class . ':updateItem');
        });
        $cmdb->group("/types", function (RouteCollectorProxy $type)
        {
          $type->map(['GET'], '', \App\v1\Controllers\CMDB\Type::class . ':getAll');
          $type->map(['POST'], '', \App\v1\Controllers\CMDB\Type::class . ':postItem');
          $type->map(['PATCH'], '', \App\v1\Controllers\CMDB\Type::class . ':patchItem');
          $type->group("/{id:[0-9]+}", function (RouteCollectorProxy $typeid)
          {
            $typeid->map(['GET'], '', \App\v1\Controllers\CMDB\Type::class . ':getOne');
            $typeid->group("/property", function (RouteCollectorProxy $property)
            {
              $property->map(['POST'], '/{propertyid:[0-9]+}', \App\v1\Controllers\CMDB\Type::class . ':postProperty');
              $property->map(['DELETE'], '/{propertyid:[0-9]+}', \App\v1\Controllers\CMDB\Type::class . ':deleteProperty');
            });

            $typeid->group("/items", function (RouteCollectorProxy $item)
            {
              $item->map(['GET'], '', \App\v1\Controllers\CMDB\Item::class . ':getAll');
              $item->map(['POST'], '', \App\v1\Controllers\CMDB\Item::class . ':postItem');
            });

            $typeid->group("/propertygroups", function (RouteCollectorProxy $propertygroup)
            {
              $propertygroup->map(['POST'], '', \App\v1\Controllers\CMDB\TypePropertygroup::class . ':postItem');
              // $propertygroup->map(['PATCH'], '/propertygroupid:[0-9]+', \App\v1\Controllers\CMDB\TypePropertygroup::class . ':patchItem');
            });
          });
        });
        $cmdb->group("/typeproperties", function (RouteCollectorProxy $type)
        {
          $type->map(['GET'], '', \App\v1\Controllers\CMDB\TypeProperty::class . ':getAll');
          $type->map(['POST'], '', \App\v1\Controllers\CMDB\TypeProperty::class . ':postItem');
          $type->map(['PATCH'], '', \App\v1\Controllers\CMDB\TypeProperty::class . ':patchItem');
        });
      });

      // CMDB
      
/*
* itemstate: get/post/put/delete 
* property: get/post/put/delete
* propertylistvalue: get/post/put/delete
* item_property: put
*/

      $v1->group("/rules/{type:searchitem|rewritefield|notification}", function (RouteCollectorProxy $rule)
      {
        $rule->map(['GET'], '', \App\v1\Controllers\Rule::class . ':getAll');
        $rule->map(['POST'], '', \App\v1\Controllers\Rule::class . ':postRule');
        $rule->group('/{id:[0-9]+}', function (RouteCollectorProxy $ruleOne)
        {
          $ruleOne->map(['GET'], '', \App\v1\Controllers\Rule::class . ':getOne');
          $ruleOne->map(['PATCH'], '', \App\v1\Controllers\Rule::class . ':updateOne');
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









      // TODO below

      // Manage tickets
      $v1->get('/tickets', \App\v1\Controllers\Ticket::class . ':getAll');
      $v1->post('/tickets', \App\v1\Controllers\Ticket::class . ':postTicket');
      $v1->group('/tickets/{id:[0-9]+}', function (RouteCollectorProxy $group)
      {
        $group->map(['GET'], '', \App\v1\Controllers\Ticket::class . ':getOne');
        $group->map(['PUT'], '', \App\v1\Controllers\Ticket::class . ':updateOne');
        $group->post('/followup', \App\v1\Controllers\Ticket::class . ':postFollowup');
        //    $group->post('/tasks', \App\v1\Controllers\Ticket::class);
        //    $group->post('/documents', \App\v1\Controllers\Ticket::class);
          // $group->post('/solutions', \App\v1\Controllers\Ticket::class. ':postSolution');
        //    $group->post('/validations', \App\v1\Controllers\Ticket::class);
        $group->post('/solutions/refuse', \App\v1\Controllers\Ticket::class . ':postSolutionRefuse');
        $group->post('/solutions/accept', \App\v1\Controllers\Ticket::class . ':postSolutionAccept');
        //    $group->post('/satisfaction', \App\v1\Controllers\Ticket::class);
      });

      // Manage form creator
      $v1->get('/servicecatalogs', \App\v1\Controllers\Servicecatalog::class . ':getAll');
      $v1->post('/servicecatalogs', \App\v1\Controllers\Servicecatalog::class . ':postServicecatalog');
      $v1->group('/servicecatalogs/{id:[0-9]+}', function (RouteCollectorProxy $group)
      {
        $group->map(['GET'], '', \App\v1\Controllers\Servicecatalog::class . ':getOne');
        $group->post('/answer', \App\v1\Controllers\Servicecatalog::class . ':postAnswer');
        $group->post('/section', \App\v1\Controllers\Servicecatalog::class . ':postSection');
        $group->group('/section/{idsec:[0-9]+}/question', function (RouteCollectorProxy $grpQuestions)
        {
          $grpQuestions->post('/checkbox', \App\v1\Controllers\Servicecatalog::class . ':postQuestionCheckbox');
          $grpQuestions->post('/date', \App\v1\Controllers\Servicecatalog::class . ':postQuestionDate');
          $grpQuestions->post('/datetime', \App\v1\Controllers\Servicecatalog::class . ':postQuestionDatetime');
          $grpQuestions->post('/description', \App\v1\Controllers\Servicecatalog::class . ':postQuestionDescription');
          $grpQuestions->post('/email', \App\v1\Controllers\Servicecatalog::class . ':postQuestionEmail');
          $grpQuestions->post('/file', \App\v1\Controllers\Servicecatalog::class . ':postQuestionFile');
          $grpQuestions->post('/float', \App\v1\Controllers\Servicecatalog::class . ':postQuestionFloat');
          $grpQuestions->post('/hidden', \App\v1\Controllers\Servicecatalog::class . ':postQuestionHidden');
          $grpQuestions->post('/integer', \App\v1\Controllers\Servicecatalog::class . ':postQuestionInteger');
          $grpQuestions->post('/radio', \App\v1\Controllers\Servicecatalog::class . ':postQuestionRadio');
          $grpQuestions->post('/select', \App\v1\Controllers\Servicecatalog::class . ':postQuestionSelect');
          $grpQuestions->post('/input', \App\v1\Controllers\Servicecatalog::class . ':postQuestionInput');
          $grpQuestions->post('/textarea', \App\v1\Controllers\Servicecatalog::class . ':postQuestionTextarea');
          $grpQuestions->post('/time', \App\v1\Controllers\Servicecatalog::class . ':postQuestionTime');

          $grpQuestions->post('/selectitem', \App\v1\Controllers\Servicecatalog::class . ':postQuestionSelectitem');
          $grpQuestions->get('/selectitem', \App\v1\Controllers\Servicecatalog::class . ':getQuestionSelectitemList');

        });
      });
      $v1->group('/servicecatalogs/categories', function (RouteCollectorProxy $sccategories)
      {
        $sccategories->map(['GET'], '', \App\v1\Controllers\Servicecatalog::class . ':getCategories');
      });


      // Manage groups
      $v1->get('/groups', \App\v1\Controllers\Group::class . ':getAll');

      // Manage news
      $v1->get('/news', \App\v1\Controllers\News::class . ':getAll');

      // Manage knowledge
      $v1->get('/knowledges', \App\v1\Controllers\Knowledge::class . ':getAll');
      $v1->get('/knowledges/{id:[0-9]+}', \App\v1\Controllers\Knowledge::class . ':getOne');
      $v1->get('/knowledges/categories', \App\v1\Controllers\Knowledge::class . ':getCategories');

      $v1->post('/diabolocom/callback', \App\v1\Controllers\Diabolocom::class . ':postInCampaign');
  
    });
  }
}
