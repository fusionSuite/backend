<?php

namespace App\v1\Controllers\Cli;

use PHPUnit\Framework\TestCase;

class EnvironmentCreateTest extends TestCase
{

  private $cmd = __DIR__.'/../../../../../bin/cli';

  /**
   * Format of provider data
   * 
   * confName, host, databaseName, username, password, databaseType, setAsCurrent (true/false), expectedCode, expectedShowHelp
   */
  public function additionProvider(): array
  {
    return [
      'MariaDB-Current'         => ['phpunittest', '127.0.0.1', 'fusionsuite_phpunit', 'fusion_user', 'mypassword', 'MariaDB', 3306, true, 0, 0],
      'MySQL-Current'           => ['phpunittest', '127.0.0.1', 'fusionsuite_phpunit', 'fusion_user', 'mypassword', 'MySQL', 3306, true, 0, 0],
      'PostgreSQL-Current'      => ['phpunittest', '127.0.0.1', 'fusionsuite_phpunit', 'fusion_user', 'mypassword', 'PostgreSQL', 5432, true, 0, 0],
      'SQLServer-Current'       => ['phpunittest', '127.0.0.1', 'fusionsuite_phpunit', 'fusion_user', 'mypassword', 'SQLServer', 1433, true, 0, 0],
      'unknowtype-Current'      => ['phpunittest', '127.0.0.1', 'fusionsuite_phpunit', 'fusion_user', 'mypassword', 'YoloDB', 0, true, 255, 0],
      'MariaDB'                 => ['phpunittest', '127.0.0.1', 'fusionsuite_phpunit', 'fusion_user', 'mypassword', 'MariaDB', 3306, false, 0, 0],
      'MariaDB-Current-notHost' => ['phpunittest', '', 'fusionsuite_phpunit', 'fusion_user', 'mypassword', 'MariaDB', 3306, true, 255, 0],
    ];
    // TODO manage the sqlite !
  }

  /**
   * @dataProvider additionProvider
   */
  public function testEnvCreation($name, $host, $db, $user, $pass, $type, $port, $curr, $expectedCode, $expectedShowHelp)
  {
    // If environment exists, remove it
    $envDir = __DIR__.'/../../../../../config/'.$name;
    if (file_exists($envDir))
    {
      foreach (['/config.php', '/database.php'] as $filename)
      {
        if (file_exists($envDir.$filename))
        {
          unlink($envDir.$filename);
        }
      }
      rmdir($envDir);
    }
    // remove too the files in current if present
    $currDir = __DIR__.'/../../../../../config/current/';
    foreach (['config.php', 'database.php'] as $filename)
    {
      if (file_exists($currDir.$filename))
      {
        unlink($currDir.$filename);
      }
    }

    $output=null;
    $retval=null;
    $args = 'env:create -n '.$name.' -H '.$host.' -d '.$db.' -u '.$user.' -p '.$pass.' -t '.$type;
    if ($curr)
    {
      $args .= ' -c';
    }
    exec($this->cmd.' '.$args, $output, $retval);
    if (count($output) > 8)
    {
      unset($output[0]);
    }
    $outputStr = implode("\n", $output);
    $this->assertEquals($expectedCode, $retval, $outputStr);
    if ($expectedShowHelp)
    {
      $this->assertMatchesRegularExpression('/Options:|Commands:/', $outputStr, $outputStr);
    }
    else
    {
      $this->assertDoesNotMatchRegularExpression('/Options:|Commands:/', $outputStr, $outputStr);
    }
    // If error, not test the current files
    if ($expectedCode > 0)
    {
      return;
    }

    // Manage current or not
    if ($curr)
    {
      $this->assertTrue(file_exists($currDir.'config.php'));
      $this->assertTrue(file_exists($currDir.'database.php'));
      $phinxConfig = include($currDir.'database.php');
      $this->assertEquals($name , $phinxConfig['environments']['default_environment']);
      $this->assertEquals($host , $phinxConfig['environments'][$name]['host']);
      $this->assertEquals($port , $phinxConfig['environments'][$name]['port']);
    }
    else
    {
      $this->assertNotTrue(file_exists($currDir.'config.php'));
      $this->assertNotTrue(file_exists($currDir.'database.php'));
    }
  }
}
