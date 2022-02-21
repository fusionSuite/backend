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

use Ahc\Cli\Output\Color;
use Ahc\Cli\Output\Writer;

class Common
{
  protected $dirBase = __DIR__.'/../../../../config/';

  /**
  * Get the present environment configuration list
  */
  function getEnvironmentList()
  {
    $envList = [];
    if ($handle = opendir($this->dirBase))
    {
      while (false !== ($entry = readdir($handle)))
      {
        if (($entry != "." && $entry != ".."&& $entry != "current") && is_dir($this->dirBase.$entry))
        {
          $envList[$entry] = $entry;
        }
      }
      closedir($handle);
    }
    return $envList;
  }

  function displayLogo()
  {
    $color = new Color;
    $writer = new Writer;
   
    $environment = $color->errorBold('[not defined]');
    if (file_exists($this->dirBase.'current/database.php'))
    {
      $conf = include($this->dirBase.'current/database.php');
      $environment = $color->ok('['.$conf['environments']['default_environment'].']');
    }
    
    $logo = "
    ______           _            _____       _ __     
   / ____/_  _______(_)___  ____ / ___/__  __(_) /____ 
  / /_  / / / / ___/ / __ \/ __ \\__ \ / / / / / __/ _ \
 / __/ / /_/ (__  ) / /_/ / / / /__/ / /_/ / / /_/  __/
/_/    \__,_/____/_/\____/_/ /_/____/\__,_/_/\__/\___/ 
                      
FusionSuite Backend cli tool

Current environment: ".$environment."
=======================================================

";
    $writer->white($logo);
  }

  function switchEnvironment($name)
  {
    foreach (['/config.php', '/database.php'] as $filename)
    {
      $this->copy(
        $this->dirBase.$name.$filename,
        $this->dirBase.'current'.$filename,
      );
    }
  }


  protected function copy($source, $dest)
  {
    $writer = new Writer;

    if (!@copy($source, $dest))
    {
      $writer->boldRed('Error when try define this new environment as the current environment configuration (copy file)');
      $writer->write("\n");
      $errors= error_get_last();
      $writer->boldRed("Copy error: ".$errors['type']);
      $writer->write("\n");
      $writer->boldRed("Message: ".$errors['message']);
      $writer->write("\n");
      throw new \Exception("Error when try define this new environment as the current environment configuration (copy file)");
      return;
    }

  }
}
