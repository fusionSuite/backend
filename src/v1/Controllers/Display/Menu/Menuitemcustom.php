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

namespace App\v1\Controllers\Display\Menu;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class Menuitemcustom
{
  use \App\v1\Read;

  /**
   * @api {get} /v1/display/menu/custom Get all custom items of menu
   * @apiName GetDisplayMenuCustoms
   * @apiGroup Display/Menu/Customs
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Object[]}      customs                          List of customitens.
   * @apiSuccess {Number}        customs.id                       The id of the customitem.
   * @apiSuccess {Number}        customs.position                 The position of the customitem in the menu.
   * @apiSuccess {ISO8601}       customs.created_at               Date of the item creation.
   * @apiSuccess {null|ISO8601}  customs.updated_at               Date of the last item modification.
   * @apiSuccess {Object}        customs.menuitem                 The menuitem of this item.
   * @apiSuccess {Number}        customs.menuitem.id              The id of the menuitem.
   * @apiSuccess {String}        customs.menuitem.name            The name of the item.
   * @apiSuccess {null|String}   customs.menuitem.icon            The icon name of the item.
   * @apiSuccess {Number}        customs.menuitem.position        The position of the item in the menu.
   * @apiSuccess {ISO8601}       customs.menuitem.created_at      Date of the item creation.
   * @apiSuccess {null|ISO8601}  customs.menuitem.updated_at      Date of the last item modification.
   * @apiSuccess {Object}        customs.menuitem.type                            The type of this item.
   * @apiSuccess {Number}        customs.menuitem.type.id                         The id of the type.
   * @apiSuccess {String}        customs.menuitem.type.name                       The name of the type.
   * @apiSuccess {String}        customs.menuitem.type.internalname               The internalname of the type.
   * @apiSuccess {String="logical","physical"} customs.menuitem.type.modeling     The model of the type.
   * @apiSuccess {Boolean}       customs.menuitem.type.tree                       Set if the items of this type are
   *    in a tree.
   * @apiSuccess {Boolean}       customs.menuitem.type.allowtreemultipleroots     Set if the items of this type can
   *    have multiple roots.
   * @apiSuccess {Boolean}       customs.menuitem.type.unique_name                Set if the name of items is unique.
   * @apiSuccess {ISO8601}       customs.menuitem.type.created_at                 Date of the type creation.
   * @apiSuccess {null|ISO8601}  customs.menuitem.type.updated_at                 Date of the last type modification.
   * @apiSuccess {null|ISO8601}  customs.menuitem.type.deleted_at                 Date of the soft delete of the type.
   * @apiSuccess {null|Object}   customs.menuitem.type.created_by                 User has created the type.
   * @apiSuccess {Number}        customs.menuitem.type.created_by.id              Id of the user has created the type.
   * @apiSuccess {String}        customs.menuitem.type.created_by.name            Name (login) of the user has created
   *    the type.
   * @apiSuccess {String}        customs.menuitem.type.created_by.first_name      First name of the user has created
   *    the type.
   * @apiSuccess {String}        customs.menuitem.type.created_by.last_name       Last name of the user has created
   *    the type.
   * @apiSuccess {null|Object}   customs.menuitem.type.updated_by                 User has updated the type.
   * @apiSuccess {Number}        customs.menuitem.type.updated_by.id              Id of the user has updated the type.
   * @apiSuccess {String}        customs.menuitem.type.updated_by.name            Name (login) of the user has updated
   *    the type.
   * @apiSuccess {String}        customs.menuitem.type.updated_by.first_name      First name of the user has updated
   *    the type.
   * @apiSuccess {String}        customs.menuitem.type.updated_by.last_name       Last name of the user has updated
   *    the type.
   * @apiSuccess {null|Object}   customs.menuitem.type.deleted_by                 User has soft deleted the type.
   * @apiSuccess {Number}        customs.menuitem.type.deleted_by.id              Id of the user has soft deleted
   *    the type.
   * @apiSuccess {String}        customs.menuitem.type.deleted_by.name            Name (login) of the user has soft
   *    deleted the type.
   * @apiSuccess {String}        customs.menuitem.type.deleted_by.first_name      First name of the user has soft
   *    deleted the type.
   * @apiSuccess {String}        customs.menuitem.type.deleted_by.last_name       Last name of the user has soft
   *    deleted the type.
   * @apiSuccess {Object[]}      customs.menuitem.type.properties                 The properties list.
   * @apiSuccess {Number}        customs.menuitem.type.properties.id              The id of the property.
   * @apiSuccess {String}        customs.menuitem.type.properties.name            The name of the property.
   * @apiSuccess {String}        customs.menuitem.type.properties.internalname    The internalname of the property.
   * @codingStandardsIgnoreStart because break apidocsjs
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink","itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  customs.menuitem.type.properties.valuetype   The type of value.
   * @codingStandardsIgnoreEnd
   * @apiSuccess {null|String}   customs.menuitem.type.properties.unit            The unit used for the property
   *    (example: Ko, seconds...).
   * @apiSuccess {null|String}   customs.menuitem.type.properties.description     The description of the propery.
   * @apiSuccess {Boolean}       customs.menuitem.type.properties.canbenull       The property can be null or not.
   * @apiSuccess {Boolean}       customs.menuitem.type.properties.setcurrentdate  The property in the item can
   *    automatically use the current date when store in DB.
   * @apiSuccess {null|String}   customs.menuitem.type.properties.regexformat     The regexformat to verify the value
   *    is conform (works only with valuetype is string or list).
   * @apiSuccess {null|String[]} customs.menuitem.type.properties.listvalues      The list of values when
   *    valuetype="list", else null.
   * @apiSuccess {Any}           customs.menuitem.type.properties.default         The default value.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 1,
   *     "position": 0,
   *     "user_id": 2,
   *     "menuitem": {
   *       "id": 8,
   *       "name": "Laptop",
   *       "icon": "signs-post",
   *       "position": 0,
   *       "menu_id": 9,
   *       "created_at": "2023-02-06T07:00:00.000000Z",
   *       "updated_at": "2023-02-06T07:00:00.000000Z",
   *       "type": {
   *         "id": 3,
   *         "name": "Laptop",
   *         "internalname": "laptop",
   *         "sub_organization": false,
   *         "modeling": "physical",
   *         "tree": false,
   *         "allowtreemultipleroots": false,
   *         "unique_name": false,
   *         "created_at": "2023-02-06T06:54:36.000000Z",
   *         "updated_at": "2023-02-06T06:54:36.000000Z",
   *         "deleted_at": null,
   *         "created_by": {
   *           "id": 2,
   *           "name": "admin",
   *           "first_name": "Steve",
   *           "last_name": "Rogers"
   *         },
   *         "updated_by": null,
   *         "deleted_by": null,
   *         "properties": [
   *           {
   *             "id": 10,
   *             "name": "Serial number",
   *             "internalname": "serialnumber",
   *             "valuetype": "string",
   *             "regexformat": null,
   *             "unit": null,
   *             "description": null,
   *             "canbenull": true,
   *             "setcurrentdate": null,
   *             "listvalues": [],
   *             "value": null,
   *             "default": "",
   *             "byfusioninventory": null
   *           }
   *         ],
   *         "organization": {
   *           "id": 1,
   *           "name": "My organization"
   *         }
   *       }
   *     }
   *   }
   * ]
   *
   */
  public function routeGetAll(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $userId = $GLOBALS['user_id'];

    $menuitemcustoms = \App\v1\Models\Display\Menu\Menuitemcustom::query()->where('user_id', $userId)->orderBy('id')->get();

    $response->getBody()->write($menuitemcustoms->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {get} /v1/display/menu/custom/:id Get all custom items of menu
   * @apiName GetDisplayMenuCustom
   * @apiGroup Display/Menu/Customs
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Number}        id                       The id of the customitem.
   * @apiSuccess {Number}        position                 The position of the customitem in the menu.
   * @apiSuccess {ISO8601}       created_at               Date of the item creation.
   * @apiSuccess {null|ISO8601}  updated_at               Date of the last item modification.
   * @apiSuccess {Object}        menuitem                 The menuitem of this item.
   * @apiSuccess {Number}        menuitem.id              The id of the menuitem.
   * @apiSuccess {String}        menuitem.name            The name of the item.
   * @apiSuccess {null|String}   menuitem.icon            The icon name of the item.
   * @apiSuccess {Number}        menuitem.position        The position of the item in the menu.
   * @apiSuccess {ISO8601}       menuitem.created_at      Date of the item creation.
   * @apiSuccess {null|ISO8601}  menuitem.updated_at      Date of the last item modification.
   * @apiSuccess {Object}        menuitem.type                            The type of this item.
   * @apiSuccess {Number}        menuitem.type.id                         The id of the type.
   * @apiSuccess {String}        menuitem.type.name                       The name of the type.
   * @apiSuccess {String}        menuitem.type.internalname               The internalname of the type.
   * @apiSuccess {String="logical","physical"} menuitem.type.modeling     The model of the type.
   * @apiSuccess {Boolean}       menuitem.type.tree                       Set if the items of this type are
   *    in a tree.
   * @apiSuccess {Boolean}       menuitem.type.allowtreemultipleroots     Set if the items of this type can
   *    have multiple roots.
   * @apiSuccess {Boolean}       menuitem.type.unique_name                Set if the name of items is unique.
   * @apiSuccess {ISO8601}       menuitem.type.created_at                 Date of the type creation.
   * @apiSuccess {null|ISO8601}  menuitem.type.updated_at                 Date of the last type modification.
   * @apiSuccess {null|ISO8601}  menuitem.type.deleted_at                 Date of the soft delete of the type.
   * @apiSuccess {null|Object}   menuitem.type.created_by                 User has created the type.
   * @apiSuccess {Number}        menuitem.type.created_by.id              Id of the user has created the type.
   * @apiSuccess {String}        menuitem.type.created_by.name            Name (login) of the user has created the type.
   * @apiSuccess {String}        menuitem.type.created_by.first_name      First name of the user has created the type.
   * @apiSuccess {String}        menuitem.type.created_by.last_name       Last name of the user has created the type.
   * @apiSuccess {null|Object}   menuitem.type.updated_by                 User has updated the type.
   * @apiSuccess {Number}        menuitem.type.updated_by.id              Id of the user has updated the type.
   * @apiSuccess {String}        menuitem.type.updated_by.name            Name (login) of the user has updated the type.
   * @apiSuccess {String}        menuitem.type.updated_by.first_name      First name of the user has updated the type.
   * @apiSuccess {String}        menuitem.type.updated_by.last_name       Last name of the user has updated the type.
   * @apiSuccess {null|Object}   menuitem.type.deleted_by                 User has soft deleted the type.
   * @apiSuccess {Number}        menuitem.type.deleted_by.id              Id of the user has soft deleted the type.
   * @apiSuccess {String}        menuitem.type.deleted_by.name            Name (login) of the user has soft deleted
   *    the type.
   * @apiSuccess {String}        menuitem.type.deleted_by.first_name      First name of the user has soft deleted
   *    the type.
   * @apiSuccess {String}        menuitem.type.deleted_by.last_name       Last name of the user has soft deleted
   *    the type.
   * @apiSuccess {Object[]}      menuitem.type.properties                 The properties list.
   * @apiSuccess {Number}        menuitem.type.properties.id              The id of the property.
   * @apiSuccess {String}        menuitem.type.properties.name            The name of the property.
   * @apiSuccess {String}        menuitem.type.properties.internalname    The internalname of the property.
   * @codingStandardsIgnoreStart because break apidocsjs
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink","itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  menuitem.type.properties.valuetype   The type of value.
   * @codingStandardsIgnoreEnd
   * @apiSuccess {null|String}   menuitem.type.properties.unit            The unit used for the property
   *    (example: Ko, seconds...).
   * @apiSuccess {null|String}   menuitem.type.properties.description     The description of the propery.
   * @apiSuccess {Boolean}       menuitem.type.properties.canbenull       The property can be null or not.
   * @apiSuccess {Boolean}       menuitem.type.properties.setcurrentdate  The property in the item can
   *    automatically use the current date when store in DB.
   * @apiSuccess {null|String}   menuitem.type.properties.regexformat     The regexformat to verify the value
   *    is conform (works only with valuetype is string or list).
   * @apiSuccess {null|String[]} menuitem.type.properties.listvalues      The list of values when
   *    valuetype="list", else null.
   * @apiSuccess {Any}           menuitem.type.properties.default         The default value.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 1,
   *   "position": 0,
   *   "user_id": 2,
   *   "menuitem": {
   *     "id": 8,
   *     "name": "Laptop",
   *     "icon": "signs-post",
   *     "position": 0,
   *     "menu_id": 9,
   *     "created_at": "2023-02-06T07:00:00.000000Z",
   *     "updated_at": "2023-02-06T07:00:00.000000Z",
   *     "type": {
   *       "id": 3,
   *       "name": "Laptop",
   *       "internalname": "laptop",
   *       "sub_organization": false,
   *       "modeling": "physical",
   *       "tree": false,
   *       "allowtreemultipleroots": false,
   *       "unique_name": false,
   *       "created_at": "2023-02-06T06:54:36.000000Z",
   *       "updated_at": "2023-02-06T06:54:36.000000Z",
   *       "deleted_at": null,
   *       "created_by": {
   *         "id": 2,
   *         "name": "admin",
   *         "first_name": "Steve",
   *         "last_name": "Rogers"
   *       },
   *       "updated_by": null,
   *       "deleted_by": null,
   *       "properties": [
   *         {
   *           "id": 10,
   *           "name": "Serial number",
   *           "internalname": "serialnumber",
   *           "valuetype": "string",
   *           "regexformat": null,
   *           "unit": null,
   *           "description": null,
   *           "canbenull": true,
   *           "setcurrentdate": null,
   *           "listvalues": [],
   *           "value": null,
   *           "default": "",
   *           "byfusioninventory": null
   *         }
   *       ],
   *       "organization": {
   *         "id": 1,
   *         "name": "My organization"
   *       }
   *     }
   *   }
   * }
   *
   */
  public function routeGetOne(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $userId = $GLOBALS['user_id'];

    $menuitemcustoms = \App\v1\Models\Display\Menu\Menuitemcustom::query()->find($args['id']);
    if (is_null($menuitemcustoms))
    {
      throw new \Exception("This custom item has not be found", 404);
    }
    if ($menuitemcustoms->user_id != $userId)
    {
      throw new \Exception("This custom item has not be found", 404);
    }

    $response->getBody()->write($menuitemcustoms->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/display/menu/custom Create a new item of a menu
   * @apiName PostDisplayMenuCustom
   * @apiGroup Display/Menu/Customs
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiBody {String}       menuitem_id   The id of the custommenu.
   * @apiBody {Number}       [position]    The position of the custommenu.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "menuitem_id": 3,
   * }
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   "id":3
   * ]
   *
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The menu item is an id than does not exist"
   * }
   *
   */

  public function routePost(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $userId = $GLOBALS['user_id'];
    $data = json_decode($request->getBody());

    // Validate the data format
    $dataFormat = [
      'menuitem_id'      => 'required|type:integer|min:1',
      'position'         => 'type:integer|regex:/^[0-9]+$/'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $menuitem = \App\v1\Models\Display\Menu\Menuitem::query()->find($data->menuitem_id);
    if (is_null($menuitem))
    {
      throw new \Exception("The menu item is an id than does not exist", 400);
    }

    $custom = new \App\v1\Models\Display\Menu\Menuitemcustom();
    $custom->menuitem_id = $data->menuitem_id;

    // get the max position
    $maxItem = \App\v1\Models\Display\Menu\Menuitemcustom::query()->where('user_id', $userId)
      ->orderBy('position', 'desc')
      ->first();
    if ($maxItem !== null)
    {
      if (property_exists($data, 'position'))
      {
        if ($data->position > $maxItem->position)
        {
          $custom->position = $maxItem->position + 1;
        } else {
          \App\v1\Models\Display\Menu\Menuitemcustom::query()->where('user_id', $userId)
            ->where('position', '>=', $data->position)
            ->increment('position', 1);
          $custom->position = $data->position;
        }
      } else {
        $custom->position = $maxItem->position + 1;
      }
    }

    $custom->user_id = $userId;
    $custom->save();

    $response->getBody()->write(json_encode(["id" => intval($custom->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {patch} /v1/display/menu/custom/:id Update an existing custom item menu
   * @apiName PatchDisplayMenuCustom
   * @apiGroup Display/Menu/Customs
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the customitemmenu.
   *
   * @apiBody {Number}  [position]   New position of the custom item menu.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "position": 2,
   * }
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   *
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 404 Bad Request
   * {
   *   "status: "error",
   *   "message": "The custom item has not be found"
   * }
   *
   */
  public function routePatch(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $userId = $GLOBALS['user_id'];

    $data = json_decode($request->getBody());
    $custom = \App\v1\Models\Display\Menu\Menuitemcustom::query()->find($args['id']);
    if (is_null($custom))
    {
      throw new \Exception("The custom item has not be found", 404);
    }
    if ($custom->user_id != $userId)
    {
      throw new \Exception("This custom item has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'position' => 'type:integer|regex:/^[0-9]+$/'
    ];
    \App\v1\Common::validateData($data, $dataFormat);
    $properties = ['position'];
    foreach ($data as $key)
    {
      if (!in_array($key, $properties))
      {
        throw new \Exception("The property $key is not allowed", 400);
      }
    };

    foreach ($properties as $propertyName)
    {
      $custom->$propertyName = $data->$propertyName;
    }

    \App\v1\Controllers\Log\Audit::addEntry(
      $request,
      'UPDATE',
      '',
      'Display\Menu\Menuitemcustom',
      $custom->id
    );
    $custom->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/display/menu/custom/:id delete a custom item
   * @apiName DeleteDisplayMenuCustom
   * @apiGroup Display/Menu/Customs
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the custom item.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   *
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 404 Bad Request
   * {
   *   "status: "error",
   *   "message": "The custom item has not be found"
   * }
   *
   */
  public function routeDelete(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $userId = $GLOBALS['user_id'];

    $custom = \App\v1\Models\Display\Menu\Menuitemcustom::query()->find($args['id']);

    if (is_null($custom))
    {
      throw new \Exception("The custom item has not be found", 404);
    }
    if ($custom->user_id != $userId)
    {
      throw new \Exception("This custom item has not be found", 404);
    }
    $customId = $args['id'];

    // check permissions
    // \App\v1\Permission::checkPermissionToStructure('delete', 'config/property', $property->id);

    \App\v1\Controllers\Log\Audit::addEntry(
      $request,
      'DELETE',
      '',
      'Display\Menu\Menuitemcustom',
      $custom->id
    );
    $custom->forceDelete();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }
}
