<?php

/**
 * FusionSuite - Backend
 * Copyright (C) 2023 FusionSuite
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

use Illuminate\Database\Capsule\Manager as DB;

class Query
{
  /**
   * Prepare item filter on custom field (string type) where
   */
  public static function fieldWhere($query, $searchField, $value)
  {
    // Manage special case for PostgreSQL because it's sensible to case for the where
    if ($query->getConnection()->getDriverName() == 'pgsql')
    {
      $query->where(DB::raw('lower(' . $searchField . ')'), strtolower($value));
    } else {
      $query->where($searchField, $value);
    }
  }

  /**
   * Prepare item filter on custom field (string type) where in
   */
  public static function fieldWherein($query, $searchField, $values)
  {
    // Manage special case for PostgreSQL because it's sensible to case for the where
    if ($query->getConnection()->getDriverName() == 'pgsql')
    {
      $query->whereIn(DB::raw('lower(' . $searchField . ')'), array_map('strtolower', $values));
    } else {
      $query->whereIn($searchField, $values);
    }
  }

  /**
   * Prepare item filter on custom field (string type) where like
   */
  public static function fieldWhereLike($query, $searchField, $value)
  {
    // Manage special case for PostgreSQL because it's sensible to case for the where
    if ($query->getConnection()->getDriverName() == 'pgsql')
    {
      $query->where(DB::raw('lower(' . $searchField . ')'), 'like', strtolower($value));
    } else {
      $query->where($searchField, 'like', $value);
    }
  }

  /**
   * Prepare item filter on custom field (string type) where not
   */
  public static function fieldWherenot($query, $searchField, $value)
  {
    // Manage special case for PostgreSQL because it's sensible to case for the where
    if ($query->getConnection()->getDriverName() == 'pgsql')
    {
      $query->whereNot(DB::raw('lower(' . $searchField . ')'), strtolower($value));
    } else {
      $query->whereNot($searchField, $value);
    }
  }

  /**
   * Prepare item filter on property where
   */
  public static function propertyWhere($items, $value, $property)
  {
    $items->whereHas('properties', function ($q) use ($value, $property)
    {
      $self = new self();
      $field = 'item_property.' . $self->getPropertyValuetype($property);
      if (
          $q->getConnection()->getDriverName() == 'pgsql'
          && in_array($property->valuetype, ['string', 'text'])
      )
      {
        $q->where('item_property.property_id', $property->id)
          ->where(DB::raw('lower(' . $field . ')'), strtolower($value));
      } else {
        $q->where('item_property.property_id', $property->id)
          ->where($field, $value);
      }
    });
  }

  /**
   * Prepare item filter on property where in
   */
  public static function propertyWherein($items, $values, $property)
  {
    $items->whereHas('properties', function ($q) use ($values, $property)
    {
      $self = new self();
      $field = 'item_property.' . $self->getPropertyValuetype($property);
      if (
          $q->getConnection()->getDriverName() == 'pgsql'
          && in_array($property->valuetype, ['string', 'text'])
      )
      {
        $q->where('item_property.property_id', $property->id)
          ->whereIn(
            DB::raw('lower(' . $field . ')'),
            array_map('strtolower', $values)
          );
      } else {
        $q->where('item_property.property_id', $property->id)
          ->whereIn($field, $values);
      }
    });
  }

  /**
   * Prepare item filter on property where with custom operator
   */
  public static function propertyWhereWithOperator($items, $value, $property, $operator = 'like')
  {
    $items->whereHas('properties', function ($q) use ($value, $property, $operator)
    {
      $self = new self();
      $field = 'item_property.' . $self->getPropertyValuetype($property);
      if (
          $q->getConnection()->getDriverName() == 'pgsql'
          && in_array($property->valuetype, ['string', 'text'])
      )
      {
        $q->where('item_property.property_id', $property->id)
          ->where(
            DB::raw('lower(' . $field . ')'),
            $operator,
            strtolower($value)
          );
      } else {
        $q->where('item_property.property_id', $property->id)
          ->where($field, $operator, $value);
      }
    });
  }

  /**
   * Prepare item filter on property where not
   */
  public static function propertyWherenot($items, $value, $property)
  {
    $items->whereHas('properties', function ($q) use ($value, $property)
    {
      $self = new self();
      $field = 'item_property.' . $self->getPropertyValuetype($property);
      if (
          $q->getConnection()->getDriverName() == 'pgsql'
          && in_array($property->valuetype, ['string', 'text'])
      )
      {
        $q->where('item_property.property_id', $property->id)
          ->whereNot(DB::raw('lower(' . $field . ')'), strtolower($value));
      } else {
        $q->where('item_property.property_id', $property->id)
          ->whereNot($field, $value);
      }
    });
  }

  // ************************************************************************************************** //
  // ************************** Private functions ***************************************************** //
  // ************************************************************************************************** //

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
}
