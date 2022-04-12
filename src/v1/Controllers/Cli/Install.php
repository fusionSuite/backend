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
use Ahc\Cli\Output\Color;
use Ahc\Cli\Output\Writer;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Install extends Command
{
  public function __construct()
  {
    parent::__construct('install', 'Install / update FusionSuite & actionScripts');
  }

  public function execute()
  {
    $color = new Color();
    $writer = new Writer();
    echo $color->comment('=> The database will be installed / updated');
    $writer->write("\n");

    $phinx = new PhinxApplication();
    $phinxCommand = $phinx->find('migrate');
    $phinxConfig = include(__DIR__ . '/../../../../config/current/database.php');

    $arguments = [
      'command'         => 'migrate',
      '--environment'   => $phinxConfig['environments']['default_environment'],
      '--configuration' => __DIR__ . '/../../../../config/current/database.php'
    ];
    $input = new ArrayInput($arguments);
    $output = new ConsoleOutput();
    $returnCode = $phinxCommand->run(new ArrayInput($arguments), $output);
    if ($returnCode != 0)
    {
      echo $color->error('The database is not correctly installed');
      return $returnCode;
    }
    echo $color->ok('The database is up to date');
    $writer->write("\n\n");

    echo $color->comment('=> The ActionScripts will be installed / updated');
    $writer->write("\n");

    $writer->green('Starting the process of import / update the database with scripts templates...');
    $writer->write("\n");

    // Manage DB Connection
    $config = include(__DIR__ . '/../../../config.php');
    $capsule = new Capsule();
    $capsule->addConnection($config['db']);
    $capsule->setEventDispatcher(new Dispatcher(new Container()));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    // DB connection done
    $error = false;

    $actionBaseFolder = __DIR__ . '/../../../../ActionScripts';
    $actionFolders = scandir($actionBaseFolder);
    $type = new \App\v1\Controllers\Config\Type();
    foreach ($actionFolders as $actionFolder)
    {
      if ($actionFolder == '.' || $actionFolder == '..')
      {
        continue;
      }
      $jsonTemplateFile = $actionBaseFolder . '/' . $actionFolder . '/' . $actionFolder . '.json';
      if (file_exists($jsonTemplateFile))
      {
        $writer->green(' -> ' . $actionFolder);
        $nbChar = strlen($actionFolder);
        $writer->green(str_repeat('.', (80 - $nbChar)));
        $template = json_decode(file_get_contents($jsonTemplateFile));

        $debug = false;
        if ($debug)
        {
          $type->createTemplate($template);
          $writer->boldGreen(' OK ');
        }
        else {
          try {
            $type->createTemplate($template);
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
    $writer->green('Fusionsuite is right installed / updated.');
    $writer->boldGreen(' Enjoy!');
    $writer->write("\n");
  }
}
