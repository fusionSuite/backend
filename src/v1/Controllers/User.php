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

final class User
{
  /**
   * @api {get} /v1/userparams Get the userparams types
   * @apiName GetUserparams
   * @apiGroup User
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Object}   itemlist                              The itemlist param.
   * @apiSuccess {Number}   itemlist.id                           The id of type (see type endpoint) for manage
   *    itemlist userparam.
   * @apiSuccess {Object}   itemlist.properties                   List of properties
   * @apiSuccess {Number}   itemlist.properties.typeId            The property id where store for what id of item
   *    manage this userparams.
   * @apiSuccess {Number}   itemlist.properties.elementsPerPage   The property id for store the default number of
   *    elements to display per page.
   * @apiSuccess {Number}   itemlist.properties.propertiesOrder   The property id for store the order to display
   *    properties.
   * @apiSuccess {Number}   itemlist.properties.propertiesHidden  The property id for store the hidden properties.
   * @apiSuccess {Object}   csvimport                             The csvimport param for manage CSV import userparam.
   * @apiSuccess {Number}   csvimport.id                          The id of type (see type endpoint).
   * @apiSuccess {Object}   csvimport.properties                  List of properties
   * @apiSuccess {Number}   csvimport.properties.typeId           The property id where store for what id of item
   *    manage this userparams.
   * @apiSuccess {Number}   csvimport.properties.mappingCols      The property id for store the columns mapping.
   * @apiSuccess {Number}   csvimport.properties.joiningFields    The property id for store what fields are joining.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "itemlist": {
   *     "id": 2,
   *     "properties": {
   *       "typeId": 45,
   *       "elementsPerPage": 46,
   *       "propertiesOrder": 48,
   *       "propertiesHidden": 49
   *     }
   *   },
   *   "csvimport": {
   *     "id": 10,
   *     "properties": {
   *       "typeId": 45,
   *       "mappingCols": 51,
   *       "joiningFields": 52
   *     }
   *   }
   * }
   *
   */
  public function getUserparams(Request $request, Response $response, $args): Response
  {
    $types = \App\v1\Models\Config\Type::whereIn('internalname', [
      'userparam.itemlist',
      'userparam.item',
      'userparam.csvimport',
      'userparam.globalmenu',
      'userparam.homepage'
    ])->get();

    $ret = [
      'itemlist'   => null,
      // 'item'       => null,
      'csvimport'  => null,
      // 'globalmenu' => null,
      // 'homepage'   => null
    ];
    foreach ($types as $type)
    {
      $item = [
        'id' => $type->id,
        'properties' => []
      ];
      switch ($type->internalname)
      {
        case 'userparam.itemlist':
          $properties = [
            'typeId'           => null,
            'elementsPerPage'  => null,
            'propertiesOrder'  => null,
            'propertiesHidden' => null
          ];
          foreach ($type->properties()->get() as $prop)
          {
            switch ($prop->internalname)
            {
              case 'internal.typeId':
                $properties['typeId'] = $prop->id;
                  break;

              case 'internal.elementsPerPage':
                $properties['elementsPerPage'] = $prop->id;
                  break;

              case 'internal.properties':
                $properties['propertiesOrder'] = $prop->id;
                  break;

              case 'internal.propertieshidden':
                $properties['propertiesHidden'] = $prop->id;
                  break;
            }
          }
          $item['properties'] = $properties;
          $ret['itemlist'] = $item;
            break;

        case 'userparam.csvimport':
          $properties = [
            'typeId'        => null,
            'mappingCols'   => null,
            'joiningFields' => null
          ];
          foreach ($type->properties()->get() as $prop)
          {
            switch ($prop->internalname)
            {
              case 'internal.typeId':
                $properties['typeId'] = $prop->id;
                  break;

              case 'internal.mappingcols':
                $properties['mappingCols'] = $prop->id;
                  break;

              case 'internal.joiningfields':
                $properties['joiningFields'] = $prop->id;
                  break;
            }
          }
          $item['properties'] = $properties;
          $ret['csvimport'] = $item;
            break;
      }
    }
    $response->getBody()->write(json_encode($ret));
    return $response->withHeader('Content-Type', 'application/json');
  }
}
