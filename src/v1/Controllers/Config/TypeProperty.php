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
namespace App\v1\Controllers\Config;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class TypeProperty
{

  /**
   * @api {get} /v1/config/typeproperty Get all typeproperties
   * @apiName GetConfigTypeProperties
   * @apiGroup Config/Typeproperties
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *     
   * @apiSuccess {Integer}  id                 The id of the typeproperty.
   * @apiSuccess {String}   name               The name of the typeproperty.
   * @apiSuccess {String}   internalname       The internalname of the typeproperty.
   * @apiSuccess {String="string","integer","float","date","datetime","list","boolean","text","itemlink","itemlinks"}   valuetype  The type of value.
   * @apiSuccess {String}   regexformat        The regexformat to verify the value is conform (works only with valuetype is string or list).
   * @apiSuccess {String[]|null}  listvalues   The list of values when valuetype="list", else null.
   * @apiSuccess {String|null}  unit           The unit used for the property (example: Ko, seconds...).
   * @apiSuccess {String}   default            The default value.
   * @apiSuccess {String|null}  [description]  The description of the propery.
   * @apiSuccess {String}       created_at     Date of the item creation.
   * @apiSuccess {String|null}  updated_at     Date of the last item modification.
   * 
   * 
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 8,
   *     "name": "Serial Number",
   *     "internalname": "serialnumber",
   *     "valuetype": "string",
   *     "listvalues": null,
   *     "unit": null,
   *     "default": "",
   *     "description": "Enter the serial number of the item",
   *     "created_at": "2020-07-21 09:21:52",
   *     "updated_at": null,
   *   },
   *   {
   *     "id": 9,
   *     "name": "Model",
   *     "internalname": "model",
   *     "valuetype": "list",
   *     "listvalues": ["Latitude E7470", "Latitude E7490", "Latitude E9510", "P43s"],
   *     "unit": null,
   *     "default": "",
   *     "created_at": "2020-07-21 09:31:30",
   *     "updated_at": null,
   *   }
   * ]
   *
   */
  public function getAll(Request $request, Response $response, $args): Response
  {
    $items = \App\v1\Models\Config\Property::all();
    $response->getBody()->write($items->toJson());
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {post} /v1/config/typeproperty Create a typeproperty
   * @apiName GetConfigTypeProperties
   * @apiGroup Config/Typeproperties
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *     
   * @apiSuccess {String}  name             The name of the type of items.
   * @apiSuccess {String="string","integer","float","date","datetime","list","boolean","text","itemlink","itemlinks"}  valuetype        The TODO.
   * @apiSuccess {String}  regexformat        The regexformat to verify the value is conform (works only with valuetype is string or list).
   * @apiSuccess {String[]|null}  listvalues       The TODO.
   * @apiSuccess (Optional) {String|null}  unit  The TODO.
   * 
   */
  public function postItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());

    // Validate the data format
    $dataFormat = [
      'name'         => 'required|type:string',
      'internalname' => 'type:string|regex:/^[a-z.]+$/',
      'valuetype'    => 'required|type:string|in:string,integer,float,date,datetime,list,boolean,text,itemlink,itemlinks',
      'regexformat'  => 'required|type:string',
      'listvalues'   => 'required|type:array|array',
      'unit'         => 'string|type:string',
      'default'      => 'present|type:string',
      'description'  => 'type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $propId = $this->_createProperty($data);

    $response->getBody()->write(json_encode(["id" => intval($propId)]));
    return $response->withHeader('Content-Type', 'application/json');
  }



  // TODO
  /**
   * @api {patch} /v1/config/type/:id Update an existing type of items
   * @apiName PatchConfigTypes
   * @apiGroup Config/Typeproperties
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *     
   * @apiParam {Number}    id        Unique ID of the type.
   *     
   * @apiSuccess {String}  name      Name of the type.
   */
  public function patchItem(Request $request, Response $response, $args): Response
  {
    $token = $request->getAttribute('token');

    $data = json_decode($request->getBody());
    $type = \App\v1\Models\Config\Type::find($args['id']);

    if (is_null($type))
    {
      throw new \Exception("The type has not be found", 404);
    }

    // Validate the data format
    $dataFormat = [
      'name' => 'required|type:string'
    ];
    \App\v1\Common::validateData($args, $dataFormat);

    $type->name = $data->name;
    $type->save();

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /********************
   * Private functions
   ********************/

  function _createProperty($data)
  {
    $property = new \App\v1\Models\Config\Property;
    $property->name = $data->name;
    $property->valuetype = $data->valuetype;
    $property->regexformat = $data->regexformat;
    if (\App\v1\Post::PostHasProperties($data, ['unit']) === true)
    {
      $property->unit = $data->unit;
    }
    if (property_exists($data, 'internalname') === false)
    {
      $property->internalname = preg_replace("/[^a-z.]+/", "", strtolower($data->name));
    }
    else
    {
      $property->internalname = $data->internalname;
    }
    $property->default = $data->default;
    if (\App\v1\Post::PostHasProperties($data, ['description']) === true)
    {
      $property->description = $data->description;
    }
    $property->save();

    if ($data->valuetype == "list"
      && \App\v1\Post::PostHasProperties($data, ['listvalues']) === true
      && count($data->listvalues) > 0)
    {
      foreach ($data->listvalues as $value)
      {
        $propertylist = new \App\v1\Models\Config\Propertylist;
        $propertylist->property_id = $property->id;
        $propertylist->value = $value;
        $propertylist->save();
      }
    }
    if (($data->valuetype == "itemlink" || $data->valuetype == "itemlinks")
      && \App\v1\Post::PostHasProperties($data, ['listvalues']) === true
      && count($data->listvalues) > 0)
    {
      foreach ($data->listvalues as $value)
      {
        // search the type with the name
        $prop = \App\v1\Models\Config\Type::where('name', $value)->first();
        if (!is_null($prop))
        {
          $propertylist = new \App\v1\Models\Config\Propertylist;
          $propertylist->property_id = $property->id;
          $propertylist->value = $prop->id;
          $propertylist->save();
        }
      }
    }
    return $property->id;
  }

}
