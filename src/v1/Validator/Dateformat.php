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

namespace App\v1\Validator;

use Rakit\Validation\Rule;

class Dateformat extends Rule
{
  protected $message = "The :attribute is not valid date";

  protected $fillableParams = ['dateformat'];

  public function check($value): bool
  {
    // true for valid, false for invalid
    if ($value == '')
    {
      return true;
    }
    $date = date_create_from_format('Y-m-d', $value);
    if (!$date || date_format($date, 'Y-m-d') != $value)
    {
      return false;
    }
    return true;
  }
}
