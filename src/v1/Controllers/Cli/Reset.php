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

namespace App\v1\Controllers\Cli;

use Ahc\Cli\Input\Command;
use Ahc\Cli\Output\Writer;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Reset extends Command
{
  public function __construct()
  {
    parent::__construct('reset', 'Reset the database');
  }

  public function execute()
  {
    $writer = new Writer();
    $writer->comment('=> The database will be reset', true);

    $config = include(__DIR__ . '/../../../config.php');

    $capsule = new Capsule();
    $capsule->addConnection($config['db']);
    $capsule->setEventDispatcher(new Dispatcher(new Container()));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    $database_name = $config['db']['database'];

    $writer->green(' -> Droping the tables ');

    try {
      Capsule::schema()->dropAllTables();
      $writer->boldGreen('OK', true);
    } catch (\Exception $e) {
      $writer->boldRed('Failed', true);
      $writer->error($e->getMessage(), true);
      return -1;
    }

    $install_command = new Install();
    return $install_command->execute();
  }
}
