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

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use \Ahc\Cli\Input\Command;
use \Ahc\Cli\IO\Interactor;
use \Ahc\Cli\Output\Writer;

class ActionScript extends Command
{
  public function __construct()
  {
    parent::__construct('actionscript', 'Update database data with rule actions scripts templates');

    $this->option('-d,--debug', 'Run in debug mode to see the errors');
  }

  public function execute($debug)
  {
    // Parse template from folders inside ActionScript and import them

    $writer = new Writer;
    $writer->boldGreen('Starting the process of import / update the database with scripts templates...');
    $writer->write("\n\n");

    // Manage DB Connection
    $config = include(__DIR__ . '/../../../config.php');
    $capsule = new Capsule;
    $capsule->addConnection($config['db']);
    $capsule->setEventDispatcher(new Dispatcher(new Container));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    // DB connection done
    $error = false;

    $actionBaseFolder = __DIR__ . '/../../../../ActionScripts';
    $actionFolders = scandir($actionBaseFolder);
    $type = new \App\v1\Controllers\Config\Type;
    foreach($actionFolders as $actionFolder)
    {
      if ($actionFolder == '.' || $actionFolder == '..')
      {
        continue;
      }
      $jsonTemplateFile = $actionBaseFolder.'/'.$actionFolder.'/'.$actionFolder.'.json';
      if (file_exists($jsonTemplateFile))
      {
        $writer->green(' -> '.$actionFolder);
        $nbChar = strlen($actionFolder);
        $writer->green(str_repeat('.', (80 - $nbChar)));
        $template = json_decode(file_get_contents($jsonTemplateFile));

        if ($debug)
        {
          $type->_createTemplate($template);
          $writer->boldGreen(' OK ');
        }
        else
        {
          try
          {
            $type->_createTemplate($template);
            $writer->boldGreen(' OK ');
          }
          catch (\Exception $e)
          {
            $writer->boldRed(' KO ');
            $writer->write('  ');
            echo 'Caught exception: ',  $e->getMessage();
            $error = true;
          }
        }
        $writer->write("\n");
      }
    }
    if ($error)
    {
      throw new \Exception('Error(s) when import actions templates.');
    }
  }

}

