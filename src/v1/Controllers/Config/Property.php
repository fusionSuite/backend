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
use stdClass;

final class Property
{
  use \App\v1\Read;

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
   * @apiSuccess {ISO8601}        properties.created_at         Date of the property creation.
   * @apiSuccess {null|ISO8601}   properties.updated_at         Date of the last property modification.
   * @apiSuccess {null|ISO8601}   properties.deleted_at         Date of the soft delete of the property.
   * @apiSuccess {null|Object}    properties.created_by         User has created the property.
   * @apiSuccess {Number}         properties.created_by.id      Id of the user has created the property.
   * @apiSuccess {String}         properties.created_by.name    Name (login) of the user has created the property.
   * @apiSuccess {String}         properties.created_by.first_name  First name of the user has created the property.
   * @apiSuccess {String}         properties.created_by.last_name   Last name of the user has created the property.
   * @apiSuccess {null|Object}    properties.updated_by         User has updated the property.
   * @apiSuccess {Number}         properties.updated_by.id      Id of the user has updated the property.
   * @apiSuccess {String}         properties.updated_by.name    Name (login) of the user has updated the property.
   * @apiSuccess {String}         properties.updated_by.first_name  First name of the user has updated the property.
   * @apiSuccess {String}         properties.updated_by.last_name   Last name of the user has updated the property.
   * @apiSuccess {null|Object}    properties.deleted_by         User has soft deleted the property.
   * @apiSuccess {Number}         properties.deleted_by.id      Id of the user has soft deleted the property.
   * @apiSuccess {String}         properties.deleted_by.name    Name (login) of the user has soft deleted the property.
   * @apiSuccess {String}         properties.deleted_by.first_name  First name of the user has soft deleted
   *    the property.
   * @apiSuccess {String}         properties.deleted_by.last_name   Last name of the user has soft deleted the property.
   * @apiSuccess {Boolean}        properties.canbenull          The property can be null or not.
   * @apiSuccess {null|Boolean}   properties.setcurrentdate     The property in the item can automatically use the
   *    current date when store in DB (works only for date, time and datetime valuetype).
   * @apiSuccess {null|String}    properties.regexformat        The regexformat to verify the value is conform
   *    (works only for string and list valuetype).
   * @apiSuccess {Object[]}       properties.listvalues         The list of values when valuetype="list".
   * @apiSuccess {Number}         properties.listvalues.id      The id of the value.
   * @apiSuccess {String}         properties.listvalues.value   The value.
   * @apiSuccess {Any}            properties.default            The default value, the type of value depends on
   *    the valuetype.
   * @apiSuccess {Object[]}       properties.allowedtypes       Types allowed in case valuetype is itemlinks or itemlink
   * @apiSuccess {Number}         properties.allowedtypes.id    The id of the type
   * @apiSuccess {String}         properties.allowedtypes.name  The name of the type
   * @apiSuccess {String}         properties.allowedtypes.internalname  The internal name of the type
   * @apiSuccess {Object}         properties.organization       Information about the organization to which
   *    the property belongs.
   * @apiSuccess {Number}         properties.organization.id    The id of the organization.
   * @apiSuccess {String}         properties.organization.name  The name of the organization.
   * @apiSuccess {Boolean}        properties.sub_organization   The property is available or not in sub organizations.
   *
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * [
   *   {
   *     "id": 8,
   *     "name": "Serial Number",
   *     "internalname": "serialnumber",
   *     "sub_organization": false,
   *     "valuetype": "string",
   *     "regexformat": null,
   *     "unit": null,
   *     "description": "Enter the serial number of the item",
   *     "created_at": "2022-08-11T00:38:57.000000Z",
   *     "updated_at": 2022-08-11T10:12:42.000000Z,
   *     "deleted_at": null,
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
   *     "canbenull": true,
   *     "setcurrentdate": null,
   *     "listvalues": [],
   *     "default": "",
   *     "allowedtypes": [],
   *     "organization": {
   *       "id": 4,
   *       "name": "suborg_2"
   *     }
   *   },
   *   {
   *     "id": 9,
   *     "name": "Model",
   *     "internalname": "model",
   *     "sub_organization": true,
   *     "valuetype": "list",
   *     "regexformat": null,
   *     "unit": null,
   *     "description": "Enter the serial number of the item",
   *     "created_at": "2022-08-11T00:38:58.000000Z",
   *     "updated_at": 2022-08-11T00:49:01.000000Z,
   *     "deleted_at": null,
   *     "created_by": {
   *       "id": 2,
   *       "name": "admin",
   *       "first_name": "Steve",
   *       "last_name": "Rogers"
   *     },
   *     "updated_by": {
   *       "id": 2,
   *       "name": "admin",
   *       "first_name": "Steve",
   *       "last_name": "Rogers"
   *     },
   *     "deleted_by": null,
   *     "canbenull": true,
   *     "setcurrentdate": null,
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
   *     "default": "",
   *     "allowedtypes": [],
   *     "organization": {
   *       "id": 4,
   *       "name": "suborg_2"
   *     }
   *   }
   * ]
   *
   */
  public function getAll(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $organizations = \App\v1\Common::getOrganizationsIds($token);
    $parentsOrganizations = \App\v1\Common::getParentsOrganizationsIds($token);

    $params = $this->manageParams($request);

    $property = \App\v1\Models\Config\Property::ofSort($params)
      ->where(function ($query) use ($organizations, $parentsOrganizations)
      {
        $query->whereIn('organization_id', $organizations)
              ->orWhere(function ($query2) use ($parentsOrganizations)
              {
                $query2->whereIn('organization_id', $parentsOrganizations)
                      ->where('sub_organization', true);
              });
      })
      ->with('listvalues', 'created_by.properties', 'updated_by.properties', 'deleted_by.properties');
    // manage permissions
    \App\v1\Permission::checkPermissionToStructure('view', 'config/property');
    $permissionIds = \App\v1\Permission::getStructureViewIds('config/property');
    if (!is_null($permissionIds))
    {
      $property->where('id', $permissionIds);
    }

    $items = $property->get()
      ->makeHidden(['value', 'byfusioninventory'])
      ->makeVisible($this->getVisibleFields());
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
   *    "itemlinks","typelink","typelinks","propertylink","list","password","passwordhash"}   properties.valuetype
   *    The type of value.
   * @apiSuccess {null|String}    unit               The unit used for the property (example: Ko,
   *    seconds...).
   * @apiSuccess {null|String}    description        The description of the propery.
   * @apiSuccess {ISO8601}        created_at         Date of the property creation.
   * @apiSuccess {null|ISO8601}   updated_at         Date of the last property modification.
   * @apiSuccess {null|ISO8601}   deleted_at         Date of the soft delete of the property.
   * @apiSuccess {null|Object}    created_by         User has created the property.
   * @apiSuccess {Number}         created_by.id      Id of the user has created the property.
   * @apiSuccess {String}         created_by.name    Name (login) of the user has created the property.
   * @apiSuccess {String}         created_by.first_name  First name of the user has created the property.
   * @apiSuccess {String}         created_by.last_name   Last name of the user has created the property.
   * @apiSuccess {null|Object}    updated_by         User has updated the property.
   * @apiSuccess {Number}         updated_by.id      Id of the user has updated the property.
   * @apiSuccess {String}         updated_by.name    Name (login) of the user has updated the property.
   * @apiSuccess {String}         updated_by.first_name  First name of the user has updated the property.
   * @apiSuccess {String}         updated_by.last_name   Last name of the user has updated the property.
   * @apiSuccess {null|Object}    deleted_by         User has soft deleted the property.
   * @apiSuccess {Number}         deleted_by.id      Id of the user has soft deleted the property.
   * @apiSuccess {String}         deleted_by.name    Name (login) of the user has soft deleted the property.
   * @apiSuccess {String}         deleted_by.first_name  First name of the user has soft deleted the property.
   * @apiSuccess {String}         deleted_by.last_name   Last name of the user has soft deleted the property.
   * @apiSuccess {Boolean}        canbenull          The property can be null or not.
   * @apiSuccess {null|Boolean}   setcurrentdate     The property in the item can automatically use the
   *    current date when store in DB (works only for date, time and datetime valuetype).
   * @apiSuccess {null|String}    regexformat        The regexformat to verify the value is conform
   *    (works only for string and list valuetype).
   * @apiSuccess {Object[]}       listvalues         The list of values when valuetype="list".
   * @apiSuccess {Number}         listvalues.id      The id of the value.
   * @apiSuccess {String}         listvalues.value   The value.
   * @apiSuccess {Any}            default            The default value, the type of value depends on the valuetype.
   * @apiSuccess {Object[]}       allowedtypes       Types allowed in case valuetype is itemlinks or itemlink
   * @apiSuccess {Number}         allowedtypes.id    The id of the type
   * @apiSuccess {String}         allowedtypes.name  The name of the type
   * @apiSuccess {String}         allowedtypes.internalname  The internal name of the type
   * @apiSuccess {Object}         organization       Information about the organization to which
   *    the property belongs.
   * @apiSuccess {Number}         organization.id    The id of the organization.
   * @apiSuccess {String}         organization.name  The name of the organization.
   * @apiSuccess {Boolean}        sub_organization   The property is available or not in sub organizations.
   *
   * @apiSuccessExample Success-Response:
   * HTTP/1.1 200 OK
   * {
   *   "id": 8,
   *   "name": "Serial Number",
   *   "internalname": "serialnumber",
   *   "sub_organization": false,
   *   "valuetype": "string",
   *   "regexformat": null,
   *   "unit": null,
   *   "description": "Enter the serial number of the item",
   *   "created_at": "2022-08-11T00:38:57.000000Z",
   *   "updated_at": 2022-08-11T10:12:42.000000Z,
   *   "deleted_at": null,
   *   "created_by": {
   *     "id": 2,
   *     "name": "admin",
   *     "first_name": "Steve",
   *     "last_name": "Rogers"
   *   },
   *   "updated_by": {
   *     "id": 3,
   *     "name": "tstark",
   *     "first_name": "Tony",
   *     "last_name": "Stark"
   *   },
   *   "deleted_by": null,
   *   "canbenull": true,
   *   "setcurrentdate": null,
   *   "listvalues": [],
   *   "default": "",
   *   "allowedtypes": [],
   *   "organization": {
   *     "id": 4,
   *     "name": "suborg_2"
   *   }
   * }
   *
   */
  public function getOne(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $organizations = \App\v1\Common::getOrganizationsIds($token);
    $parentsOrganizations = \App\v1\Common::getParentsOrganizationsIds($token);

    // check permissions
    \App\v1\Permission::checkPermissionToStructure('view', 'config/property', $args['id']);

    $item = \App\v1\Models\Config\Property::
         with('listvalues', 'created_by.properties', 'updated_by.properties', 'deleted_by.properties')
        ->withTrashed()->find($args['id']);
    if (is_null($item))
    {
      throw new \Exception("This item has not be found", 404);
    }

    $item->makeHidden(['value', 'byfusioninventory'])
      ->makeVisible($this->getVisibleFields());
    if (
        !in_array($item->organization_id, $organizations)
        && (!(in_array($item->organization_id, $parentsOrganizations) && $item->sub_organization))
    )
    {
      throw new \Exception("This property is not in your organization", 403);
    }

    $item->makeHidden(['value', 'byfusioninventory'])
    ->makeVisible($this->getVisibleFields());
    $item->makeVisible('changes');

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
   * @apiBody {null|String{..255}}   [regexformat]   The regexformat to verify the value is conform (works only with
   *    valuetype is string or list).
   * @apiBody {null|String[}         listvalues      The list of values in case of valuetype is 'list'.
   * @apiBody {null|Any}             default         The default value.
   * @apiBody {Number[]}             [allowedtypes]  The array of id of types allowed for valuetype = itemlink
   *    and itemlinks
   * @apiBody {Boolean=false}        [setcurrentdate]  Define (for date, time and datetime valuetype only) the property
   *    in item will use current.
   * @apiBody {Boolean=true}         [canbenull]     Define if the value in the item can be null or not.
   * @apiBody {null|String{..255}}   [unit]          The unit.
   * @apiBody {null|String}          [description]   The description of the property.
   * @apiBody {Null|Number}          [organization_id]  The id of the organization. If null or not defined, use the
   *    user default organization_id.
   * @apiBody {boolean}              [sub_organization] Define of the item can be viewed in sub organizations.
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
    $token = (object)$request->getAttribute('token');

    $data = json_decode($request->getBody());

    // Validate the data format
    $dataFormat = [
      'name'             => $this->validateNameAttribute(['required']),
      'internalname'     => $this->validateInternalnameAttribute(),
      'valuetype'        => $this->validateValuetypeAttribute(['required']),
      'regexformat'      => $this->validateRegexformatAttribute(),
      'listvalues'       => $this->validateListvaluesAttribute(['present']),
      'default'          => $this->validateDefaultAttribute(['present']),
      'allowedtypes'     => 'type:array|array',
      'setcurrentdate'   => $this->validateSetcurrentdateAttribute([]),
      'canbenull'        => $this->validateCanbenullAttribute([]),
      'unit'             => $this->validateUnitAttribute(),
      'description'      => $this->validateDescriptionAttribute(),
      'sub_organization' => $this->validateSubOrganizationAttribute(),
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    // check allowedtypes values
    if (
        in_array($data->valuetype, ['itemlink', 'itemlinks'])
        && property_exists($data, 'allowedtypes')
    )
    {
      // check the type of data
      $dataFormat = [
        'allowedtype_id' => 'required|type:integer|regex:/^[0-9]+$/'
      ];
      $dataTypes = new stdClass();
      foreach ($data->allowedtypes as $type_id)
      {
        $dataTypes->allowedtype_id = $type_id;
        \App\v1\Common::validateData($dataTypes, $dataFormat);
      }
      // check if the type id exists
      foreach ($data->allowedtypes as $type_id)
      {
        $type = \App\v1\Models\Config\Type::find($type_id);
        if (is_null($type))
        {
          throw new \Exception("The type id in allowedtypes has not be found", 404);
        }
      }
    }
    elseif (property_exists($data, 'allowedtypes'))
    {
      unset($data->{'allowedtypes'});
    }

    $propId = $this->createProperty($data, $token);

    \App\v1\Controllers\Log\Audit::addEntry($request, 'CREATE', '', 'Config\Property', $propId);

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
   * @apiBody {null|String{..255}}  [regexformat]   The regexformat to verify the value is conform (works only with
   *    valuetype is string or list).
   * @apiBody {null|Any}            [default]       The default value.
   * @apiBody {Number[]}            [allowedtypes]  The array of id of types allowed for valuetype = itemlink
   *    and itemlinks
   * @apiBody {Boolean}             [setcurrentdate] Define (for date, time and datetime valuetype only) the property
   *    in item will use current.
   * @apiBody {Boolean}             [canbenull]     Define if the value in the item can be null or not.
   * @apiBody {null|String{..255}}  [unit]          The unit.
   * @apiBody {null|String}         [description]   The description of the property.
   *
   */
  public function patchItem(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');

    $data = json_decode($request->getBody());
    $property = \App\v1\Models\Config\Property::
        with('listvalues')
      ->withTrashed()->find($args['id']);

    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }

    // check permissions
    \App\v1\Permission::checkPermissionToStructure('update', 'config/property', $property->id);

    // Validate the data format
    $dataFormat = [
      'name'           => $this->validateNameAttribute(),
      'regexformat'    => $this->validateRegexformatAttribute(),
      'allowedtypes'   => 'type:array|array',
      'setcurrentdate' => $this->validateSetcurrentdateAttribute(),
      'canbenull'      => $this->validateCanbenullAttribute(),
      'unit'           => $this->validateUnitAttribute(),
      'description'    => $this->validateDescriptionAttribute()
    ];
    \App\v1\Common::validateData($data, $dataFormat);
    foreach ($data as $key => $value)
    {
      if (
          !in_array(
            $key,
            ['name', 'regexformat', 'default', 'allowedtypes', 'setcurrentdate', 'canbenull', 'unit', 'description']
          )
      )
      {
        throw new \Exception("The property $key is not allowed", 400);
      }
    };
    if (property_exists($data, 'default'))
    {
      $data->valuetype = $property->valuetype;
      if ($property->valuetype == 'list')
      {
        // we get value because we can need compare the default value with the values of list in DB
        $data->listvalues = array_column($property->listvalues()->get()->toArray(), 'value');
      }
      $this->validateDefaultForSaveMethods($data);
    }

    // check allowedtypes values
    if (
        in_array($property->valuetype, ['itemlink', 'itemlinks'])
        && property_exists($data, 'allowedtypes')
    )
    {
      // check the type of data
      $dataFormat = [
        'allowedtype_id' => 'required|type:integer|regex:/^[0-9]+$/'
      ];
      $dataTypes = new stdClass();
      foreach ($data->allowedtypes as $type_id)
      {
        $dataTypes->allowedtype_id = $type_id;
        \App\v1\Common::validateData($dataTypes, $dataFormat);
      }
      // check if the type id exists
      foreach ($data->allowedtypes as $type_id)
      {
        $type = \App\v1\Models\Config\Type::find($type_id);
        if (is_null($type))
        {
          throw new \Exception("The type id in allowedtypes has not be found", 404);
        }
      }
    }

    $properties = $this->fillArrayForSaveMethods($data, false, $args['id']);
    foreach ($properties as $propertyName => $propertyValue)
    {
      $property->$propertyName = $propertyValue;
    }
    if ($property->trashed())
    {
      \App\v1\Controllers\Log\Audit::addEntry(
        $request,
        'SOFTDELETE',
        'restore',
        'Config\Property',
        $property->id
      );
      $property->restore();
    } else {
      \App\v1\Controllers\Log\Audit::addEntry(
        $request,
        'UPDATE',
        '',
        'Config\Property',
        $property->id
      );
      $property->save();
    }

    // Special case for itemlinks, because store default values in another model
    if ($property->valuetype == 'itemlinks' && property_exists($data, 'default') && !is_null($data->default))
    {
      // get values, delete if not in list, and add if missing
      $propertyitemlinks = \App\v1\Models\Config\Propertyitemlink::where('property_id', $property->id)->get();
      foreach ($propertyitemlinks as $propitemlink)
      {
        if (!in_array($propitemlink->item_id, $data->default))
        {
          $propitemlink->delete();
        } else {
          $key = array_search($propitemlink->item_id, $data->default);
          if ($key !== false) {
              unset($data->default[$key]);
          }
          unset($data->default[$propitemlink]);
        }
      }
      foreach ($data->default as $typeId)
      {
        $propertyitemlink = new \App\v1\Models\Config\Propertyitemlink();
        $propertyitemlink->property_id = $property->id;
        $propertyitemlink->item_id = $typeId;
        $propertyitemlink->save();
      }
    }

    // Special case for typelinks, because store default values in another model
    if ($property->valuetype == 'typelinks' && property_exists($data, 'default') && !is_null($data->default))
    {
      // get values, delete if not in list, and add if missing
      $propertytypelinks = \App\v1\Models\Config\Propertytypelink::where('property_id', $property->id)->get();
      foreach ($propertytypelinks as $proptypelink)
      {
        if (!in_array($proptypelink->type_id, $data->default))
        {
          $proptypelink->delete();
        } else {
          $key = array_search($proptypelink->type_id, $data->default);
          if ($key !== false) {
              unset($data->default[$key]);
          }
          unset($data->default[$proptypelink]);
        }
      }
      foreach ($data->default as $typeId)
      {
        $propertytypelink = new \App\v1\Models\Config\Propertytypelink();
        $propertytypelink->property_id = $property->id;
        $propertytypelink->type_id = $typeId;
        $propertytypelink->save();
      }
    }

    // special case to update allowedtypes
    if (
        in_array($property->valuetype, ['itemlink', 'itemlinks'])
        && property_exists($data, 'allowedtypes')
    )
    {
      // get values, delete if not in list, and add is missing
      $propertyallowedtypes = \App\v1\Models\Config\Propertyallowedtype::where('property_id', $property->id)->get();
      foreach ($propertyallowedtypes as $propallowedtype)
      {
        if (!in_array($propallowedtype->type_id, $data->allowedtypes))
        {
          $propallowedtype->delete();
        } else {
          $key = array_search($propallowedtype->type_id, $data->allowedtypes);
          if ($key !== false) {
            unset($data->allowedtypes[$key]);
          }
        }
      }
      foreach ($data->allowedtypes as $type_id)
      {
        $propertyallowedtype = new \App\v1\Models\Config\Propertyallowedtype();
        $propertyallowedtype->property_id = $property->id;
        $propertyallowedtype->type_id = $type_id;
        $propertyallowedtype->save();
      }
    }

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
    $token = (object)$request->getAttribute('token');

    $property = \App\v1\Models\Config\Property::withTrashed()
    ->find($args['id']);

    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }
    $this->denyDeleteProperty($property->internalname);
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

    // If in soft trash, delete permanently
    if ($property->trashed())
    {
      // check permissions
      \App\v1\Permission::checkPermissionToStructure('delete', 'config/property', $property->id);

      \App\v1\Controllers\Log\Audit::addEntry(
        $request,
        'DELETE',
        '',
        'Config\Property',
        $property->id
      );
      $property->makeHidden(['value', 'byfusioninventory'])
      ->makeVisible($this->getVisibleFields());
      $property->forceDelete();

      // Post delete actions
      \App\v1\Controllers\Config\Permissionstructure::deleteEndpointIdToRoles('config/property', $args['id']);
    }
    else
    {
      // check permissions
      \App\v1\Permission::checkPermissionToStructure(
        'softdelete',
        'config/property',
        $property->id
      );

      \App\v1\Controllers\Log\Audit::addEntry(
        $request,
        'SOFTDELETE',
        '',
        'Config\Property',
        $property->id
      );
      $property->delete();
    }

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function getProtectedProperties()
  {
    return [
      'userfirstname',
      'userlastname',
      'userrefreshtoken',
      'userjwtid',
      'activated'
    ];
  }

  public function createProperty($data, $token)
  {
    // check permissions
    \App\v1\Permission::checkPermissionToStructure('create', 'config/property');

    $this->validateDefaultForSaveMethods($data);
    $properties = $this->fillArrayForSaveMethods($data);

    $property = new \App\v1\Models\Config\Property();
    if (property_exists($data, 'organization_id'))
    {
      $property->organization_id = $data->organization_id;
    }
    if (property_exists($data, 'sub_organization'))
    {
      $property->sub_organization = $data->sub_organization;
    }

    foreach ($properties as $propertyName => $propertyValue)
    {
      $property->$propertyName = $propertyValue;
    }
    $property->save();

    // add list values in the right model
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
    if (
        in_array($data->valuetype, ['itemlink', 'itemlinks'])
        && property_exists($data, 'allowedtypes')
    )
    {
      foreach ($data->allowedtypes as $type_id)
      {
        $propertyallowedtype = new \App\v1\Models\Config\Propertyallowedtype();
        $propertyallowedtype->property_id = $property->id;
        $propertyallowedtype->type_id = $type_id;
        $propertyallowedtype->save();
      }
    }

    if ($data->valuetype == 'itemlinks' && !is_null($data->default))
    {
      foreach ($data->default as $typeId)
      {
        $propertyitemlink = new \App\v1\Models\Config\Propertyitemlink();
        $propertyitemlink->property_id = $property->id;
        $propertyitemlink->item_id = $typeId;
        $propertyitemlink->save();
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

    $propertyId = $property->id;

    // Add to permissions
    \App\v1\Controllers\Config\Permissionstructure::addEndpointIdToRoles('config/property', $propertyId);

    return $propertyId;
  }

  public static function deleteAllowedtypesByTypeId($typeId)
  {
    $lines = \App\v1\Models\Config\Propertyallowedtype::where('type_id', $typeId)->get();
    foreach ($lines as $line)
    {
      $line->delete();
    }
  }

  /**
   * @api {post} /v1/config/properties/:id/listvalues Add a value in listvalues when valuetype is 'list'
   * @apiName PostConfigPropertiesListvalues
   * @apiGroup Config/Properties
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id     Unique ID of the property.
   *
   * @apiSuccess {string}  value  value to add to listvalues.
   *
   * @apiParamExample {json} Request-Example:
   * {
   *   "value": "test value"
   * }
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   *
   */
  public function postPropertyListvalue(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $data = json_decode($request->getBody());
    $args['id'] = intval($args['id']);

    $property = \App\v1\Models\Config\Property::
        with('listvalues')
      ->withTrashed()
      ->find($args['id']);
    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }

    // check permissions
    \App\v1\Permission::checkPermissionToStructure('update', 'config/property', $property->id);

    $dataFormat = [
      'value' => 'required|type:string'
    ];
    \App\v1\Common::validateData($data, $dataFormat);

    $propertylist = new \App\v1\Models\Config\Propertylist();
    $propertylist->property_id = $property->id;
    $propertylist->value = $data->value;
    $propertylist->save();

    \App\v1\Controllers\Log\Audit::addEntry(
      $request,
      'UPDATE',
      'update a property',
      'Config\Property',
      $property->id
    );

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /**
   * @api {delete} /v1/config/properties/:id/listvalues/:listvalueid Delete a value in listvalues when valuetype
   *    is 'list'
   * @apiName DeleteConfigPropertiesListvalue
   * @apiGroup Config/Properties
   * @apiVersion 1.0.0-draft
   *
   * @apiUse AutorizationHeader
   *
   * @apiParam {Number}    id           Unique ID of the property.
   * @apiParam {Number}    listvalueid  Unique ID of the listvalue.
   *
   * @apiSuccessExample {json} Success-Response:
   * HTTP/1.1 200 OK
   * [
   * ]
   *
   */
  public function deletePropertyListvalue(Request $request, Response $response, $args): Response
  {
    $token = (object)$request->getAttribute('token');
    $data = json_decode($request->getBody());
    $args['id'] = intval($args['id']);
    $args['listvalueid'] = intval($args['listvalueid']);

    // Validation of data
    $property = \App\v1\Models\Config\Property::withTrashed()->with('listvalues')->find($args['id']);
    if (is_null($property))
    {
      throw new \Exception("The property has not be found", 404);
    }
    $propertylist = \App\v1\Models\Config\Propertylist::
        where('property_id', $args['id'])
      ->where('id', $args['listvalueid'])
      ->first();

    if (is_null($propertylist))
    {
      throw new \Exception("The listvalue has not be found for this property", 404);
    }

    if (!$property->canbenull && $property->default == $args['listvalueid'])
    {
      throw new \Exception("The property can't be null and the default value is this listvalue", 401);
    }

    // check permissions
    \App\v1\Permission::checkPermissionToStructure('update', 'config/property', $property->id);

    $propertylist->delete();

    // need delete default if this listvalue in default in the porperty
    if ($property->default == $args['listvalueid'])
    {
      $property->default = null;
      $property->save();
    }

    // need delete all properties used in items with this listvalue
    $items = \App\v1\Models\Item::
      withTrashed()
      ->with('properties.listvalues')
      ->whereHas('properties', function ($query) use ($args)
      {
        $query->where('value_list', $args['listvalueid']);
      })
      ->get();
    foreach ($items as $item)
    {
      $item->properties()->updateExistingPivot($args['id'], [
        'value_list' => $property->default
      ]);
      $item->touch();

      \App\v1\Controllers\Log\Audit::addEntry(
        $request,
        'UPDATE',
        'update a property because valuelist has been deleted by an administrator',
        'Item',
        $item->id
      );
    }

    \App\v1\Controllers\Log\Audit::addEntry(
      $request,
      'UPDATE',
      'update a property',
      'Config\Property',
      $property->id
    );

    $response->getBody()->write(json_encode([]));
    return $response->withHeader('Content-Type', 'application/json');
  }

  /********************
   * Private functions
   ********************/

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
        $dataFormat['default'] = 'type:integer';
          break;

      case 'itemlinks':
      case 'typelinks':
        $dataFormat['default'] = 'type:array';
          break;
    }
    $dataFormat['organization_id']  = 'type:integer|integer';
    $dataFormat['sub_organization'] = 'type:boolean|boolean';

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
          // validatation of the item type
          $dataFormat = [
            'default' => 'type:integer|regex:/^[0-9]+$/'
          ];
          \App\v1\Common::validateData((object)['default' => $itemId], $dataFormat);

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
        \App\v1\Common::validateData($data, $dataFormat);
        $dataFormat['default'] = 'regex:/^[0-9]+$/';
        \App\v1\Common::validateData($data, $dataFormat);

        $item = \App\v1\Models\Config\Type::find($data->default);
        if (is_null($item))
        {
          throw new \Exception("The Default type does not exist", 400);
        }

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
          // validatation of the item type
          $dataFormat = [
            'default' => 'type:integer|regex:/^[0-9]+$/'
          ];
          \App\v1\Common::validateData((object)['default' => $typeId], $dataFormat);

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
        // validatation of the item type
        $dataFormat = [
          'default' => 'regex:/^[0-9]+$/'
        ];
        \App\v1\Common::validateData($data, $dataFormat);

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
  private function fillArrayForSaveMethods($data, $generateInternalname = true, $propertyId = null)
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
        $properties['internalname'] = uniqid(preg_replace("/[^a-z.0-9]+/", "", strtolower($data->name)));
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
        // have the name, must get the id
        if (!is_null($propertyId))
        {
          $propertylist = \App\v1\Models\Config\Propertylist::
            where('property_id', $propertyId)
            ->where('value', $data->default)
            ->first();
            $properties['default_' . $properties['valuetype']] = $propertylist->id;
        } else {
          $properties['default_' . $properties['valuetype']] = 0;
        }
      }
    }
    if (\App\v1\Post::postHasProperties($data, ['description']) === true)
    {
      $properties['description'] = $data->description;
    }
    if (property_exists($data, 'canbenull'))
    {
      $properties['canbenull'] = $data->canbenull;
      if (property_exists($data, 'default') && !$data->canbenull && is_null($data->default))
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
    $values[] = 'regex:/^[a-z.0-9]+$/';
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

  private function validateSubOrganizationAttribute($values = [])
  {
    $values[] = 'type:boolean';
    return implode('|', $values);
  }

  /**
   * check if the property can be deleted, if not exception is thrown
   */
  private function denyDeleteProperty($internalname)
  {
    $properties = $this->getProtectedProperties();
    if (in_array($internalname, $properties))
    {
      throw new \Exception('Cannot delete this property, it is a system property', 403);
    }
  }

  private function getVisibleFields()
  {
    return [
      'created_at',
      'updated_at',
      'deleted_at',
      'created_by',
      'updated_by',
      'deleted_by',
      'organization',
      'sub_organization'
    ];
  }
}
