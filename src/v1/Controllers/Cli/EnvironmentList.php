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

use \Ahc\Cli\Input\Command;
use Ahc\Cli\Output\Color;
use Ahc\Cli\Output\Writer;
use \Ahc\Cli\IO\Interactor;
use App\v1\Controllers\Cli\Common;

class EnvironmentList extends Command
{
  public function __construct()
  {
    parent::__construct('env:list', 'List all configured enviromnents configuration');
  }

  public function execute($create, $list, $update, $setcurrent)
  {
    $cliCommon = new Common;
    $envList = $cliCommon->getEnvironmentList();

    $color = new Color;
    $writer = new Writer;
    echo $color->comment('=> The list of environments configuration available is:');
    $writer->write("\n");

    if (empty($envList))
    {
      echo $color->error('ERROR: No environment found!');
    }
    else
    {
      foreach(array_keys($envList) as $envDir)
      {
        $writer->green('  * '.$envDir);
        $writer->write("\n");
      }
    }
    $writer->write("\n");
  }
}
