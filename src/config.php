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

if (!isset($phinxConfig))
{
   $phinxConfig = include(__DIR__ . '/../config/current/database.php');
}
$environment = $phinxConfig['environments']['default_environment'];
$phinxDatabase = $phinxConfig['environments'][$environment];

return [
  'determineRouteBeforeAppMiddleware' => false,
  'outputBuffering'                   => false,
  'displayErrorDetails'               => true,
  'db'                                => [
    'driver'    => $phinxDatabase['adapter'],
    'host'      => $phinxDatabase['host'],
    'port'      => $phinxDatabase['port'],
    'database'  => $phinxDatabase['name'],
    'username'  => $phinxDatabase['user'],
    'password'  => $phinxDatabase['pass'],
    'charset'   => $phinxDatabase['charset'],
    'collation' => 'utf8_unicode_ci',
  ]
];
