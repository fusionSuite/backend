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

final class Fusioninventory
{
  private $myInventory;

  public function postRegister(Request $request, Response $response, $args): Response
  {
      $data = json_decode($request->getBody());

      $res = [];
      $payload = json_encode($res);
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }

  public function getConfig(Request $request, Response $response, $args): Response
  {
    // need deviceid

    $res = [
      'modules' => [
        [
          'name'      => 'Inventory',
          'frequency' => 24,
          'endpoints' => [
            'main' => 'localinventory'
          ]
        ],
        [
          'name'      => 'Deploy',
          'frequency' => 2,
          'endpoints'  => [
            'main'      => 'deploy',
            'filepart'  => 'deploy/filepart',
            'userevent' => 'deploy/userevent'
          ]
        ],
        [
          'name'      => 'NetDiscovery',
          'frequency' => 500,
          'endpoints' => [
            'main' => 'networkdiscovery'
          ]
        ],
        [
          'name'      => 'NetInventory',
          'frequency' => 2,
          'endpoints' => [
            'main'          => 'networkinventory',
            'sendInventory' => 'networkinventory'
          ]
        ],
        [
          'name'      => 'Collect',
          'frequency' => 24,
          'endpoints' => [
            'main' => 'collect'
          ]
        ],
        [
          'name'      => 'WMI',
          'frequency' => 24,
          'endpoints' => [
            'main' => 'wmi'
          ]
        ],
        [
          'name'      => 'ESX',
          'frequency' => 24,
          'endpoints' => [
            'main' => 'esx'
          ]
        ],
        [
          'name'      => 'WakeOnLan',
          'frequency' => 24,
          'endpoints' => [
            'main' => 'wakeonlan'
          ]
        ]
      ]
    ];
    $payload = json_encode($res);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }


  public function getLocalinventoryConfiguration(Request $request, Response $response, $args): Response
  {
    $res = [
      'noCategories'  => ['printer'],
      'scanHomedirs' => false,
      'scanProfiles' => false
    ];
    $payload = json_encode($res);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function postLocalinventoryInventory(Request $request, Response $response, $args): Response
  {
    $data = json_decode($request->getBody(), false);
    $this->manageInventory($data);
    $response->getBody()->write("Received, bye!");
    return $response;
  }




  private function manageInventory($data)
  {
    $time_start = microtime(true);

    $this->myInventory = $data;






    //  // Add laptop
    // $item = new \App\v1\Models\Item;
    // $item->name = $data->content->hardware->name;
    // $item->type_id = 2;
    // $item->owner_user_id = 0;
    // $item->owner_group_id = 0;
    // $item->state_id = 0;
    // $item->save();

    // $item->properties()->attach(1, ['value' => $data->content->bios->ssn]);
    // $item->properties()->attach(8, ['value' => $data->content->bios->smodel]);

    /**
     * TODO use rule fusioninventorygettype to define the type of the device
     *
     * criteria field = key.key.key (path)
     *
     *
     */
    $type_id = \App\v1\Controllers\Rules\GetType::runRules($data);
    if (!$type_id)
    {
      return;
    }

    // TODO Rule to get the root element
    $this->manageItem(0, null, $type_id);
    $time_end = microtime(true);
    return ["TIME" => ($time_end - $time_start)];
  }

  private function manageItem($fusioninventoryitem_id, $parent = null, $type_id = null)
  {
    // add, update or delete

    // TODO manage with datamodel in DB
    $where = [
      ['querytype', '=', 'computerinventory'],
      ['fusioninventoryitem_id', '=', $fusioninventoryitem_id],
      ['markup', '!=', 'operatingsystem']
    ];
    if (!is_null($type_id))
    {
      $where[] = ['type_id', '=', $type_id];
    }
    $fusionItems = \App\v1\Models\Fusioninventoryitem::where($where)->with('properties')->get();

    // TODO manage items yet in DB, use rules to define criteria and manage updates


    foreach ($fusionItems as $fusionItem)
    {
      // create item
      // TODO use controller Item post item + properties?
      $path = $this->myInventory->content;
      if ($fusionItem->markup != '')
      {
        if (!isset($this->myInventory->content->{$fusionItem->markup}))
        {
          continue;
        }
        $path = $this->myInventory->content->{$fusionItem->markup};
      }

      [$markups, $modified] = $this->parseMarkups($fusionItem->markup_name, $fusionItem->markup);

      if ($fusionItem->markup == '')
      {
        // It's the device

        // rule searchitem
        $data = [
          'name'            => null,
          'serial'          => null,
          'inventorynumber' => null,
          'macaddress'      => null,
          'uuid'            => null
        ];
        if (isset($path->{'hardware'}) && isset($path->{'hardware'}->{'name'}))
        {
          $data['name'] = $path->{'hardware'}->{'name'};
        }
        if (isset($path->{'bios'}) && isset($path->{'bios'}->{'ssn'}))
        {
          $data['serial'] = $path->{'bios'}->{'ssn'};
        }
        if (isset($path->{'hardware'}) && isset($path->{'hardware'}->{'uuid'}))
        {
          $data['uuid'] = $path->{'hardware'}->{'uuid'};
        }
        $item_id = \App\v1\Controllers\Rules\SearchItem::runRules($data, $type_id);

        if (!$item_id || $item_id == 'notimport')
        {
          return;
        }

        $item = new \App\v1\Models\Item();
        if ($item_id != 'import')
        {
          $item = \App\v1\Models\Item::find($item_id);
        }
        $item->name = $this->getValueWithMarkupName($markups, $path);
        $item->type_id = $fusionItem->type_id;
        $item->owner_user_id = 0;
        $item->owner_group_id = 0;
        $item->state_id = 0;
        $item->byfusioninventory = true;
        $item->save();

        if (!is_null($parent))
        {
          // Add relationship
          $parent->getItems()->attach($item->id);
        }

        // Get current properties
        $itemProperties = [];
        foreach ($item->properties()->get() as $prop)
        {
          $itemProperties[] = $prop->id;
        }
        foreach ($fusionItem->properties as $property)
        {
          // TODO manage when have yet properties, see https://laravel.com/docs/8.x/eloquent-relationships
          // #updating-a-record-on-the-intermediate-table
          [$markupProps, $modifiedProps] = $this->parseMarkups($property->markup, $fusionItem->markup);
          $propPath = null;
          if ($modifiedProps)
          {
            $propPath = $path;
          }
          $value = $this->getValueWithMarkupName($markupProps, $propPath);
          if (!is_null($value))
          {
            if (in_array($property->property_id, $itemProperties))
            {
              $item->properties()->updateExistingPivot($property->property_id, [
                'value'             => $value,
                'byfusioninventory' => true
              ]);
            }
            else {
              $item->properties()->attach($property->property_id, ['value' => $value, 'byfusioninventory' => true]);
            }
          }
        }
        $this->manageItem($fusionItem->id, $item);
      } else {
        $dbItems = [];
        $idx = 1;
        // Get all devices attached to the item with this type
        // foreach ($parent->getItems()->where('type_id', $fusionItem->type_id)->get() as $tt)
        foreach ($parent->getItems->where('type_id', $fusionItem->type_id) as $tt)
        {
          // this line is too slow (toArray())
          // $dbItems[$idx] = $tt->toArray();
          // get the fields wanted is more quickly
          $dbItems[$idx] = [
            "name" => $tt->name,
            "id"   => $tt->id,
          ];
          $idx++;
        }

        $inventoryItems = [];
        $idx = 1;
        foreach ($path as $rootMarkup)
        {
          if (count((array)$rootMarkup) == 0)
          {
            // case have no properties in this markup
            continue;
          }

          // TODO play rules for rewrite / import
          $inventoryItems[$idx] = [
            "name" => $this->getValueWithMarkupName($markups, $rootMarkup),
            "brut" => $rootMarkup
          ];
          $idx++;
        }
        if (empty($inventoryItems) && empty($dbItems))
        {
          continue;
        }
        // get in DB and not in inventory
        // get in inventory and not in DB
        [$toDelete, $toAdd] = $this->filterData($inventoryItems, $dbItems);

        foreach ($toDelete as $data)
        {
          $itemToDel = \App\v1\Models\Item::find($data['id']);
          $itemToDel->delete();
        }

        foreach ($toAdd as $data)
        {
          $rootMarkup = $data["brut"];
          $itemToAdd = new \App\v1\Models\Item();
          $itemToAdd->name = $data['name'];
          $itemToAdd->type_id = $fusionItem->type_id;
          $itemToAdd->owner_user_id = 0;
          $itemToAdd->owner_group_id = 0;
          $itemToAdd->state_id = 0;
          $itemToAdd->byfusioninventory = true;

          // Used only for the rule
          $properties = [];
          foreach ($fusionItem->properties as $property)
          {
            [$markupProps, $modifiedProps] = $this->parseMarkups($property->markup, $fusionItem->markup);
            $propPath = null;
            if ($modifiedProps)
            {
              $propPath = $rootMarkup;
            }
            $value = $this->getValueWithMarkupName($markupProps, $propPath);
            if (!is_null($value))
            {
              $properties[$property->property_id] = $value;
            }
          }

          // Search if item yet in DB
          // $ret = \App\v1\Controllers\Rule::runRules($item, $properties, 'searchitem');
          // if ($ret)
          // {
          //   continue;
          // }
          /**
           * Another method for rule (optimization):
           * get rule criteria for the itemtype
           * get all items relations to this item type (for example CPU, softwares, controllers...)
           * and do the diff / update
           * for 3000 softwares, have only 1 query instead 3000 and will be really more quickly
           *
           * rules possible
           *   * if name / model  => ignore import
           *   * if name == fergrt => rename
           *   * if name + manufacturer in DB => import
           *   * if name in DB => import
           *
           */

          $itemToAdd->save();

          if (!is_null($parent))
          {
            // Add relationship
            $parent->getItems()->attach($itemToAdd->id);
          }

          foreach ($fusionItem->properties as $property)
          {
            [$markupProps, $modifiedProps] = $this->parseMarkups($property->markup, $fusionItem->markup);
            $propPath = null;
            if ($modifiedProps)
            {
              $propPath = $rootMarkup;
            }
            $value = $this->getValueWithMarkupName($markupProps, $propPath);
            if (!is_null($value))
            {
              $itemToAdd->properties()->attach(
                $property->property_id,
                ['value' => $value, 'byfusioninventory' => true]
              );
            }
          }
          // $this->manageItem($fusionItem->id, $item);
        }
      }
    }
  }

  private function getValueWithMarkupName($markups, $path = null)
  {
    if (is_null($path))
    {
      $path = $this->myInventory->content;
    }
    foreach ($markups as $markup)
    {
      if (!property_exists($path, $markup))
      {
        return null;
      }
      $path = $path->{$markup};
    }
    return $path;
  }

  /**
   * Parse markups to get current node properties or another node
   */
  private function parseMarkups($markupName, $currentMarkup)
  {
    $modified = false;
    $markups = explode('/', $markupName);
    if ($markups[0] == $currentMarkup)
    {
      unset($markups[0]);
      $modified = true;
    }
    return [$markups, $modified];
  }


  private function filterData($inventoryItems, $dbItems)
  {
    $toDel = [];
    // Remove all ar in both arrays
    foreach ($inventoryItems as $idx => $item)
    {
      // $key = array_search($item['name'], array_column($dbItems, 'name'));
      $key = array_search($item['name'], array_map(function ($data)
      {
        return $data['name'];
      }, $dbItems));

      if ($key == false)
      {
        continue;
      }
      $toDel[] = $idx;
      // TODO manage diff of fields to know if require update or not

      unset($dbItems[$key]);
    }
    foreach ($toDel as $idx)
    {
      unset($inventoryItems[$idx]);
    }
    // $toDelete = $dbItem;
    // $toAdd = $inventoryItems;

    return [$dbItems, $inventoryItems];
  }
}
