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

namespace App\v1\Controllers\Display\Type;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class Typepanel
{
  use \App\v1\Read;

  /**
   * @api {get} /v1/display/type/:typeid/panels Get all display panels for a type
   * @apiName GetDisplayTypePanels
   * @apiGroup Display/TypePanels
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} typeid    the type unique ID.
   *
   * @apiSuccess {Object[]}         panels                        List of panels.
   * @apiSuccess {Number}           panels.id                     The id of the panel.
   * @apiSuccess {String}           panels.name                   The name of the panel.
   * @apiSuccess {null|String}      panels.icon                   The icon name of the panel.
   * @apiSuccess {Number}           panels.position               The position of the panel.
   * @apiSuccess {String}           panels.displaytype            The type of the panel (default, timeline...).
   * @apiSuccess {Number}           panels.type_id                The id of the type, the panel is attached to a type.
   * @apiSuccess {Object}           panels.items                  The items in the panel.
   * @apiSuccess {Number}           panels.items.id               The id of the item.
   * @apiSuccess {Number}           panels.items.position         The position of the item in the panel.
   * @apiSuccess {Number}           panels.items.property_id      The property id defined in this item.
   * @apiSuccess {Number}           panels.items.typepanel_id     The id of the panel.
   * @apiSuccess {String}           panels.items.timeline_message The message of the timeline.
   * @apiSuccess {String}           panels.items.timeline_options The options of the timeline.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 3,
   *     "name": "Default",
   *     "icon": null,
   *     "position": 0,
   *     "displaytype": "default",
   *     "type_id": 3,
   *     "items": [
   *       {
   *         "id": 10,
   *         "position": 0,
   *         "property_id": 10,
   *         "typepanel_id": 3,
   *         "timeline_message": null,
   *         "timeline_options": "[]"
   *       }
   *     ]
   *   }
   * ]
   *
   */
  public function routeGetAllOfType(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $typepanel = \App\v1\Models\Display\Type\Typepanel::query()->where('type_id', $args['typeId'])
      ->with('items')
      ->orderBy('id')
      ->get();

    $response->getBody()->write($typepanel->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {get} /v1/display/type/panels/:panelId Get one panel
   * @apiName GetDisplayTypePanel
   * @apiGroup Display/TypePanels
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} panelId   the panel unique ID.
   *
   * @apiSuccess {Number}           id                     The id of the panel.
   * @apiSuccess {String}           name                   The name of the panel.
   * @apiSuccess {null|String}      icon                   The icon name of the panel.
   * @apiSuccess {Number}           position               The position of the panel.
   * @apiSuccess {String}           displaytype            The type of the panel (default, timeline...).
   * @apiSuccess {Number}           type_id                The id of the type, the panel is attached to a type.
   * @apiSuccess {Object}           items                  The items in the panel.
   * @apiSuccess {Number}           items.id               The id of the item.
   * @apiSuccess {Number}           items.position         The position of the item in the panel.
   * @apiSuccess {Number}           items.property_id      The property id defined in this item.
   * @apiSuccess {Number}           items.typepanel_id     The id of the panel.
   * @apiSuccess {String}           items.timeline_message The message of the timeline.
   * @apiSuccess {String}           items.timeline_options The options of the timeline.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 3,
   *   "name": "Default",
   *   "icon": null,
   *   "position": 0,
   *   "displaytype": "default",
   *   "type_id": 3,
   *   "items": [
   *     {
   *       "id": 10,
   *       "position": 0,
   *       "property_id": 10,
   *       "typepanel_id": 3,
   *       "timeline_message": null,
   *       "timeline_options": "[]"
   *     }
   *   ]
   * }
   *
   */
  public function routeGetOne(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $typepanel = \App\v1\Models\Display\Type\Typepanel::query()->find($args['panelId']);
    if (is_null($typepanel))
    {
      throw new \Exception("This panel has not be found", 404);
    }

    $response->getBody()->write($typepanel->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/display/type/panels Create a new panel
   * @apiName PostDisplayTypePanel
   * @apiGroup Display/TypePanels
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiBody {String}       name                   The name of the panel.
   * @apiBody {Number}       type_id                the id of the type the panel is attached.
   * @apiBody {null|String}  [icon]                 The icon name of the panel.
   * @apiBody {Number}       [position]             The position of the panel.
   * @apiBody {Number}       [displaytype=default]  The type of panel display.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "main attributes",
   *   "type_id": 3
   * }
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id":54
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
      'name'             => 'required|type:string|minchars:2|maxchars:255',
      'type_id'          => 'required|type:integer|integer',
      'icon'             => 'type:string|minchars:2|maxchars:255',
      'position'         => 'type:integer|regex:/^[0-9]+$/',
      'displaytype'      => 'type:string|minchars:2|maxchars:255'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    // Checks the type exists
    $type = \App\v1\Models\Config\Type::query()->find($data->type_id);
    if (is_null($type))
    {
      throw new \Exception("The type has not be found", 404);
    }

    $typepanel = new \App\v1\Models\Display\Type\Typepanel();
    $typepanel->name = $data->name;
    $typepanel->type_id = $data->type_id;
    if (property_exists($data, 'icon'))
    {
      $typepanel->icon = \App\v1\Common::setDisplayIcon($data->icon);
    }
    // get the max position
    $maxPanel = \App\v1\Models\Display\Type\Typepanel::query()->where('type_id', $data->type_id)
      ->orderBy('position', 'desc')
      ->first();
    if ($maxPanel !== null)
    {
      if (property_exists($data, 'position'))
      {
        if ($data->position > $maxPanel->position)
        {
          $typepanel->position = $maxPanel->position + 1;
        } else {
          \App\v1\Models\Display\Type\Typepanel::query()->where('type_id', $data->type_id)
            ->where('position', '>=', $data->position)
            ->increment('position', 1);
            $typepanel->position = $data->position;
        }
      } else {
        $typepanel->position = $maxPanel->position + 1;
      }
    }
    if (property_exists($data, 'displaytype') and $data->displaytype == 'timeline')
    {
      $typepanel->displaytype = 'timeline';
    }
    $typepanel->save();

    $response->getBody()->write(json_encode(["id" => intval($typepanel->id)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {patch} /v1/display/type/panels/:panelId Update an existing panel
   * @apiName PatchDisplayTypePanel
   * @apiGroup Display/TypePanels
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} panelId   the panel unique ID.
   *
   * @apiBody {String}  [name]       Name of the panel.
   * @apiBody {null|String} [icon]   Icon name of the panel.
   * @apiBody {Number}  [position]   Position of the panel.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "Main attributes",
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
    $typepanel = \App\v1\Models\Display\Type\Typepanel::query()->find($args['panelId']);
    if (is_null($typepanel))
    {
      throw new \Exception("The panel has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'name'             => 'type:string|minchars:2|maxchars:255',
      'icon'             => 'type:string|minchars:2|maxchars:255',
      'position'         => 'type:integer|regex:/^[0-9]+$/'
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
    if ($typepanel->name == 'Default' && property_exists($data, 'name'))
    {
      throw new \Exception("Rename Default panel is not allowed", 400);
    }

    foreach ($properties as $propertyName)
    {
      $typepanel->$propertyName = $data->$propertyName;
    }
    // Special case for icon
    if (property_exists($data, 'icon'))
    {
      $typepanel->icon = \App\v1\Common::setDisplayIcon($data->icon);
    }

    \App\v1\Controllers\Log\Audit::addEntry(
      $request,
      'UPDATE',
      '',
      'Display\Type\Typepanel',
      $typepanel->id
    );
    $typepanel->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/display/type/panels/:panelid delete a panel
   * @apiName DeleteDisplayTypePanel
   * @apiGroup Display/TypePanels
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} panelId   the panel unique ID.
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

    $typepanel = \App\v1\Models\Display\Type\Typepanel::query()->find($args['panelId']);

    if (is_null($typepanel))
    {
      throw new \Exception("The panel has not be found", 404);
    }
    if ($typepanel->name == 'Default')
    {
      throw new \Exception("Delete the default panel is not allowed", 400);
    }

    // check permissions
    // \App\v1\Permission::checkPermissionToStructure('delete', 'config/property', $property->id);

    // Transfert all panelitems to default
    $typepanelitems = \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $args['panelId'])->get();
    foreach ($typepanelitems as $typepanelitem)
    {
      \App\v1\Controllers\Display\Type\Typepanelitem::
        transferPanelitemToDefaultPanel($typepanelitem->id, $typepanel->type_id);
    }

    \App\v1\Controllers\Log\Audit::addEntry(
      $request,
      'DELETE',
      '',
      'Display\Type\Typepanel',
      $typepanel->id
    );
    $typepanel->forceDelete();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {get} /v1/display/type/panels/:panelId/panelitems Get all panelitems of a panel
   * @apiName GetDisplayTypePanelItems
   * @apiGroup Display/TypePanels
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} panelId   the panel unique ID.
   *
   * @apiSuccess {Object[]}    items                    List of items.
   * @apiSuccess {Number}      items.id                 The id of the item.
   * @apiSuccess {Number}      items.position           The position of the item in the panel.
   * @apiSuccess {Number}      items.property_id        The property id defined in this item.
   * @apiSuccess {Number}      items.typepanel_id       The id of the panel.
   * @apiSuccess {String}      items.timeline_message   The message of the timeline.
   * @apiSuccess {String}      items.timeline_options   The options of the timeline.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 10,
   *     "position": 0,
   *     "property_id": 10,
   *     "typepanel_id": 3,
   *     "timeline_message": null,
   *     "timeline_options": "[]"
   *   }
   * ]
   *
   */
  public function routeGetAllOfPanel(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $typepanelitem = \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $args['panelId'])
      ->orderBy('id')->get();

    $response->getBody()->write($typepanelitem->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * Delete all panels when delete type
   */
  public static function deleteAllPanels($typeId)
  {
    // loop panels of the type
    $panels = \App\v1\Models\Display\Type\Typepanel::query()->where('type_id', $typeId)->get();
    foreach ($panels as $panel)
    {
      // loop panelitems
      $panelitems = \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $panel->id);
      foreach ($panelitems as $panelitem)
      {
        // delete panel items
        \App\v1\Controllers\Display\Type\Typepanelitem::deletePanelItem($panelitem->property_id, $typeId);
      }
      // delete panel
      $panel->delete();
    }
  }
}
