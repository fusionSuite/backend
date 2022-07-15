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
  // TODO
  // with_properties=['type', 'software']

  public function manageParams($request, $tokenInformation = null)
  {
    $params = $request->getQueryParams();

    $queryParams = [];
    $pagination = $this->paramPagination($params);
    $queryParams = array_merge($queryParams, $pagination);
    return $queryParams;
  }

  public function paramFilters($params, $items)
  {
    /*
    Examples:
      name=toto
      name[in]=[toto,titi]
      name[contains]=to
      name[begin]=to
      name[end]=to
      name[not]=toto
      processors[less]=10
      processors[greater]=10
      date[before]=2010-01-01
      date[after]=2010-01-01

      see https://docs.github.com/en/search-github/searching-on-github/searching-issues-and-pull-requests

      name:portable
      port in:name
      memory:>1024




    */
    foreach ($params as $key => $value)
    {
      if (in_array($key, ['page', 'per_page']))
      {
        continue;
      }
      if ($key == 'name')
      {
        $items->where('name', $value);
        continue;
      }
      // manage search on properties
      $items->whereHas('properties', function ($q) use ($value)
      {
        $q->where('item_property.value', $value);
      });
    }
    return $items;
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

  public function paramPagination($params)
  {
    /*
    Examples:
      page=3
      per_page=100
    */

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
}
