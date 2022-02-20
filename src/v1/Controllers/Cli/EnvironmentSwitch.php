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
use Ahc\Cli\IO\Interactor;
use App\v1\Controllers\Cli\Common;
use Ahc\Cli\Output\Writer;

class EnvironmentSwitch extends Command
{
  public function __construct()
  {
    parent::__construct('env:switch', 'Switch to another existant enviromnent configuration');

    $this
      ->option('-n --name', 'The name of the environment to switch to the current')
      // Usage examples:
      ->usage(
        '<bold>  env:switch</end> <comment>-n production ## switch to a production environment<eol/>'
      );
  }

  public function interact(Interactor $io)
  {
    $cliCommon = new Common;
    $envs = $cliCommon->getEnvironmentList();

    if (is_null($this->name) || $this->name == 1)
    {
      $env = $io->choice('Select the environment to switch to:', $envs);
      $this->set('name', $env);
    }

  }

  public function execute()
  {
    $writer = new Writer;
    $cliCommon = new Common;

    $cliCommon->switchEnvironment($this->name);

    $writer->green('The new environment has been successfully switched to current');
    $writer->write("\n");
  }
}
