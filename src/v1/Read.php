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

namespace App\v1;

trait Read
{
  /**
   * Manage params of query (pagination only for this case)
   *
   */
  public function manageParams($request, $tokenInformation = null)
  {
    $params = $request->getQueryParams();

    $queryParams = [];
    $pagination = $this->paramPagination($params);
    $queryParams = array_merge($queryParams, $pagination);
    return $queryParams;
  }

  /**
   * Manage the filter params (search filter).
   * It's mainly used by the getall items to reduce the elements to get/return
   *
   * Examples:
   *   name=toto
   *   name_in=toto,titi
   *   name_contains=to
   *   name_begin=to
   *   name_end=to
   *   name_not=toto
   *   property76_begin=to
   *   property76_end=to
   *   property76_less=10
   *   property76_greater=10
   *   created_at_before.=2010-01-01
   *   created_at_after=2010-01-01
   *   property56_contains
   *   property56=toto
   */
  public function paramFilters($params, $items, $typeId = null)
  {
    foreach ($params as $key => $value)
    {
      // manage pagination, we ignore them
      if (in_array($key, ['page', 'per_page']))
      {
        continue;
      }
      // manage filtering by name
      if ($key == 'name')
      {
        if (is_array($value))
        {
          foreach ($value as $singleValue)
          {
            $items = $this->paramFilterName(null, $singleValue, $items, $typeId);
          }
        } else {
          $items = $this->paramFilterName(null, $value, $items, $typeId);
        }
        continue;
      }
      if (str_starts_with($key, 'name_'))
      {
        $operator = str_replace('name_', '', $key);
        if (is_array($value))
        {
          foreach ($value as $singleValue)
          {
            $items = $this->paramFilterName($operator, $singleValue, $items, $typeId);
          }
        } else {
          $items = $this->paramFilterName($operator, $value, $items, $typeId);
        }
        continue;
      }
      // manage filtering by property
      preg_match('/^property(\d+)(_(in|contains|begin|end|not|less|greater|before|after)){0,1}$/', $key, $matches);
      // Validation of data
      if (count($matches) != 2 and count($matches) != 4)
      {
        throw new \Exception("This query is malformed", 400);
      } else {
        if (is_array($value))
        {
          foreach ($value as $singleValue)
          {
            $items = $this->paramFilterProperty($matches, $singleValue, $items, $typeId);
          }
        } else {
          $items = $this->paramFilterProperty($matches, $value, $items, $typeId);
        }
        continue;
      }
    }
    return $items;
  }

  private function paramFilterName($operator, $value, $items, $typeId = null)
  {
    if (is_null($operator))
    {
      $items->where('name', $value);
    } else {
      switch ($operator)
      {
        case 'in':
          $values = explode(',', $value);
          $items->whereIn('name', $values);
            break;

        case 'contains':
          $items->where('name', 'like', '%' . $value . '%');
            break;

        case 'begin':
          $items->where('name', 'like', $value . '%');
            break;

        case 'end':
          $items->where('name', 'like', '%' . $value);
            break;

        case 'not':
          $items->whereNot('name', $value);
            break;

        default:
            throw new \Exception("The Searchvalue is not allowed", 400);
      }
    }
    return $items;
  }

  /**
   * Manage filter on item properties
   */
  private function paramFilterProperty($matches, $value, $items, $typeId)
  {
    $property = $this->getPropertyOfType($typeId, $matches[1]);
    if (is_null($property))
    {
      throw new \Exception("This property does not exist", 400);
    }
    if ($value == 'null' && count($matches) == 2)
    {
      $value = null;
    } elseif ($property->valuetype == 'boolean')
    {
      $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
      if (count($matches) == 4)
      {
        throw new \Exception("The search operator is not allowed", 400);
      }
      elseif (is_null($value))
      {
        throw new \Exception("The Searchvalue is not in the right format", 400);
      }
    } elseif ($value == 'null' && count($matches) == 4)
    {
      $value = null;
    }

    // Validation ok, now execute
    if (count($matches) == 2)
    {
      if ($property->valuetype == 'list' && !is_null($value))
      {
        $ids = $this->getSpecialPropertyFilterIds($property, $value, '=');
        if (count($ids) == 1)
        {
          $value = $ids[0];
        } else {
          $value = 0;
        }
      }
      $value = $this->convertNumericValue($value, $property->valuetype);
      $this->searchPropertyValidation($property->valuetype, $value);
      if (is_null($value))
      {
        $items->whereHas('properties', function ($q) use ($property)
        {
          $q->where('item_property.property_id', $property->id)
            ->whereNull('item_property.' . $this->getPropertyValuetype($property));
        });
      } else {
        $this->propertyQueryWhere($items, $value, $property);
      }
      return $items;
    }
    if (count($matches) == 4)
    {
      if ($this->isValuetypeIsId($property->valuetype))
      {
        $items = $this->paramFilterPropertySuffixTypeId($items, $property, $value, $matches);
      }
      elseif ($this->isValuetypeIsDatetime($property->valuetype))
      {
        $items = $this->paramFilterPropertySuffixTypeDateTime($items, $property, $value, $matches);
      }
      elseif ($this->isValuetypeIsString($property->valuetype))
      {
        $items = $this->paramFilterPropertySuffixTypeString($items, $property, $value, $matches);
      } else {
        $items = $this->paramFilterPropertySuffixTypeNumber($items, $property, $value, $matches);
      }
    }
    return $items;
  }

  /**
   * Manage filter property with suffix (in, contains...) when property is a number type
   */
  private function paramFilterPropertySuffixTypeNumber($items, $property, $value, $matches)
  {
    switch ($matches[3])
    {
      case 'in':
        $values = preg_split("#(?<!/),#", $value);
        $values = $this->convertNumericValue($values, $property->valuetype);
        foreach ($values as $index => $singleValue)
        {
          $this->searchPropertyValidation($property->valuetype, $singleValue);
        }
        $this->propertyQueryWherein($items, $values, $property);
          break;

      case 'contains':
        $value = $this->convertNumericValue($value, $property->valuetype);
        $this->searchPropertyValidationIncomplete($property->valuetype, $value);
        if ($property->valuetype == 'decimal')
        {
          $this->propertyQueryWhereraw(
            $items,
            'cast(CAST(item_property.' . $this->getPropertyValuetype($property) .
            ' as decimal(18,5)) as float) like \'%' . $value . '%\'',
            $property
          );
        } else {
          $this->propertyQueryWhereWithOperator($items, '%' . $value . '%', $property);
        }
          break;

      case 'begin':
        if (is_null($value))
        {
          throw new \Exception("The Searchvalue is not valid type, The Searchvalue is not valid format", 400);
        }
        $value = $this->convertNumericValue($value, $property->valuetype);
        $this->searchPropertyValidationIncomplete($property->valuetype, $value, false);
        $this->propertyQueryWhereWithOperator($items, $value . '%', $property);
          break;

      case 'end':
        if (is_null($value))
        {
          throw new \Exception("The Searchvalue is not valid type, The Searchvalue is not valid format", 400);
        }
        $value = $this->convertNumericValue($value, $property->valuetype);
        $this->searchPropertyValidationIncomplete($property->valuetype, $value, false);
        if ($property->valuetype == 'decimal')
        {
          $this->propertyQueryWhereraw(
            $items,
            'cast(CAST(item_property.' . $this->getPropertyValuetype($property) .
            ' as decimal(18,5)) as float) like \'%' . $value . '\'',
            $property
          );
        } else {
          $this->propertyQueryWhereWithOperator($items, '%' . $value, $property);
        }
          break;

      case 'not':
        $value = $this->convertNumericValue($value, $property->valuetype);
        $this->searchPropertyValidation($property->valuetype, $value);
        $this->propertyQueryWherenot($items, $value, $property);
          break;

      case 'less':
        if (!in_array($property->valuetype, ['integer', 'number', 'decimal']))
        {
          throw new \Exception("The Searchvalue is only usable for integer, number and decimal properties", 400);
        }
        $value = $this->convertNumericValue($value, $property->valuetype);
        $this->searchPropertyValidation($property->valuetype, $value);
        $this->propertyQueryWhereWithOperator($items, $value, $property, '<');
          break;

      case 'greater':
        if (!in_array($property->valuetype, ['integer', 'number', 'decimal']))
        {
          throw new \Exception("The Searchvalue is only usable for integer, number and decimal properties", 400);
        }
        $value = $this->convertNumericValue($value, $property->valuetype);
        $this->searchPropertyValidation($property->valuetype, $value);
        $this->propertyQueryWhereWithOperator($items, $value, $property, '>');
          break;

      default:
          throw new \Exception("The search operator is not allowed", 400);
    }
    return $items;
  }

  /**
   * Manage filter property with suffix (in, contains...) when property is a string type
   */
  private function paramFilterPropertySuffixTypeString($items, $property, $value, $matches)
  {
    switch ($matches[3])
    {
      case 'in':
        $values = preg_split("#(?<!/),#", $value);
        $values = $this->convertNumericValue($values, $property->valuetype);
        foreach ($values as $index => $singleValue)
        {
          if (gettype($singleValue) == 'string' && strstr($singleValue, '/,'))
          {
            $singleValue = str_replace('/,', ',', $singleValue);
            $values[$index] = $singleValue;
          }
          $this->searchPropertyValidation($property->valuetype, $singleValue);
        }
        $this->propertyQueryWherein($items, $values, $property);
          break;

      case 'contains':
        $this->searchPropertyValidationIncomplete($property->valuetype, $value);
        $this->propertyQueryWhereWithOperator($items, '%' . $value . '%', $property);
          break;

      case 'begin':
        if (is_null($value))
        {
          throw new \Exception("The Searchvalue is not valid type, The Searchvalue is not valid format", 400);
        }
        $value = $this->convertNumericValue($value, $property->valuetype);
        $this->searchPropertyValidationIncomplete($property->valuetype, $value, false);
        $this->propertyQueryWhereWithOperator($items, $value . '%', $property);
          break;

      case 'end':
        if (is_null($value))
        {
          throw new \Exception("The Searchvalue is not valid type, The Searchvalue is not valid format", 400);
        }
        $value = $this->convertNumericValue($value, $property->valuetype);
        $this->searchPropertyValidationIncomplete($property->valuetype, $value, false);
        $this->propertyQueryWhereWithOperator($items, '%' . $value, $property);
          break;

      case 'not':
        $value = $this->convertNumericValue($value, $property->valuetype);
        $this->searchPropertyValidation($property->valuetype, $value);
        $this->propertyQueryWherenot($items, $value, $property);
          break;

      default:
          throw new \Exception("The search operator is not allowed", 400);
    }
    return $items;
  }

  /**
   * Manage filter property with suffix (in, contains...) when property is an id type
   */
  private function paramFilterPropertySuffixTypeId($items, $property, $value, $matches)
  {
    $values = [];
    if (!is_null($value))
    {
      $values = $this->getSpecialPropertyFilterIds($property, $value, $matches[3]);
    }
    switch ($matches[3])
    {
      case 'in':
      case 'contains':
        $this->propertyQueryWherein($items, $values, $property);
          break;

      case 'begin':
      case 'end':
        if (is_null($value))
        {
          throw new \Exception("The Searchvalue is not valid type, The Searchvalue is not valid format", 400);
        }
        $this->propertyQueryWherein($items, $values, $property);
          break;

      case 'not':
        // $value = $this->convertNumericValue($value, $property->valuetype);
        if (is_null($value) && ($property->valuetype == 'itemlinks' || $property->valuetype == 'typelinks'))
        {
          $items->whereDoesntHave('properties', function ($q) use ($property)
          {
            $q->where('item_property.property_id', $property->id)
              ->whereNull('item_property.' . $this->getPropertyValuetype($property));
          });
        }
        elseif (is_null($value))
        {
          $items->whereHas('properties', function ($q) use ($property)
          {
            $q->where('item_property.property_id', $property->id)
              ->whereNotNull('item_property.value_' . $property->valuetype);
          });
        }
        elseif ($property->valuetype == 'itemlinks' || $property->valuetype == 'typelinks')
        {
          // Special case for itemlinks / typelinks to manage when have multiple values
          $items->whereDoesntHave('properties', function ($q) use ($value, $property)
          {
            $q->where('item_property.property_id', $property->id)
              ->where('item_property.' . $this->getPropertyValuetype($property), $value);
          });
          $items->whereDoesntHave('properties', function ($q) use ($property)
          {
            $q->where('item_property.property_id', $property->id)
              ->whereNull('item_property.' . $this->getPropertyValuetype($property));
          });
        } else {
          foreach ($values as $val)
          {
            $this->searchPropertyValidation($property->valuetype, $val);
            $this->propertyQueryWherenot($items, $val, $property);
          }
        }
          break;

      default:
          throw new \Exception("The search operator is not allowed", 400);
    }
    return $items;
  }

  /**
   * Manage filter property with suffix (in, contains...) when property is a date/time type
   */
  private function paramFilterPropertySuffixTypeDateTime($items, $property, $value, $matches)
  {
    switch ($matches[3])
    {
      case 'in':
        $values = preg_split("#(?<!/),#", $value);
        $values = $this->convertNumericValue($values, $property->valuetype);
        foreach ($values as $index => $singleValue)
        {
          if (gettype($singleValue) == 'string' && strstr($singleValue, '/,'))
          {
            $singleValue = str_replace('/,', ',', $singleValue);
            $values[$index] = $singleValue;
          }
          $this->searchPropertyValidation($property->valuetype, $singleValue);
        }
        $this->propertyQueryWherein($items, $values, $property);
          break;

      case 'contains':
        $this->searchPropertyValidationIncomplete($property->valuetype, $value);
        $this->propertyQueryWhereWithOperator($items, '%' . $value . '%', $property);
          break;

      case 'begin':
        if (is_null($value))
        {
          throw new \Exception("The Searchvalue is not valid type, The Searchvalue is not valid format", 400);
        }
        $this->searchPropertyValidationIncomplete($property->valuetype, $value, false);
        $this->propertyQueryWhereWithOperator($items, $value . '%', $property);
          break;

      case 'end':
        if (is_null($value))
        {
          throw new \Exception("The Searchvalue is not valid type, The Searchvalue is not valid format", 400);
        }
        $this->searchPropertyValidationIncomplete($property->valuetype, $value, false);
        $this->propertyQueryWhereWithOperator($items, '%' . $value, $property);
          break;

      case 'not':
        $this->searchPropertyValidation($property->valuetype, $value);
        $this->propertyQueryWherenot($items, $value, $property);
          break;

      case 'before':
        $this->searchPropertyValidation($property->valuetype, $value);
        $this->propertyQueryWhereWithOperator($items, $value, $property, '<');
          break;

      case 'after':
        $this->searchPropertyValidation($property->valuetype, $value);
        $this->propertyQueryWhereWithOperator($items, $value, $property, '>');
          break;

      default:
          throw new \Exception("The search operator is not allowed", 400);
    }
    return $items;
  }

  /**
   * Prepare item filter on property where
   */
  private function propertyQueryWhere($items, $value, $property)
  {
    $items->whereHas('properties', function ($q) use ($value, $property)
    {
      $q->where('item_property.property_id', $property->id)
        ->where('item_property.' . $this->getPropertyValuetype($property), $value);
    });
  }

  /**
   * Prepare item filter on property where
   */
  private function propertyQueryWhereWithOperator($items, $value, $property, $operator = 'like')
  {
    $items->whereHas('properties', function ($q) use ($value, $property, $operator)
    {
      $q->where('item_property.property_id', $property->id)
        ->where('item_property.' . $this->getPropertyValuetype($property), $operator, $value);
    });
  }

  /**
   * Prepare item filter on property wherein
   */
  private function propertyQueryWherein($items, $values, $property)
  {
    $items->whereHas('properties', function ($q) use ($values, $property)
    {
      $q->where('item_property.property_id', $property->id)
        ->whereIn('item_property.' . $this->getPropertyValuetype($property), $values);
    });
  }

  /**
   * Prepare item filter on property whereraw
   */
  private function propertyQueryWhereraw($items, $query, $property)
  {
    $items->whereHas('properties', function ($q) use ($query, $property)
    {
      $q->where('item_property.property_id', $property->id)
        ->whereRaw($query);
    });
  }

  /**
   * Prepare item filter on property wherenot
   */
  private function propertyQueryWherenot($items, $value, $property)
  {
    $items->whereHas('properties', function ($q) use ($value, $property)
    {
      $q->where('item_property.property_id', $property->id)
        ->whereNot('item_property.' . $this->getPropertyValuetype($property), $value);
    });
  }

  private function paramSorting($params)
  {
    /*
    Examples:
      sort_by=email
      sort_by=-email,id
    */

    // By default order by id
    $order = ['id'];
    if (isset($params['sort_by']))
    {
      $order = [];
      $fields = explode(',', $params['sort_by']);
      // TODO check if field exist and have the right
      foreach ($fields as $field)
      {
        if ($field[0] == '-')
        {
          $order[] = substr($field, 1) . " DESC";
        }
        else
        {
          $order[] = $field;
        }
      }
    }
    return $order;
  }

  private function getPropertyOfType($typeId, $propertyId)
  {
    $type = \App\v1\Models\Config\Type::find($typeId);
    foreach ($type->properties()->get() as $property)
    {
      if ($property->id == $propertyId)
      {
        return $property;
      }
    }
    return null;
  }

  /**
   * return the valuetype field of the database for the table item_property for the where function
   */
  private function getPropertyValuetype($property)
  {
    if ($property->valuetype == 'itemlinks')
    {
      return 'value_itemlink';
    }
    if ($property->valuetype == 'typelinks')
    {
      return 'value_typelink';
    }
    return 'value_' . $property->valuetype;
  }

  /**
   * Manage the pagination
   * it get (if exists) the params page (the page number) and per_page (number elements per page)
   */
  public function paramPagination($params)
  {
    // By default, no pagination
    $pagination = [
      'skip' => 0,
      'take' => 100
    ];
    if (
        isset($params['page'])
        && is_numeric($params['page'])
    )
    {
        $pagination['skip'] = ($params['page'] - 1);
    }
    if (
        isset($params['per_page'])
        && is_numeric($params['per_page'])
        && $params['per_page'] <= 990
    )
    {
        $pagination['take'] = $params['per_page'];
    }
    return $pagination;
  }

  public function createLink($request, $pagination, $totalCnt)
  {
    // next The link relation for the immediate next page of results.
    // last The link relation for the last page of results.
    // first The link relation for the first page of results.
    // prev The link relation for the immediate previous page of results.

    // <https://api.github.com/user/repos?page=3&per_page=100>; rel="next",
    // <https://api.github.com/user/repos?page=50&per_page=100>; rel="last"

    $uri = $request->getUri();
    $path = $uri->getPath();
    $scheme = $uri->getScheme();
    $host = $uri->getHost();
    $paramsQuery = $request->getQueryParams();
    $paramsQuery['per_page'] = $pagination['take'];

    $url = $scheme . "://" . $host . $path;
    $query = $uri->getQuery();
    $links = [];

    $currentMaxItems = ($pagination['skip'] + 1) * $pagination['take'];

    if ($currentMaxItems < $totalCnt)
    {
      $paramsQuery['page'] = ($pagination['skip'] + 2);
      $links[] = '<' . $url . '?' . http_build_query($paramsQuery) . '>; rel="next"';
      $paramsQuery['page'] = (ceil($totalCnt / $pagination['take']));
      $links[] = '<' . $url . '?' . http_build_query($paramsQuery) . '>; rel="last"';
    }
    if ($pagination['skip'] > 0)
    {
      $paramsQuery['page'] = 1;
      $links[] = '<' . $url . '?' . http_build_query($paramsQuery) . '>; rel="first"';
      $paramsQuery['page'] = ($pagination['skip']);
      $links[] = '<' . $url . '?' . http_build_query($paramsQuery) . '>; rel="prev"';
    }
    return implode(', ', $links);
  }

  public function createContentRange($request, $pagination, $totalCnt)
  {
    $begin = ($pagination['skip'] * $pagination['take']) + 1;
    $end = ($begin + $pagination['take']) - 1;
    if ($end > $totalCnt)
    {
      $end = $totalCnt;
    }
    return 'items ' . $begin . '-' . $end . '/' . $totalCnt;
  }

  private function searchPropertyValidation($valuetype, $value, $enableNull = true)
  {
    if (is_null($value) && $enableNull)
    {
      return;
    }

    $data = (object)[
      'searchvalue' => $value
    ];
    $dataFormat = [
      'searchvalue' => 'required'
    ];

    switch ($valuetype)
    {
      case 'integer':
        $dataFormat['searchvalue'] .= '|type:integer';
          break;

      case 'decimal':
        $dataFormat['searchvalue'] .= '|regex:/^[0-9]+\.[0-9]+$/';
          break;

      case 'number':
      case 'list':
      case 'itemlink':
      case 'itemlinks':
      case 'propertylink':
      case 'typelink':
      case 'typelinks':
        $dataFormat['searchvalue'] .= '|type:integer|regex:/^[0-9]+$/';
          break;

      case 'date':
        $dataFormat['searchvalue'] .= '|type:string|dateformat';
          break;

      case 'datetime':
        $dataFormat['searchvalue'] .= '|type:string|datetimeformat';
          break;

      case 'time':
        $dataFormat['searchvalue'] .= '|type:string|timeformat';
          break;
    }
    \App\v1\Common::validateData($data, $dataFormat);
  }

  private function searchPropertyValidationIncomplete($valuetype, $value, $enableNull = true)
  {
    if (is_null($value) && $enableNull)
    {
      return;
    }

    $data = (object)[
      'searchvalue' => $value
    ];
    $dataFormat = [
      'searchvalue' => 'required'
    ];

    switch ($valuetype)
    {
      case 'integer':
        $dataFormat['searchvalue'] .= '|type:integer|regex:/^[\-]{0,1}[0-9]*$/';
          break;

      case 'number':
        $dataFormat['searchvalue'] .= '|type:integer|regex:/^[0-9]+$/';
          break;

      case 'decimal':
        $dataFormat['searchvalue'] .= '|regex:/^[0-9]*[\.]{0,1}[0-9]*$/';
          break;

      case 'date':
        $dataFormat['searchvalue'] .= '|type:string|regex:/^[0-9\-]+$/';
          break;

      case 'datetime':
        $dataFormat['searchvalue'] .= '|type:string|regex:/^[0-9\-:]+$/';
          break;

      case 'time':
        $dataFormat['searchvalue'] .= '|type:string|regex:/^[0-9:]+$/';
          break;
    }
    \App\v1\Common::validateData($data, $dataFormat);
  }

  private function convertNumericValue($value, $valuetype)
  {
    if (is_null($value))
    {
      return $value;
    }
    if (
        !in_array(
          $valuetype,
          ['number', 'integer', 'decimal', 'itemlink', 'itemlinks', 'propertylink', 'typelink', 'typelinks']
        )
    )
    {
      return $value;
    }
    if (is_array($value))
    {
      $newValue = [];
      foreach ($value as $singleValue)
      {
        if (is_numeric($singleValue))
        {
          if ($valuetype == 'decimal')
          {
            if (
                ($singleValue === strval(floatval($singleValue)))
                && (preg_match("/^[0-9]+\.[0-9]+$/", $singleValue))
            )
            {
              $newValue[] = floatval($singleValue);
            } else {
              $newValue[] = $singleValue;
            }
          }
          elseif ($singleValue === strval(intval($singleValue)))
          {
            $newValue[] = intval($singleValue);
          } else {
            $newValue[] = $singleValue;
          }
        } else {
          $newValue[] = $singleValue;
        }
      }
      return $newValue;
    }

    if (is_numeric($value))
    {
      if ($valuetype == 'decimal')
      {
        if (
            ($value === strval(floatval($value)))
            && (preg_match("/^[0-9]+\.[0-9]+$/", $value))
        )
        {
          return floatval($value);
        }
      }
      elseif ($value === strval(intval($value)))
      {
        return intval($value);
      }
    }
    return $value;
  }

  /**
   * With param filter of property have valuetype = list, itemlink, itemlinks, propertylink, typelink, typelinks
   *
   * '=' or 'in' or 'not' => Id
   * begin, end, contains => name
   *
   */
  private function getSpecialPropertyFilterIds($property, $value, $operator)
  {
    $ids = [];
    $searchField = 'name';
    $data = (object)[
      'searchvalue' => $value
    ];
    $dataFormat = [
      'searchvalue' => 'required'
    ];

    switch ($operator)
    {
      case '=':
      case 'not':
        $dataFormat['searchvalue'] .= '|type:string|regex:/^[0-9]+$/';
          break;

      case 'in':
        $dataFormat['searchvalue'] .= '|type:string|regex:/^[0-9,]+$/';
          break;

      default:
        $dataFormat['searchvalue'] .= '|type:string';
          break;
    }
    \App\v1\Common::validateData($data, $dataFormat);

    switch ($operator)
    {
      case '=':
      case 'not':
        $ids[] = intval($value);
          break;

      case 'in':
        $values = explode(',', $value);
        $values = array_map('intval', $values);
        $ids = array_merge($ids, $values);
          break;

      default:
        // TODO search in list, itemlink....
        switch ($property->valuetype)
        {
          case 'list':
            $items = \App\v1\Models\Config\Propertylist::where('property_id', $property->id);
            $searchField = 'value';
              break;

          case 'itemlink':
          case 'itemlinks':
            $allowedtypes = [];
            foreach ($property->allowedtypes as $type)
            {
              $allowedtypes[] = $type->id;
            }
            $items = \App\v1\Models\item::whereIn('type_id', $allowedtypes);
              break;

          case 'propertylink':
            $items = \App\v1\Models\Config\Property::on();
            $searchField = 'name';
              break;

          case 'typelink':
          case 'typelinks':
            $items = \App\v1\Models\Config\Type::on();
            $searchField = 'name';
              break;
        }

        switch ($operator)
        {
          case 'contains':
            $items->where($searchField, 'like', '%' . $value . '%');
              break;

          case 'begin':
            $items->where($searchField, 'like', $value . '%');
              break;

          case 'end':
            $items->where($searchField, 'like', '%' . $value);
              break;
        }
        $allItems = $items->get();
        foreach ($allItems as $item)
        {
          $ids[] = intval($item->id);
        }
          break;
    }
    return $ids;
  }

  /**
   * return true if the valuetype must contain an id, otherwise false
   */
  private function isValuetypeIsId($valuetype)
  {
    $types = ['list', 'itemlink', 'itemlinks', 'itemtype', 'itemtypes', 'propertylink', 'typelink', 'typelinks'];
    if (in_array($valuetype, $types))
    {
      return true;
    }
    return false;
  }

  private function isValuetypeIsDatetime($valuetype)
  {
    $types = ['date', 'datetime', 'time'];
    if (in_array($valuetype, $types))
    {
      return true;
    }
    return false;
  }

  private function isValuetypeIsString($valuetype)
  {
    $types = ['string', 'text'];
    if (in_array($valuetype, $types))
    {
      return true;
    }
    return false;
  }
}
