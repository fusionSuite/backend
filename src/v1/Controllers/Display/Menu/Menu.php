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

final class Menu
{
  use \App\v1\Read;

  /**
   * @api {get} /v1/display/menu Get all menus
   * @apiName GetDisplayMenus
   * @apiGroup Display/Menus
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Object[]}         menus                          List of menus.
   * @apiSuccess {Number}           menus.id                       The id of the menu.
   * @apiSuccess {String}           menus.name                     The name of the menu.
   * @apiSuccess {null|String}      menus.icon                     The icon name of the menu.
   * @apiSuccess {Number}           menus.position                 The position of the menu.
   * @apiSuccess {ISO8601}          menus.created_at               Date of the menu creation.
   * @apiSuccess {null|ISO8601}     menus.updated_at               Date of the last menu modification.
   * @apiSuccess {Object[]}         menus.items                    The menuitems list.
   * @apiSuccess {Number}           menus.items.id                 The id of the menuitem.
   * @apiSuccess {String}           menus.items.name               The name of the menuitem.
   * @apiSuccess {null|String}      menus.items.icon               The icon name of the menuitem.
   * @apiSuccess {Number}           menus.items.position           The position of the menuitem in the menu.
   * @apiSuccess {String}           menus.items.menu_id            The id of the menu.
   * @apiSuccess {ISO8601}          menus.items.created_at         Date of the menu ite creation.
   * @apiSuccess {null|ISO8601}     menus.items.updated_at         Date of the last menu item modification.
   * @apiSuccess {Object}           menus.items.type               The type of the menuitem.
   * @apiSuccess {Number}           menus.items.type.id                            The id of the type.
   * @apiSuccess {String}           menus.items.type.name                          The name of the type.
   * @apiSuccess {String}           menus.items.type.internalname                  The internalname of the type.
   * @apiSuccess {String="logical","physical"} menus.items.type.modeling           The model of the type.
   * @apiSuccess {Boolean}          menus.items.type.tree                          Set if the items of this type are
   *    in a tree.
   * @apiSuccess {Boolean}          menus.items.type.allowtreemultipleroots        Set if the items of this type can
   *    have multiple roots.
   * @apiSuccess {Boolean}          menus.items.type.unique_name                   Set if the name of items is unique.
   * @apiSuccess {ISO8601}          menus.items.type.created_at                    Date of the type creation.
   * @apiSuccess {null|ISO8601}     menus.items.type.updated_at                    Date of the last type modification.
   * @apiSuccess {null|ISO8601}     menus.items.type.deleted_at                    Date of the soft delete of the type.
   * @apiSuccess {null|Object}      menus.items.type.created_by                    User has created the type.
   * @apiSuccess {Number}           menus.items.type.created_by.id                 Id of the user has created the type.
   * @apiSuccess {String}           menus.items.type.created_by.name               Name (login) of the user has created
   *    the type.
   * @apiSuccess {String}           menus.items.type.created_by.first_name         First name of the user has created
   *    the type.
   * @apiSuccess {String}           menus.items.type.created_by.last_name          Last name of the user has created
   *    the type.
   * @apiSuccess {null|Object}      menus.items.type.updated_by                    User has updated the type.
   * @apiSuccess {Number}           menus.items.type.updated_by.id                 Id of the user has updated the type.
   * @apiSuccess {String}           menus.items.type.updated_by.name               Name (login) of the user has updated
   *    the type.
   * @apiSuccess {String}           menus.items.type.updated_by.first_name         First name of the user has updated
   *    the type.
   * @apiSuccess {String}           menus.items.type.updated_by.last_name          Last name of the user has updated
   *    the type.
   * @apiSuccess {null|Object}      menus.items.type.deleted_by                    User has soft deleted the type.
   * @apiSuccess {Number}           menus.items.type.deleted_by.id                 Id of the user has soft deleted
   *    the type.
   * @apiSuccess {String}           menus.items.type.deleted_by.name               Name (login) of the user has
   *    soft deleted the type.
   * @apiSuccess {String}           menus.items.type.deleted_by.first_name         First name of the user has soft
   *    deleted the type.
   * @apiSuccess {String}           menus.items.type.deleted_by.last_name          Last name of the user has soft
   *    deleted the type.
   * @apiSuccess {Object[]}         menus.items.type.properties                    The properties list.
   * @apiSuccess {Number}           menus.items.type.properties.id                 The id of the property.
   * @apiSuccess {String}           menus.items.type.properties.name               The name of the property.
   * @apiSuccess {String}           menus.items.type.properties.internalname       The internalname of the property.
   * @codingStandardsIgnoreStart because break apidocsjs
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink","itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  menus.items.type.properties.valuetype   The type of value.
   * @codingStandardsIgnoreEnd
   * @apiSuccess {null|String}      menus.items.type.properties.unit               The unit used for the property
   *    (example: Ko, seconds...).
   * @apiSuccess {null|String}      menus.items.type.properties.description        The description of the propery.
   * @apiSuccess {Boolean}          menus.items.type.properties.canbenull          The property can be null or not.
   * @apiSuccess {Boolean}          menus.items.type.properties.setcurrentdate     The property in the item can
   *    automatically use the current date when store in DB.
   * @apiSuccess {null|String}      menus.items.type.properties.regexformat        The regexformat to verify the value
   *    is conform (works only with valuetype is string or list).
   * @apiSuccess {null|String[]}    menus.items.type.properties.listvalues         The list of values when
   *    valuetype="list", else null.
   * @apiSuccess {Any}              menus.items.type.properties.default            The default value.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 95,
   *     "name": "Assets",
   *     "icon": "[\"fas\",\"address-book\"]",
   *     "position": 0,
   *     "created_at": "2023-02-17T07:42:17.000000Z",
   *     "updated_at": "2023-02-17T07:42:17.000000Z",
   *     "items": [
   *       {
   *         "id": 113,
   *         "name": "Laptop",
   *         "icon": "signs-post",
   *         "position": 0,
   *         "menu_id": 95,
   *         "created_at": "2023-02-17T07:42:22.000000Z",
   *         "updated_at": "2023-02-17T07:42:22.000000Z",
   *         "type": {
   *           "id": 3,
   *           "name": "Laptop",
   *           "internalname": "laptop",
   *           "sub_organization": false,
   *           "modeling": "physical",
   *           "tree": false,
   *           "allowtreemultipleroots": false,
   *           "unique_name": false,
   *           "created_at": "2023-02-15T14:57:34.000000Z",
   *           "updated_at": "2023-02-15T14:57:34.000000Z",
   *           "deleted_at": null,
   *           "created_by": {
   *             "id": 2,
   *             "name": "admin",
   *             "first_name": "Steve",
   *             "last_name": "Rogers"
   *           },
   *           "updated_by": null,
   *           "deleted_by": null,
   *           "properties": [
   *             {
   *               "id": 10,
   *               "name": "Serial number",
   *               "internalname": "serialnumber",
   *               "valuetype": "string",
   *               "regexformat": null,
   *               "unit": null,
   *               "description": null,
   *               "canbenull": true,
   *               "setcurrentdate": null,
   *               "listvalues": [],
   *               "value": null,
   *               "default": "",
   *               "byfusioninventory": null
   *             }
   *           ],
   *           "organization": {
   *             "id": 1,
   *             "name": "My organization"
   *           }
   *         }
   *       }
   *     ]
   *   }
   * ]
   *
   */
  public function routeGetAll(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $menu = \App\v1\Models\Display\Menu\Menu::with('items')->orderBy('id')->get();

    $response->getBody()->write($menu->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {get} /v1/display/menu/:id Get one menu
   * @apiName GetDisplayMenu
   * @apiGroup Display/Menus
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} id menu unique ID.
   *
   * @apiSuccess {Number}           id                       The id of the menu.
   * @apiSuccess {String}           name                     The name of the menu.
   * @apiSuccess {null|String}      icon                     The icon name of the menu.
   * @apiSuccess {Number}           position                 The position of the menu.
   * @apiSuccess {ISO8601}          created_at               Date of the menu creation.
   * @apiSuccess {null|ISO8601}     updated_at               Date of the last menu modification.
   * @apiSuccess {Object[]}         items                    The menuitems list.
   * @apiSuccess {Number}           items.id                 The id of the menuitem.
   * @apiSuccess {String}           items.name               The name of the menuitem.
   * @apiSuccess {null|String}      items.icon               The icon name of the menuitem.
   * @apiSuccess {Number}           items.position           The position of the menuitem in the menu.
   * @apiSuccess {String}           items.menu_id            The id of the menu.
   * @apiSuccess {ISO8601}          items.created_at         Date of the menu ite creation.
   * @apiSuccess {null|ISO8601}     items.updated_at         Date of the last menu item modification.
   * @apiSuccess {Object}           items.type               The type of the menuitem.
   * @apiSuccess {Number}           items.type.id                            The id of the type.
   * @apiSuccess {String}           items.type.name                          The name of the type.
   * @apiSuccess {String}           items.type.internalname                  The internalname of the type.
   * @apiSuccess {String="logical","physical"} items.type.modeling           The model of the type.
   * @apiSuccess {Boolean}          items.type.tree                          Set if the items of this type are
   *    in a tree.
   * @apiSuccess {Boolean}          items.type.allowtreemultipleroots        Set if the items of this type can
   *    have multiple roots.
   * @apiSuccess {Boolean}          items.type.unique_name                   Set if the name of items is unique.
   * @apiSuccess {ISO8601}          items.type.created_at                    Date of the type creation.
   * @apiSuccess {null|ISO8601}     items.type.updated_at                    Date of the last type modification.
   * @apiSuccess {null|ISO8601}     items.type.deleted_at                    Date of the soft delete of the type.
   * @apiSuccess {null|Object}      items.type.created_by                    User has created the type.
   * @apiSuccess {Number}           items.type.created_by.id                 Id of the user has created the type.
   * @apiSuccess {String}           items.type.created_by.name               Name (login) of the user has created
   *    the type.
   * @apiSuccess {String}           items.type.created_by.first_name         First name of the user has created
   *    the type.
   * @apiSuccess {String}           items.type.created_by.last_name          Last name of the user has created
   *    the type.
   * @apiSuccess {null|Object}      items.type.updated_by                    User has updated the type.
   * @apiSuccess {Number}           items.type.updated_by.id                 Id of the user has updated the type.
   * @apiSuccess {String}           items.type.updated_by.name               Name (login) of the user has updated
   *    the type.
   * @apiSuccess {String}           items.type.updated_by.first_name         First name of the user has updated
   *    the type.
   * @apiSuccess {String}           items.type.updated_by.last_name          Last name of the user has updated the type.
   * @apiSuccess {null|Object}      items.type.deleted_by                    User has soft deleted the type.
   * @apiSuccess {Number}           items.type.deleted_by.id                 Id of the user has soft deleted the type.
   * @apiSuccess {String}           items.type.deleted_by.name               Name (login) of the user has soft deleted
   *    the type.
   * @apiSuccess {String}           items.type.deleted_by.first_name         First name of the user has soft deleted
   *    the type.
   * @apiSuccess {String}           items.type.deleted_by.last_name          Last name of the user has soft deleted
   *    the type.
   * @apiSuccess {Object[]}         items.type.properties                    The properties list.
   * @apiSuccess {Number}           items.type.properties.id                 The id of the property.
   * @apiSuccess {String}           items.type.properties.name               The name of the property.
   * @apiSuccess {String}           items.type.properties.internalname       The internalname of the property.
   * @codingStandardsIgnoreStart because break apidocsjs
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink","itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  items.type.properties.valuetype   The type of value.
   * @codingStandardsIgnoreEnd
   * @apiSuccess {null|String}      items.type.properties.unit               The unit used for the property
   *    (example: Ko, seconds...).
   * @apiSuccess {null|String}      items.type.properties.description        The description of the propery.
   * @apiSuccess {Boolean}          items.type.properties.canbenull          The property can be null or not.
   * @apiSuccess {Boolean}          items.type.properties.setcurrentdate     The property in the item can
   *    automatically use the current date when store in DB.
   * @apiSuccess {null|String}      items.type.properties.regexformat        The regexformat to verify the value
   *    is conform (works only with valuetype is string or list).
   * @apiSuccess {null|String[]}    items.type.properties.listvalues         The list of values when
   *    valuetype="list", else null.
   * @apiSuccess {Any}              items.type.properties.default            The default value.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 95,
   *   "name": "Assets",
   *   "icon": "[\"fas\",\"address-book\"]",
   *   "position": 0,
   *   "created_at": "2023-02-17T07:42:17.000000Z",
   *   "updated_at": "2023-02-17T07:42:17.000000Z",
   *   "items": [
   *     {
   *       "id": 113,
   *       "name": "Laptop",
   *       "icon": "signs-post",
   *       "position": 0,
   *       "menu_id": 95,
   *       "created_at": "2023-02-17T07:42:22.000000Z",
   *       "updated_at": "2023-02-17T07:42:22.000000Z",
   *       "type": {
   *         "id": 3,
   *         "name": "Laptop",
   *         "internalname": "laptop",
   *         "sub_organization": false,
   *         "modeling": "physical",
   *         "tree": false,
   *         "allowtreemultipleroots": false,
   *         "unique_name": false,
   *         "created_at": "2023-02-15T14:57:34.000000Z",
   *         "updated_at": "2023-02-15T14:57:34.000000Z",
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
   *   ]
   * }
   *
   */
  public function routeGetOne(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $menu = \App\v1\Models\Display\Menu\Menu::find($args['id']);
    if (is_null($menu))
    {
      throw new \Exception("This menu has not be found", 404);
    }

    $response->getBody()->write($menu->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/display/menu Create a new menu
   * @apiName PostDisplayMenu
   * @apiGroup Display/Menus
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiBody {String}       name           The name of the menu.
   * @apiBody {null|String}  [icon]         The icon name of the menu.
   * @apiBody {Number}       [position]     The position of the menu.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "Assets",
   * }
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id":3
   * }
   *
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The Name is required"
   * }
   *
   */
  public function routePost(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $data = json_decode($request->getBody());

    // Validate the data format
    $dataFormat = [
      'name'             => 'required|type:string',
      'icon'             => 'type:string',
      'position'         => 'type:integer|min:0'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $properties = ['name', 'icon', 'position'];
    foreach ($data as $key => $value)
    {
      if (!in_array($key, $properties))
      {
        throw new \Exception("The property $key is not allowed", 400);
      }
    };

    $menu = new \App\v1\Models\Display\Menu\Menu();
    $menu->name = $data->name;
    if (property_exists($data, 'icon'))
    {
      $menu->icon = \App\v1\Common::setDisplayIcon($data->icon);
    }
    // get the max position
    $maxMenu = \App\v1\Models\Display\Menu\Menu::orderBy('position', 'desc')->first();
    if ($maxMenu !== null)
    {
      if (property_exists($data, 'position'))
      {
        if ($data->position > $maxMenu->position)
        {
          $menu->position = $maxMenu->position + 1;
        } else {
          \App\v1\Models\Display\Menu\Menu::where('position', '>=', $data->position)
            ->increment('position', 1);
            $menu->position = $data->position;
        }
      } else {
        $menu->position = $maxMenu->position + 1;
      }
    }

    $menu->save();

    $response->getBody()->write(json_encode(["id" => intval($menu->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {patch} /v1/display/menu/:id Update an existing menu
   * @apiName PatchDisplayMenu
   * @apiGroup Display/Menus
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the menu.
   *
   * @apiBody {String}  [name]       Name of the menu.
   * @apiBody {null|String} [icon]   Icon name of the menu.
   * @apiBody {Number}  [position]   Position of the menu.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "Assets2",
   * }
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   *
   */
  public function routePatch(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $data = json_decode($request->getBody());
    $menu = \App\v1\Models\Display\Menu\Menu::find($args['id']);
    if (is_null($menu))
    {
      throw new \Exception("The menu has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'name'             => 'type:string',
      'icon'             => 'type:string',
      'position'         => 'type:integer|min:0'
    ];
    \App\v1\Common::validateData($data, $dataFormat);
    $properties = ['name', 'icon', 'position'];
    foreach ($data as $key => $value)
    {
      if (!in_array($key, $properties))
      {
        throw new \Exception("The property $key is not allowed", 400);
      }
    };

    foreach ($properties as $propertyName)
    {
      if (property_exists($data, $propertyName))
      {
        $menu->$propertyName = $data->$propertyName;
      }
    }
    // Special case for icon
    if (property_exists($data, 'icon'))
    {
      $menu->icon = \App\v1\Common::setDisplayIcon($data->icon);
    }

    \App\v1\Controllers\Log\Audit::addEntry(
      $request,
      'UPDATE',
      '',
      'Display\Menu\Menu',
      $menu->id
    );
    $menu->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/display/menu/:id delete a menu
   * @apiName DeleteDisplayMenu
   * @apiGroup Display/Menus
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the menu.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   *
   */
  public function routeDelete(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $menu = \App\v1\Models\Display\Menu\Menu::find($args['id']);

    if (is_null($menu))
    {
      throw new \Exception("The menu has not be found", 404);
    }

    // check permissions
    // \App\v1\Permission::checkPermissionToStructure('delete', 'config/property', $property->id);

    \App\v1\Controllers\Log\Audit::addEntry(
      $request,
      'DELETE',
      '',
      'Display\Menu\Menu',
      $menu->id
    );
    $menu->forceDelete();

    // ====== Post delete actions ====== //
    // decrease position of others with position more than this deleted menu
    \App\v1\Models\Display\Menu\Menu::where('position', '>=', $menu->position)
      ->decrement('position', 1);

    // delete menu items
    $menuitems = \App\v1\Models\Display\Menu\Menuitem::where('menu_id', $menu->id)->get();
    $menuitemClass = new \App\v1\Controllers\Display\Menu\Menuitem();
    foreach ($menuitems as $menuitem)
    {
      $menuitemClass->deleteItem($menuitem, $request);
    }
    // ====== End ====================== //

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }
}
