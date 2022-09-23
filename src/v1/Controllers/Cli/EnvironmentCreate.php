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

use App\v1\Controllers\Cli\Common;
use Ahc\Cli\Input\Command;
use Ahc\Cli\IO\Interactor;
use Ahc\Cli\Output\Color;
use Ahc\Cli\Output\Writer;

class EnvironmentCreate extends Command
{
  public function __construct()
  {
    parent::__construct('env:create', 'Create an enviromnent configuration');

    $this
      ->option('-n --name', 'The name of the environment configuration')
      ->option('-t --type', 'The type of the database: MySQL, MariaDB, PostgreSQL, SQLite or SQLServer')
      ->option('-H --host', 'The hostname of the database (IP address or DNS)')
      ->option('-d --databasename', 'The name of the database')
      ->option('-u --username', 'The username account to connect to the database')
      ->option('-p --password', 'The password to connect to the database')
      ->option('-P --port', '[option] The port to connect to the database')
      ->option('-c --current', '[option] This will define this new environment as the current ' .
      'environment configuration')
      // Usage examples:
      ->usage(
        '<bold>  env:create</end> <comment> ## create an environment configuration - interactive mode<eol/>' .
        '<bold>  env:create</end> <comment>--name staging --type MariaDB --host 127.0.0.1 --username root ' .
        '--password secret ## create a MariaDB staging environment configuration<eol/>' .
        '<bold>  env:create</end> <comment>-n production -t PostgreSQL -h 192.168.20.10 -u root -p secret ' .
        '-P 5433 ## create a PostgreSQL production environment configuration<eol/>'
      );
  }

  // This method is auto called before `self::execute()` and receives `Interactor $io` instance
  public function interact(Interactor $io): void
  {
    $color = new Color();
    $writer = new Writer();

    $optionsCalled = false;
    foreach ($this->values(false) as $option => $value)
    {
      if (!is_null($value))
      {
        $optionsCalled = true;
        break;
      }
    }

    if (!$optionsCalled)
    {
      // Enter interactive mode
      $this->set(
        'name',
        $io->prompt('Enter the name of the environment configuration (examples: production, staging...)')
      );

      $databases = ['1' => 'MySQL', '2' => 'MariaDB', '3' => 'PostgreSQL', '4' => 'SQLite', '5' => 'SQLServer'];
      $database = $io->choice('Select a type of database:', $databases, '3');
      $this->set('type', $database);

      $this->set('host', $io->prompt('Enter the hostname of the database'));
      $this->set('databasename', $io->prompt('Enter the name of the database'));
      $this->set('username', $io->prompt('Enter the username to connect to the database'));
      $this->set('password', $io->promptHidden('Enter the password to connect to the database'));
      $this->set('port', $io->prompt('Enter the port to connect to the database, set 0 for the default port'));

      $choices = ['0' => 'no', '1' => 'yes'];
      $choice = $io->choice('Define this environment as the current environment?', $choices, '0');
      $this->set('current', $choice);
    }
    else
    {
      if (is_null($this->name) || $this->name == 1)
      {
        throw new \Exception("The name is not defined!");
      }
      if (is_null($this->type) || $this->type == 1)
      {
        throw new \Exception("The type of database is not defined!");
      }
      if (is_null($this->host) || $this->host == 1)
      {
        throw new \Exception("The database hostname is not defined!");
      }
      if (is_null($this->databasename) || $this->databasename == 1)
      {
        throw new \Exception("The database name is not defined!");
      }
      if (is_null($this->username) || $this->username == 1)
      {
        throw new \Exception("The database username is not defined!");
      }
      if (is_null($this->password) || $this->password == 1)
      {
        throw new \Exception("The database password is not defined!");
      }
    }
  }

  public function execute()
  {
    $writer = new Writer();
    $cliCommon = new Common();

    // Convert name to restricted chars and lowercase
    $name = preg_replace("/[^a-z0-9]+$/", "", strtolower($this->name));

    // Create folder
    $dirName = __DIR__ . '/../../../../config/' . $name;
    if (file_exists($dirName))
    {
      throw new \Exception("The environment yet exists!");
    }
    mkdir($dirName);

    $adapter = '';
    $port = 0;
    switch ($this->type)
    {
      case 'MySQL':
      case 'MariaDB':
      case '1':
      case '2':
        $adapter = 'mysql';
        $port = 3306;
          break;

      case 'PostgreSQL':
      case '3':
        $adapter = 'pgsql';
        $port = 5432;
          break;

      case 'SQLite':
      case '4':
        $adapter = 'sqlite';
          break;

      case 'SQLServer':
      case '5':
        $adapter = 'sqlsrv';
        $port = 1433;
          break;

      default:
          throw new \Exception("The database type not right/supported!");
    }
    if (!is_null($this->port) && $this->port != 1)
    {
      $port = $this->port;
    }

    $content = "<?php\n" .
    "return\n" .
    "[\n" .
    "  'paths' => [\n" .
    "      'migrations' => __DIR__.'/../../db/migrations',\n" .
    "      'seeds' => __DIR__.'/../../db/seeds'\n" .
    "  ],\n" .
    "  'environments' => [\n" .
    "      'default_migration_table' => 'phinxlog',\n" .
    "      'default_environment' => '" . $name . "',\n" .
    "      '" . $name . "' => [\n" .
    "          'adapter' => '" . $adapter . "',\n" .
    "          'host'    => '" . $this->host . "',\n" .
    "          'name'    => '" . $this->databasename . "',\n" .
    "          'user'    => '" . $this->username . "',\n" .
    "          'pass'    => '" . $this->password . "',\n" .
    "          'port'    => '" . $port . "',\n" .
    "          'charset' => 'utf8',\n" .
    "      ]\n" .
    "  ],\n" .
    "  'version_order' => 'creation'\n" .
    "];\n" .
    "\n";
    file_put_contents($dirName . '/database.php', $content);

    // Generate and create config file (for JWT secret for example)

    $content = "<?php\n" .
    "return\n" .
    "[\n" .
    "  'jwtsecret' => '" . sodium_bin2base64(
      random_bytes(64),
      SODIUM_BASE64_VARIANT_ORIGINAL
    ) . "',\n" .
    "  'pwdsecret' => '" . sodium_bin2base64(
      random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES),
      SODIUM_BASE64_VARIANT_ORIGINAL
    ) . "'\n" .
    "];\n" .
    "\n";
    file_put_contents($dirName . '/config.php', $content);
    // If current (switch to this environment), copy the file into current folder
    if ($this->current == 1 || $this->current == 'yes')
    {
      $cliCommon->switchEnvironment($name);
    }

    $writer->green('The new environment has been successfully defined');
    $writer->write("\n");
  }
}
