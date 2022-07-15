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

namespace App\v1\Controllers\Config;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class Property
{
  /**
   * @api {get} /v1/config/properties Get all properties
   * @apiName GetConfigProperties
   * @apiGroup Config/Properties
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiSuccess {Object[]}       properties                    List of properties.
   * @apiSuccess {Number}         properties.id                 The id of the property.
   * @apiSuccess {String}         properties.name               The name of the property.
   * @apiSuccess {String}         properties.internalname       The internalname of the property.
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink",
   *    "itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}   properties.valuetype
   *    The type of value.
   * @apiSuccess {null|String}    properties.unit               The unit used for the property (example: Ko,
   *    seconds...).
   * @apiSuccess {null|String}    properties.description        The description of the propery.
   * @apiSuccess {ISO8601}        properties.created_at         Date of the item creation.
   * @apiSuccess {null|ISO8601}   properties.updated_at         Date of the last item modification.
   * @apiSuccess {Boolean}        properties.canbenull          The property can be null or not.
   * @apiSuccess {Boolean}        properties.setcurrentdate     The property in the item can automatically use the
   *    current date when store in DB.
   * @apiSuccess {null|String}    properties.regexformat        The regexformat to verify the value is conform
   *    (works only with valuetype is string or list).
   * @apiSuccess {Object[]}       properties.listvalues         The list of values when valuetype="list".
   * @apiSuccess {Number}         properties.listvalues.id      The id of the value.
   * @apiSuccess {String}         properties.listvalues.value   The value.
   * @apiSuccess {Any}            properties.default            The default value.
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 8,
   *     "name": "Serial Number",
   *     "internalname": "serialnumber",
   *     "valuetype": "string",
   *     "listvalues": [],
   *     "unit": null,
   *     "default": "",
   *     "description": "Enter the serial number of the item",
   *     "created_at": "2020-07-21 09:21:52",
   *     "updated_at": null
   *   },
   *   {
   *     "id": 9,
   *     "name": "Model",
   *     "internalname": "model",
   *     "valuetype": "list",
   *     "listvalues": [
   *       {
   *         "id": 1,
   *         "value": "Latitude E7470"
   *       },
   *       {
   *         "id": 1,
   *         "value": "Latitude E7490"
   *       },
   *       {
   *         "id": 1,
   *         "value": "Latitude E9510"
   *       },
   *       {
   *         "id": 1,
   *         "value": "P43s"
   *       },
   *     ],
   *     "unit": null,
   *     "default": "",
   *     "created_at": "2020-07-21 09:31:30",
   *     "updated_at": null
   *   }
   * ]
   *
   */
  public function getAll(Request $request, Response $response, $args): Response
  {
    $items = \App\v1\Models\Config\Property::all()->makeHidden(['value', 'byfusioninventory']);
    $response->getBody()->write($items->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {get} /v1/config/properties/:id Get one properties
   * @apiName GetConfigProperty
   * @apiGroup Config/Properties
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}           id                 The Property unique ID.
   *
   * @apiSuccess {Number}         id                 The id of the property.
   * @apiSuccess {String}         name               The name of the property.
   * @apiSuccess {String}         internalname       The internalname of the property.
   * @apiSuccess {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink",
   *    "itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}   valuetype
   *    The type of value.
   * @apiSuccess {null|String}    unit               The unit used for the property (example: Ko,
   *    seconds...).
   * @apiSuccess {null|String}    description        The description of the propery.
   * @apiSuccess {ISO8601}        created_at         Date of the item creation.
   * @apiSuccess {null|ISO8601}   updated_at         Date of the last item modification.
   * @apiSuccess {Boolean}        canbenull          The property can be null or not.
   * @apiSuccess {Boolean}        setcurrentdate     The property in the item can automatically use the current date
   *    when store in DB.
   * @apiSuccess {null|String}    regexformat        The regexformat to verify the value is conform (works only with
   *    valuetype is string or list).
   * @apiSuccess {Object[]}       listvalues         The list of values when valuetype="list".
   * @apiSuccess {Number}         listvalues.id      The id of the value.
   * @apiSuccess {String}         listvalues.value   The value.
   * @apiSuccess {Any}            default            The default value.
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 8,
   *   "name": "Serial Number",
   *   "internalname": "serialnumber",
   *   "valuetype": "string",
   *   "listvalues": [
   *     {
   *       "id": 1,
   *       "value": "Latitude E7470"
   *     }
   *   ],
   *   "unit": null,
   *   "default": "",
   *   "description": "Enter the serial number of the item",
   *   "created_at": "2020-07-21 09:21:52",
   *   "updated_at": null,
   *   "canbenull": false,
   *   "setcurrentdate": false,
   *   "regexformat": null
   * }
   *
   */
  public function getOne(Request $request, Response $response, $args): Response
  {
    $item = \App\v1\Models\Config\Property::find($args['id'])->makeHidden(['value', 'byfusioninventory']);
    if (is_null($item))
    {
      throw new \Exception("This item has not be found", 404);
    }
    $response->getBody()->write($item->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/config/properties Create a property
   * @apiName PostConfigProperties
   * @apiGroup Config/Properties
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiBody {String{2..255}}       name            The name of the type of items.
   * @apiBody {null|String{2..255}}  [internalname]  The internal name of the property (only lowercase a to z and .),
   *    if not defined, be generated with name.
   * @apiBody {String="string","integer","decimal","text","boolean","datetime","date","time","number","itemlink",
   *    "itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}  valuetype
   *    The type of value.
   * @apiBody {null|String{..255}}   [regexformat]     The regexformat to verify the value is conform (works only with
   *    valuetype is string or list).
   * @apiBody {null|String[}         listvalues      The list of values in case of valuetype is 'list'.
   * @apiBody {null|Any}             default         The default value.
   * @apiBody {Boolean=false}        [setcurrentdate]  Define (for date, time and datetime valuetype only) the property
   *    in item will use current.
   * @apiBody {Boolean=true}         [canbenull]       Define if the value in the item can be null or not.
   * @apiBody {null|String{..255}}   [unit]          The unit.
   * @apiBody {null|String}          [description]   The description of the property.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "name": "Serial number",
   *   "valuetype": "string",
   *   "listvalues": null,
   *   "default": null
   * }
   *
   * @apiSuccess {Number}  id   The id of the property.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id":10
   * }
   *
   */
  public function postItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());

    // Validate the data format
    $dataFormat = [
      'name'           => $this->validateNameAttribute(['required']),
      'internalname'   => $this->validateInternalnameAttribute(),
      'valuetype'      => $this->validateValuetypeAttribute(['required']),
      'regexformat'    => $this->validateRegexformatAttribute(),
      'listvalues'     => $this->validateListvaluesAttribute(['present']),
      'default'        => $this->validateDefaultAttribute(['present']),
      'setcurrentdate' => $this->validateSetcurrentdateAttribute([]),
      'canbenull'      => $this->validateCanbenullAttribute([]),
      'unit'           => $this->validateUnitAttribute(),
      'description'    => $this->validateDescriptionAttribute(),
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $propId = $this->createProperty($data);

    $response->getBody()->write(json_encode(["id" => intval($propId)]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {patch} /v1/config/properties/:id Update an existing type of items
   * @apiName PatchConfigProperties
   * @apiGroup Config/Properties
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}         id              Unique ID of the type.
   *
   * @apiBody {String{2..255}}      [name]          Name of the type.
   * @apiBody {null|String{..255}}  [regexformat]    The regexformat to verify the value is conform (works only with
   *    valuetype is string or list).
   * @apiBody {null|Any}            [default]        The default value.
   * @apiBody {Boolean}             [setcurrentdate] Define (for date, time and datetime valuetype only) the property
   *    in item will use current.
   * @apiBody {Boolean}             [canbenull]      Define if the value in the item can be null or not.
   * @apiBody {null|String{..255}}  [unit]           The unit.
   * @apiBody {null|String}         [description]    The description of the property.
   *
   */
  public function patchItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());
    $property = \App\v1\Models\Config\Property::find($args['id']);

    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'name'           => $this->validateNameAttribute(),
      'regexformat'    => $this->validateRegexformatAttribute(),
      'setcurrentdate' => $this->validateSetcurrentdateAttribute(),
      'canbenull'      => $this->validateCanbenullAttribute(),
      'unit'           => $this->validateUnitAttribute(),
      'description'    => $this->validateDescriptionAttribute()
    ];
    \App\v1\Common::validateData($data, $dataFormat);
    foreach ($data as $key => $value)
    {
      if (!in_array($key, ['name', 'regexformat', 'default', 'setcurrentdate', 'canbenull', 'unit', 'description']))
      {
        throw new \Exception("The property $key is not allowed", 400);
      }
    };
    if (property_exists($data, 'default'))
    {
      $data->valuetype = $property->valuetype;
      $this->validateDefaultForSaveMethods($data);
    }

    $properties = $this->fillArrayForSaveMethods($data, false);
    foreach ($properties as $propertyName => $propertyValue)
    {
      $property->$propertyName = $propertyValue;
    }

    $property->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/config/properties/:id delete an item
   * @apiName DeleteConfigProperty
   * @apiGroup Config/Properties
   * @apiVersion 1.0.0-draft
   * @apiDescription Delete the propertytype, works only if not used
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
  public function deleteItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $property = \App\v1\Models\Config\Property::find($args['id']);

    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }
    $propertyId = $args['id'];
    // Check this property is not used, else put an error and list of types id use it
    $types = \App\v1\Models\Config\Type::whereHas('properties', function ($q) use ($propertyId)
    {
      $q->where('property_id', $propertyId);
    })->get();
    $typesId = [];
    foreach ($types as $type)
    {
      $typesId[] = $type->id;
    }
    if (count($typesId))
    {
      throw new \Exception("This property used in types: " . implode(',', $typesId), 403);
    }

    $property->delete();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }


  /********************
   * Private functions
   ********************/

  public function createProperty($data)
  {
    $this->validateDefaultForSaveMethods($data);
    $properties = $this->fillArrayForSaveMethods($data);

    $property = new \App\v1\Models\Config\Property();
    foreach ($properties as $propertyName => $propertyValue)
    {
      $property->$propertyName = $propertyValue;
    }
    $property->save();

    // add list valies in the right model
    if (
        $data->valuetype == 'list'
        && \App\v1\Post::postHasProperties($data, ['listvalues']) === true
        && count($data->listvalues) > 0
    )
    {
      foreach ($data->listvalues as $value)
      {
        $propertylist = new \App\v1\Models\Config\Propertylist();
        $propertylist->property_id = $property->id;
        $propertylist->value = $value;
        $propertylist->save();
        if ($data->default == $value)
        {
          $property->fresh();
          $property->{'default_' . $property->valuetype} = $propertylist->id;
          $property->save();
        }
      }
    }
    if ($data->valuetype == 'typelinks' && !is_null($data->default))
    {
      foreach ($data->default as $typeId)
      {
        $propertytypelink = new \App\v1\Models\Config\Propertytypelink();
        $propertytypelink->property_id = $property->id;
        $propertytypelink->type_id = $typeId;
        $propertytypelink->save();
      }
    }
    return $property->id;
  }

  /**
   * Validate the incoming data format for save methods
   */
  private function validateDefaultForSaveMethods($data, $presentFields = null)
  {
    // Validate the data format of default value
    $dataFormat = [];
    switch ($data->valuetype)
    {
      case 'string':
      case 'password':
      case 'passwordhash':
        if (is_null($data->default))
        {
          $dataFormat['default'] = 'nullable';
        }
        else
        {
          $dataFormat['default'] = 'type:string|maxchars:255';
        }
          break;

      case 'text':
        $dataFormat['default'] = 'type:string';
          break;

      case 'integer':
        $dataFormat['default'] = 'type:integer';
          break;

      case 'number':
        $dataFormat['default'] = 'type:integer|regex:/^[0-9]+$/';
          break;

      case 'decimal':
        $dataFormat['default'] = 'type:double';
          break;

      case 'date':
        if (is_null($data->default))
        {
          $dataFormat['default'] = 'nullable';
        }
        elseif ($data->default == '')
        {
          $data->default = null;
          $data->setcurrentdate = true;
          $dataFormat['default'] = 'nullable';
        }
        else
        {
          $dataFormat['default'] = 'type:string|dateformat';
        }
          break;

      case 'datetime':
        if (is_null($data->default))
        {
          $dataFormat['default'] = 'nullable';
        }
        elseif ($data->default == '')
        {
          $data->default = null;
          $data->setcurrentdate = true;
          $dataFormat['default'] = 'nullable';
        }
        else
        {
          $dataFormat['default'] = 'type:string|datetimeformat';
        }
          break;

      case 'time':
        if (is_null($data->default))
        {
          $dataFormat['default'] = 'nullable';
        }
        elseif ($data->default == '')
        {
          $data->default = null;
          $data->setcurrentdate = true;
          $dataFormat['default'] = 'nullable';
        }
        else
        {
          $dataFormat['default'] = 'type:string|timeformat';
        }
          break;

      case 'list':
        $dataFormat['default'] = 'type:string';
        if (is_null($data->default))
        {
          $dataFormat['default'] = 'nullable';
        }
        $dataFormat['listvalues'] = 'array';
          break;

      case 'boolean':
        $dataFormat['default'] = 'type:boolean';
          break;

      case 'itemlink':
      case 'typelink':
      case 'propertylink':
        $dataFormat['default'] = 'type:integer|regex:/^[0-9]+$/';
          break;

      case 'itemlinks':
      case 'typelinks':
        $dataFormat['default'] = 'type:array';
          break;
    }
    \App\v1\Common::validateData($data, $dataFormat);

    if (property_exists($data, 'default') && !is_null($data->default))
    {
      // check if the item id exists
      if ($data->valuetype == 'itemlink')
      {
        $item = \App\v1\Models\Item::find($data->default);
        if (is_null($item))
        {
          throw new \Exception("The Default item does not exist", 400);
        }
      }
      if ($data->valuetype == 'itemlinks')
      {
        foreach ($data->default as $itemId)
        {
          $item = \App\v1\Models\Item::find($itemId);
          if (is_null($item))
          {
            throw new \Exception("The Default item does not exist", 400);
          }
        }
      }
      // check if the type id exists
      if ($data->valuetype == 'typelink')
      {
        $item = \App\v1\Models\Config\Type::find($data->default);
        if (is_null($item))
        {
          throw new \Exception("The Default type does not exist", 400);
        }
      }
      if ($data->valuetype == 'typelinks')
      {
        foreach ($data->default as $typeId)
        {
          $item = \App\v1\Models\Config\Type::find($typeId);
          if (is_null($item))
          {
            throw new \Exception("The Default type does not exist", 400);
          }
        }
      }
      // check if the property id exists
      if ($data->valuetype == 'propertylink')
      {
        $item = \App\v1\Models\Config\Property::find($data->default);
        if (is_null($item))
        {
          throw new \Exception("The Default property id does not exist", 400);
        }
      }
      if ($data->valuetype == 'list')
      {
        if (!in_array($data->default, $data->listvalues))
        {
          throw new \Exception("The Default property does not exist in listvalues", 400);
        }
      }
    }
  }

  /**
   * Prepare an array with properties to save in the database
   */
  private function fillArrayForSaveMethods($data, $generateInternalname = true)
  {
    $properties = [];
    if (property_exists($data, 'name'))
    {
      $properties['name'] = $data->name;
    }
    if (property_exists($data, 'valuetype'))
    {
      $properties['valuetype'] = $data->valuetype;
    }
    if (\App\v1\Post::postHasProperties($data, ['regexformat']) === true)
    {
      $properties['regexformat'] = $data->regexformat;
    }
    if (\App\v1\Post::postHasProperties($data, ['unit']) === true)
    {
      $properties['unit'] = $data->unit;
    }
    if ($generateInternalname)
    {
      if (property_exists($data, 'internalname') === false)
      {
        $properties['internalname'] = preg_replace("/[^a-z.]+/", "", strtolower($data->name));
      }
      else
      {
        $properties['internalname'] = $data->internalname;
      }
    }
    if (property_exists($data, 'default'))
    {
      $properties['default_' . $properties['valuetype']] = $data->default;
      if (
          in_array($properties['valuetype'], ['itemlinks', 'typelinks'])
          && !is_null($data->default)
      )
      {
        $properties['default_' . $properties['valuetype']] = '';
      }
      if (
          $properties['valuetype'] == 'list'
          && !is_null($data->default)
      )
      {
        $properties['default_' . $properties['valuetype']] = 0;
      }
    }
    if (\App\v1\Post::postHasProperties($data, ['description']) === true)
    {
      $properties['description'] = $data->description;
    }
    if (property_exists($data, 'canbenull') && !$data->canbenull)
    {
      $properties['canbenull'] = false;
      if (property_exists($data, 'default') && is_null($data->default))
      {
        throw new \Exception('The Default can\'t be null', 400);
      }
    }
    if (property_exists($data, 'setcurrentdate'))
    {
      $properties['setcurrentdate'] = $data->setcurrentdate;
    }
    return $properties;
  }

  private function validateNameAttribute($values = [])
  {
    $values[] = 'type:string';
    $values[] = 'minchars:2';
    $values[] = 'maxchars:255';
    return implode('|', $values);
  }

  private function validateInternalnameAttribute($values = [])
  {
    $values[] = 'type:string';
    $values[] = 'regex:/^[a-z.]+$/';
    $values[] = 'minchars:2';
    $values[] = 'maxchars:255';
    return implode('|', $values);
  }

  private function validateValuetypeAttribute($values = [])
  {
    $values[] = 'type:string';
    $values[] = 'in:string,integer,decimal,text,boolean,datetime,date,time,number,itemlink,itemlinks,typelink,' .
                'typelinks,propertylink,list,password,passwordhash';
    return implode('|', $values);
  }

  private function validateRegexformatAttribute($values = [])
  {
    $values[] = 'type:string';
    $values[] = 'maxchars:255';
    return implode('|', $values);
  }

  private function validateListvaluesAttribute($values = [])
  {
    $values[] = 'type:array';
    $values[] = 'array';
    return implode('|', $values);
  }

  private function validateDefaultAttribute($values = [])
  {
    return implode('|', $values);
  }

  private function validateSetcurrentdateAttribute($values = [])
  {
    $values[] = 'type:boolean';
    return implode('|', $values);
  }

  private function validateCanbenullAttribute($values = [])
  {
    $values[] = 'type:boolean';
    return implode('|', $values);
  }

  private function validateUnitAttribute($values = [])
  {
    $vlaues[] = 'type:string';
    $values[] = 'maxchars:255';
    return implode('|', $values);
  }

  private function validateDescriptionAttribute($values = [])
  {
    $values[] = 'type:string';
    return implode('|', $values);
  }
}
