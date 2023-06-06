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

final class Menuitem
{
  use \App\v1\Read;

  /**
   * @api {get} /v1/display/menu/item Get all items of menus
   * @apiName GetDisplayMenuItems
   * @apiGroup Display/Menu/Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Object[]}         items                          List of items.
   * @apiSuccess {Number}           items.id                       The id of the item.
   * @apiSuccess {String}           items.name                       The name of the item.
   * @apiSuccess {null|String}      items.icon                       The icon name of the item.
   * @apiSuccess {Number}           items.position                 The position of the item in the menu.
   * @apiSuccess {ISO8601}          items.created_at                    Date of the item creation.
   * @apiSuccess {null|ISO8601}     items.updated_at                    Date of the last item modification.
   * @apiSuccess {Object}           items.type                    The type of this item.
   * @apiSuccess {Number}           items.type.id                        The id of the type.
   * @apiSuccess {String}           items.type.name                      The name of the type.
   * @apiSuccess {String}           items.type.internalname              The internalname of the type.
   * @apiSuccess {String="logical","physical"} items.type.modeling       The model of the type.
   * @apiSuccess {Boolean}          items.type.tree                      Set if the items of this type are
   *    in a tree.
   * @apiSuccess {Boolean}          items.type.allowtreemultipleroots    Set if the items of this type can
   *    have multiple roots.
   * @apiSuccess {Boolean}          items.type.unique_name               Set if the name of items is unique.
   * @apiSuccess {ISO8601}          items.type.created_at                Date of the type creation.
   * @apiSuccess {null|ISO8601}     items.type.updated_at                Date of the last type modification.
   * @apiSuccess {null|ISO8601}     items.type.deleted_at                Date of the soft delete of the type.
   * @apiSuccess {null|Object}      items.type.created_by                User has created the type.
   * @apiSuccess {Number}           items.type.created_by.id             Id of the user has created the type.
   * @apiSuccess {String}           items.type.created_by.name           Name (login) of the user has created the type.
   * @apiSuccess {String}           items.type.created_by.first_name     First name of the user has created the type.
   * @apiSuccess {String}           items.type.created_by.last_name      Last name of the user has created the type.
   * @apiSuccess {null|Object}      items.type.updated_by                User has updated the type.
   * @apiSuccess {Number}           items.type.updated_by.id             Id of the user has updated the type.
   * @apiSuccess {String}           items.type.updated_by.name           Name (login) of the user has updated the type.
   * @apiSuccess {String}           items.type.updated_by.first_name     First name of the user has updated the type.
   * @apiSuccess {String}           items.type.updated_by.last_name      Last name of the user has updated the type.
   * @apiSuccess {null|Object}      items.type.deleted_by                User has soft deleted the type.
   * @apiSuccess {Number}           items.type.deleted_by.id             Id of the user has soft deleted the type.
   * @apiSuccess {String}           items.type.deleted_by.name           Name (login) of the user has soft deleted
   *    the type.
   * @apiSuccess {String}           items.type.deleted_by.first_name     First name of the user has soft deleted
   *    the type.
   * @apiSuccess {String}           items.type.deleted_by.last_name      Last name of the user has soft deleted
   *    the type.
   * @apiSuccess {Object[]}         items.type.properties                The properties list.
   * @apiSuccess {Number}           items.type.properties.id             The id of the property.
   * @apiSuccess {String}           items.type.properties.name           The name of the property.
   * @apiSuccess {String}           items.type.properties.internalname   The internalname of the property.
   * @codingStandardsIgnoreStart because break apidocsjs
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink","itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  items.type.properties.valuetype   The type of value.
   * @codingStandardsIgnoreEnd
   * @apiSuccess {null|String}      items.type.properties.unit           The unit used for the property
   *    (example: Ko, seconds...).
   * @apiSuccess {null|String}      items.type.properties.description    The description of the propery.
   * @apiSuccess {Boolean}          items.type.properties.canbenull      The property can be null or not.
   * @apiSuccess {Boolean}          items.type.properties.setcurrentdate The property in the item can
   *    automatically use the current date when store in DB.
   * @apiSuccess {null|String}      items.type.properties.regexformat    The regexformat to verify the value
   *    is conform (works only with valuetype is string or list).
   * @apiSuccess {null|String[]}    items.type.properties.listvalues     The list of values when
   *    valuetype="list", else null.
   * @apiSuccess {Any}              items.type.properties.default        The default value.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 3,
   *     "name": "Laptop",
   *     "icon": "laptop",
   *     "position": 0,
   *     "menu_id": 1,
   *     "created_at": "2023-01-21T15:09:30.000000Z",
   *     "updated_at": "2023-01-21T15:09:30.000000Z",
   *     "type": {
   *       "id": 3,
   *       "name": "Laptop",
   *       "internalname": "laptop",
   *       "sub_organization": false,
   *       "created_at": "2022-08-11T00:38:57.000000Z",
   *       "updated_at": "022-08-11T10:03:17.000000Z",
   *       "deleted_at": null
   *       "created_by": {
   *         "id": 2,
   *         "name": "admin",
   *         "first_name": "Steve",
   *         "last_name": "Rogers"
   *       },
   *       "updated_by": {
   *         "id": 3,
   *         "name": "tstark",
   *         "first_name": "Tony",
   *         "last_name": "Stark"
   *       },
   *       "deleted_by": null,
   *       "properties": [
   *         {
   *           "id": 6,
   *           "name": "Serial number",
   *           "internalname": "serialnumber",
   *           "valuetype": "string",
   *           "regexformat": null,
   *           "unit": null,
   *           "description": null,
   *           "canbenull": true,
   *           "setcurrentdate": null,
   *           "listvalues": [],
   *           "default": "",
   *         }
   *       ]
   *     }
   *   }
   * ]
   *
   */
  public function routeGetAll(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $menuitem = \App\v1\Models\Display\Menu\Menuitem::query()->orderBy('id')->get();

    $response->getBody()->write($menuitem->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {get} /v1/display/menu/item/:id Get one item of menus
   * @apiName GetDisplayMenuItem
   * @apiGroup Display/Menu/Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Number}           id                       The id of the item.
   * @apiSuccess {String}           name                       The name of the item.
   * @apiSuccess {null|String}      icon                       The icon name of the item.
   * @apiSuccess {Number}           position                 The position of the item in the menu.
   * @apiSuccess {ISO8601}          created_at                    Date of the item creation.
   * @apiSuccess {null|ISO8601}     updated_at                    Date of the last item modification.
   * @apiSuccess {Object}           type                    The type of this item.
   * @apiSuccess {Number}           type.id                            The id of the type.
   * @apiSuccess {String}           type.name                          The name of the type.
   * @apiSuccess {String}           type.internalname                  The internalname of the type.
   * @apiSuccess {String="logical","physical"} type.modeling           The model of the type.
   * @apiSuccess {Boolean}          type.tree                          Set if the items of this type are
   *    in a tree.
   * @apiSuccess {Boolean}          type.allowtreemultipleroots        Set if the items of this type can
   *    have multiple roots.
   * @apiSuccess {Boolean}          type.unique_name                   Set if the name of items is unique.
   * @apiSuccess {ISO8601}          type.created_at                    Date of the type creation.
   * @apiSuccess {null|ISO8601}     type.updated_at                    Date of the last type modification.
   * @apiSuccess {null|ISO8601}     type.deleted_at                    Date of the soft delete of the type.
   * @apiSuccess {null|Object}      type.created_by                    User has created the type.
   * @apiSuccess {Number}           type.created_by.id                 Id of the user has created the type.
   * @apiSuccess {String}           type.created_by.name               Name (login) of the user has created the type.
   * @apiSuccess {String}           type.created_by.first_name         First name of the user has created the type.
   * @apiSuccess {String}           type.created_by.last_name          Last name of the user has created the type.
   * @apiSuccess {null|Object}      type.updated_by                    User has updated the type.
   * @apiSuccess {Number}           type.updated_by.id                 Id of the user has updated the type.
   * @apiSuccess {String}           type.updated_by.name               Name (login) of the user has updated the type.
   * @apiSuccess {String}           type.updated_by.first_name         First name of the user has updated the type.
   * @apiSuccess {String}           type.updated_by.last_name          Last name of the user has updated the type.
   * @apiSuccess {null|Object}      type.deleted_by                    User has soft deleted the type.
   * @apiSuccess {Number}           type.deleted_by.id                 Id of the user has soft deleted the type.
   * @apiSuccess {String}           type.deleted_by.name               Name (login) of the user has soft deleted
   *    the type.
   * @apiSuccess {String}           type.deleted_by.first_name         First name of the user has soft deleted the type.
   * @apiSuccess {String}           type.deleted_by.last_name          Last name of the user has soft deleted the type.
   * @apiSuccess {Object[]}         type.properties                    The properties list.
   * @apiSuccess {Number}           type.properties.id                 The id of the property.
   * @apiSuccess {String}           type.properties.name               The name of the property.
   * @apiSuccess {String}           type.properties.internalname       The internalname of the property.
   * @codingStandardsIgnoreStart because break apidocsjs
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink","itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  type.properties.valuetype   The type of value.
   * @codingStandardsIgnoreEnd
   * @apiSuccess {null|String}      type.properties.unit               The unit used for the property
   *    (example: Ko, seconds...).
   * @apiSuccess {null|String}      type.properties.description        The description of the propery.
   * @apiSuccess {Boolean}          type.properties.canbenull          The property can be null or not.
   * @apiSuccess {Boolean}          type.properties.setcurrentdate     The property in the item can
   *    automatically use the current date when store in DB.
   * @apiSuccess {null|String}      type.properties.regexformat        The regexformat to verify the value
   *    is conform (works only with valuetype is string or list).
   * @apiSuccess {null|String[]}    type.properties.listvalues         The list of values when
   *    valuetype="list", else null.
   * @apiSuccess {Any}              type.properties.default            The default value.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 3,
   *   "name": "Laptop",
   *   "icon": "laptop",
   *   "position": 0,
   *   "menu_id": 1,
   *   "created_at": "2023-01-21T15:09:30.000000Z",
   *   "updated_at": "2023-01-21T15:09:30.000000Z",
   *   "type": {
   *     "id": 3,
   *     "name": "Laptop",
   *     "internalname": "laptop",
   *     "sub_organization": false,
   *     "created_at": "2022-08-11T00:38:57.000000Z",
   *     "updated_at": "022-08-11T10:03:17.000000Z",
   *     "deleted_at": null
   *     "created_by": {
   *       "id": 2,
   *       "name": "admin",
   *       "first_name": "Steve",
   *       "last_name": "Rogers"
   *     },
   *     "updated_by": {
   *       "id": 3,
   *       "name": "tstark",
   *       "first_name": "Tony",
   *       "last_name": "Stark"
   *     },
   *     "deleted_by": null,
   *     "properties": [
   *       {
   *         "id": 6,
   *         "name": "Serial number",
   *         "internalname": "serialnumber",
   *         "valuetype": "string",
   *         "regexformat": null,
   *         "unit": null,
   *         "description": null,
   *         "canbenull": true,
   *         "setcurrentdate": null,
   *         "listvalues": [],
   *         "default": "",
   *       }
   *     ]
   *   }
   * }
   *
   */
  public function routeGetOne(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $menuitem = \App\v1\Models\Display\Menu\Menuitem::query()->find($args['id']);
    if (is_null($menuitem))
    {
      throw new \Exception("This menuitem has not be found", 404);
    }

    $response->getBody()->write($menuitem->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/display/menu/item Create a new item of a menu
   * @apiName PostDisplayMenuItems
   * @apiGroup Display/Menu/Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiBody {String}       name           The name of the menu.
   * @apiBody {Number}       type_id        The id of the type (Config/Type)
   * @apiBody {Number}       menu_id        The id of the menu (Display/Menu)
   * @apiBody {null|String}  [icon]         The icon name of the menu.
   * @apiBody {Number}       [position]     The position of the menu.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "Laptop",
   *   "type_id": 10,
   *   "menu_id": 9
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
      'type_id'          => 'required|type:integer|min:1',
      'menu_id'          => 'required|type:integer|min:1',
      'icon'             => 'type:string',
      'position'         => 'type:integer|min:0'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $properties = ['name', 'icon', 'type_id', 'menu_id', 'position'];
    foreach ($data as $key => $value)
    {
      if (!in_array($key, $properties))
      {
        throw new \Exception("The property $key is not allowed", 400);
      }
    };

    $type = \App\v1\Models\Config\Type::query()->find($data->type_id);
    if (is_null($type))
    {
      throw new \Exception("The type_id is an id than does not exist", 400);
    }
    $menu = \App\v1\Models\Display\Menu\Menu::query()->find($data->menu_id);
    if (is_null($menu))
    {
      throw new \Exception("The menu_id is an id than does not exist", 400);
    }

    $menuitem = new \App\v1\Models\Display\Menu\Menuitem();
    $menuitem->name = $data->name;
    $menuitem->type_id = $data->type_id;
    $menuitem->menu_id = $data->menu_id;

    if (property_exists($data, 'icon'))
    {
      $menuitem->icon = \App\v1\Common::setDisplayIcon($data->icon);
    }
    // get the max position
    $maxItem = \App\v1\Models\Display\Menu\Menuitem::query()->where('menu_id', $data->menu_id)
      ->orderBy('position', 'desc')
      ->first();
    if ($maxItem !== null)
    {
      if (property_exists($data, 'position'))
      {
        if ($data->position > $maxItem->position)
        {
          $menuitem->position = $maxItem->position + 1;
        } else {
          \App\v1\Models\Display\Menu\Menuitem::query()->where('menu_id', $data->menu_id)
            ->where('position', '>=', $data->position)
            ->increment('position', 1);
            $menuitem->position = $data->position;
        }
      } else {
        $menuitem->position = $maxItem->position + 1;
      }
    }
    $menuitem->save();

    $response->getBody()->write(json_encode(["id" => intval($menuitem->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }


  /**
   * @api {patch} /v1/display/menu/item/:id Update an existing menu
   * @apiName PatchDisplayMenuItems
   * @apiGroup Display/Menu/Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the menu.
   *
   * @apiBody {String}  [name]       Name of the menu.
   * @apiBody {null|String} [icon]   Icon name of the menu.
   * @apiBody {Number}  [position]   Position of the menu.
   * @apiBody {Number}  [menu_id]    Id of the menu.
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
   * @apiErrorExample {json} Error-Response:
   * HTTP/1.1 400 Bad Request
   * {
   *   "status: "error",
   *   "message": "The Name is required"
   * }
   *
   */
  public function routePatch(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $data = json_decode($request->getBody());
    $menuitem = \App\v1\Models\Display\Menu\Menuitem::query()->find($args['id']);
    if (is_null($menuitem))
    {
      throw new \Exception("The item has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'name'             => 'type:string',
      'icon'             => 'type:string',
      'position'         => 'type:integer|min:0',
      'menu_id'          => 'type:integer|min:1'
    ];
    \App\v1\Common::validateData($data, $dataFormat);
    $properties = ['name', 'icon', 'position', 'menu_id'];
    foreach ($data as $key => $value)
    {
      if (!in_array($key, $properties))
      {
        throw new \Exception("The property $key is not allowed", 400);
      }
    };

    if (property_exists($data, 'menu_id'))
    {
      $menu = \App\v1\Models\Display\Menu\Menu::query()->find($data->menu_id);
      if (is_null($menu))
      {
        throw new \Exception("The menu_id is an id than does not exist", 400);
      }
    }

    foreach ($properties as $propertyName)
    {
      if (property_exists($data, $propertyName))
      {
        $menuitem->$propertyName = $data->$propertyName;
      }
    }
    // Special case for icon
    if (property_exists($data, 'icon'))
    {
      $menuitem->icon = \App\v1\Common::setDisplayIcon($data->icon);
    }

    \App\v1\Controllers\Log\Audit::addEntry(
      $request,
      'UPDATE',
      '',
      'Display\Menu\Menuitem',
      $menuitem->id
    );
    $menuitem->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/display/menu/item/:id delete a menu
   * @apiName DeleteDisplayMenuItems
   * @apiGroup Display/Menu/Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id        Unique ID of the item.
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

    $menuitem = \App\v1\Models\Display\Menu\Menuitem::query()->find($args['id']);

    if (is_null($menuitem))
    {
      throw new \Exception("The item has not be found", 404);
    }

    // check permissions
    // \App\v1\Permission::checkPermissionToStructure('delete', 'config/property', $property->id);

    $this->deleteItem($menuitem, $request);

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * delete a menu item
   */
  public function deleteItem(\App\v1\Models\Display\Menu\Menuitem $menuitem, ?Request $request)
  {
    if (!is_null($request))
    {
      \App\v1\Controllers\Log\Audit::addEntry(
        $request,
        'DELETE',
        '',
        'Display\Menu\Menuitem',
        $menuitem->id
      );
    }
    $menuitem->forceDelete();

    // ====== Post delete actions ====== //
    // decrease position of others
    \App\v1\Models\Display\Menu\Menuitem::query()->where('menu_id', $menuitem->menu_id)
      ->where('position', '>=', $menuitem->position)
      ->decrement('position', 1);

    // delete menuitemcustom
    $customs = \App\v1\Models\Display\Menu\Menuitemcustom::query()->where('menuitem_id', $menuitem->id)->get();
    foreach ($customs as $custom)
    {
      if (!is_null($request))
      {
        \App\v1\Controllers\Log\Audit::addEntry(
          $request,
          'DELETE',
          '',
          'Display\Menu\Menuitemcustom',
          $custom->id
        );
      }
      $custom->forceDelete();
    }
    // ====== End ====================== //
  }

  /**
   * used when delete a type, do delete the entry in menu
   */
  public static function deleteItemByTypeId($typeId)
  {
    $menuitems = \App\v1\Models\Display\Menu\Menuitem::query()->where('type_id', $typeId)->get();
    $CMenuitem = new self();
    foreach ($menuitems as $menuitem)
    {
      $CMenuitem->deleteItem($menuitem, null);
      // $menuitem->forceDelete();
    }
  }
}
