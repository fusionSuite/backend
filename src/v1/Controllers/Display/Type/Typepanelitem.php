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

final class Typepanelitem
{
  use \App\v1\Read;

  /**
   * @api {get} /v1/display/type/panelitems/:panelitemId Get an item of a panel
   * @apiName GetDisplayTypePanelItem
   * @apiGroup Display/TypePanel/Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} panelitemId    the item unique ID.
   *
   * @apiSuccess {Number}      id                 The id of the item.
   * @apiSuccess {Number}      position           The position of the item in the panel.
   * @apiSuccess {Number}      property_id        The property id defined in this item.
   * @apiSuccess {Number}      typepanel_id       The id of the panel.
   * @apiSuccess {String}      timeline_message   The message of the timeline.
   * @apiSuccess {String}      timeline_options   The options of the timeline.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 10,
   *   "position": 0,
   *   "property_id": 10,
   *   "typepanel_id": 3,
   *   "timeline_message": null,
   *   "timeline_options": "[]"
   * }
   *
   */
  public function routeGetOne(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $typepanelitem = \App\v1\Models\Display\Type\Typepanelitem::query()->find($args['panelitemId']);
    if (is_null($typepanelitem))
    {
      throw new \Exception("This panel item has not be found", 404);
    }

    $response->getBody()->write($typepanelitem->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {patch} /v1/display/type/panelitems/:panelitemId Update an item of a panel
   * @apiName PatchDisplayTypePanelItem
   * @apiGroup Display/TypePanel/Items
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number} panelitemId  the panel item unique ID.
   *
   * @apiBody {Number}      [position]           The position of the item in the panel.
   * @apiBody {Number}      [typepanel_id]       The id of the panel (used to move from panel to another).
   * @apiBody {String}      [timeline_options]   The options of the timeline.

   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "position": 4,
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
    $args['panelitemId'] = intval($args['panelitemId']);

    $data = json_decode($request->getBody());
    $typepanelitem = \App\v1\Models\Display\Type\Typepanelitem::query()->find($args['panelitemId']);
    if (is_null($typepanelitem))
    {
      throw new \Exception("The panel item has not be found", 404);
    }
    $oldPosition = null;
    $oldPosition = $typepanelitem->position;

    // Validate the data format
    $dataFormat = [
      'position'         => 'type:integer|regex:/^[0-9]+$/',
      'typepanel_id'     => 'type:integer|regex:/^[0-9]+$/',
      'timeline_options' => 'array',
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    if (property_exists($data, 'typepanel_id'))
    {
      $typepanel = \App\v1\Models\Display\Type\Typepanel::query()->find($data->typepanel_id);
      if (is_null($typepanel))
      {
        throw new \Exception("The typepanel not exists", 404);
      }
    }

    if (
        property_exists($data, 'typepanel_id')
        && $data->typepanel_id != $typepanelitem->typepanel_id
    )
    {
      $this->patchTransferTypepanel($typepanelitem, $data);
    } else {
      $this->patchWithoutTranferTypepanel($typepanelitem, $data);
    }

    \App\v1\Controllers\Log\Audit::addEntry(
      $request,
      'UPDATE',
      '',
      'Display\Type\Typepanelitem',
      $typepanelitem->id
    );
    $typepanelitem->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function createPanelitem($propertyId, $typeId, $typepanelName)
  {
    $typepanel = \App\v1\Models\Display\Type\Typepanel::query()->where('type_id', $typeId)
      ->where('name', $typepanelName)
      ->first();
    $typepanelitem = new \App\v1\Models\Display\Type\Typepanelitem();
    $typepanelitem->property_id = $propertyId;
    $typepanelitem->typepanel_id = $typepanel->id;
    // get the max position
    $maxPanelitem = \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $typepanel->id)
      ->orderBy('position', 'desc')
      ->first();
    if ($maxPanelitem !== null)
    {
      $typepanelitem->position = $maxPanelitem->position + 1;
    }
    $typepanelitem->save();
  }

  public static function deletePanelItem($propertyId, $typeId)
  {
    $typepanels = \App\v1\Models\Display\Type\Typepanel::query()->where('type_id', $typeId)
      ->get();
    foreach ($typepanels as $typepanel)
    {
      $typepanelitem = \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $typepanel->id)
        ->where('property_id', $propertyId)
        ->first();
      if (!is_null($typepanelitem))
      {
        $typepanelitem->delete();
        \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $typepanel->id)
          ->where('position', '>=', $typepanelitem->position)
          ->decrement('position', 1);
      }
    }
  }

  public static function transferPanelitemToDefaultPanel($typepanelitemId, $typeId)
  {
    $typepanelitem = \App\v1\Models\Display\Type\Typepanelitem::query()->find($typepanelitemId);
    $typepanel = \App\v1\Models\Display\Type\Typepanel::query()->where('type_id', $typeId)
      ->where('name', 'Default')
      ->first();
    if (!is_null($typepanel))
    {
      $typepanelitem->typepanel_id = $typepanel->id;
      $maxItem = \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $typepanel->id)
        ->orderBy('position', 'desc')
        ->first();
      if ($maxItem !== null)
      {
        $typepanelitem->position = $maxItem->position + 1;
      }
      $typepanelitem->save();
    }
  }

  /**
   * Manage the panelitem patch with typepanel transfer
   */
  private function patchTransferTypepanel(&$typepanelitem, $data)
  {
    $oldTypepanelId = $typepanelitem->typepanel_id;
    $oldPosition = $typepanelitem->position;

    $typepanelitem->typepanel_id = $data->typepanel_id;
    // get the max position in new panel
    $maxItem = \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $typepanelitem->typepanel_id)
      ->orderBy('position', 'desc')
      ->first();
    if ($maxItem !== null)
    {
      if (property_exists($data, 'position'))
      {
        if ($data->position > $maxItem->position)
        {
          $typepanelitem->position = $maxItem->position + 1;
        } else {
          $typepanelitem->position = $data->position;
          // Change position of other panelitems in new typepanel
          $model = \App\v1\Models\Display\Type\Typepanelitem::query()
            ->where('typepanel_id', $typepanelitem->typepanel_id);
          \App\v1\Common::changePosition($maxItem->position, $typepanelitem->position, $model);
        }
      } else {
        $typepanelitem->position = $maxItem->position + 1;
      }
    }
    // Change position too in old typepanel
    $maxItem = \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $oldTypepanelId)
      ->orderBy('position', 'desc')
      ->first();
    $model = \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $oldTypepanelId);
    \App\v1\Common::changePosition($oldPosition, $maxItem->position, $model);
  }

  /**
   * Manage the panelitem patch without typepanel transfer
   */
  private function patchWithoutTranferTypepanel(&$typepanelitem, $data)
  {
    $oldPosition = $typepanelitem->position;
    if (property_exists($data, 'position')) {
      $typepanelitem->position = $data->position;
    }
    // get the max position
    $maxItem = \App\v1\Models\Display\Type\Typepanelitem::query()->where('typepanel_id', $typepanelitem->typepanel_id)
      ->orderBy('position', 'desc')
      ->first();
    if ($maxItem !== null)
    {
      if (property_exists($data, 'position'))
      {
        if ($data->position > $maxItem->position)
        {
          $typepanelitem->position = $maxItem->position + 1;
        } else {
          $typepanelitem->position = $data->position;
          // Change position of other panelitemns
          $model = \App\v1\Models\Display\Type\Typepanelitem::query()
            ->where('typepanel_id', $typepanelitem->typepanel_id);
          \App\v1\Common::changePosition($oldPosition, $typepanelitem->position, $model);
        }
      } else {
        $typepanelitem->position = $maxItem->position + 1;
      }
    }
  }
}
