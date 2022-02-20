<?php

namespace App\v1\Controllers\Cli;

use PHPUnit\Framework\TestCase;

class EnvironmentSwitchTest extends TestCase
{

  private $cmd = __DIR__.'/../../../../../bin/cli';

  public function additionProvider(): array
  {
    return [
      [1],
      [3],
      [2],
      [1]
    ];
  }

  public function createEnvs()
  {
    $output=null;
    $retval=null;
    foreach ([1, 2, 3] as $index)
    {
      // If environment exists, remove it
      $envDir = __DIR__.'/../../../../../config/env0'.$index;
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

      $args = 'env:create -n env0'.$index.' -H 127.0.0.1 -d fusionsuite_env0'.$index.' -u user0'.$index.' -p mypass0'.$index.' -t MariaDB -c';
      exec($this->cmd.' '.$args, $output, $retval);
      if (count($output) > 8)
      {
        unset($output[0]);
      }
      $outputStr = implode("\n", $output);
      $this->assertEquals(0, $retval, $outputStr);
    }
  }

  /**
   * @dataProvider additionProvider
   */
  public function testEnvSwitchTo01($index)
  {
    $this->createEnvs();

    $args = 'env:switch -n env0'.$index;
    exec($this->cmd.' '.$args, $output, $retval);
    if (count($output) > 8)
    {
      unset($output[0]);
    }
    $outputStr = implode("\n", $output);
    $this->assertEquals(0, $retval, $outputStr);

    $currDir = __DIR__.'/../../../../../config/current/';
    $this->assertTrue(file_exists($currDir.'config.php'));
    $this->assertTrue(file_exists($currDir.'database.php'));
    $phinxConfig = include($currDir.'database.php');
    $this->assertEquals('env0'.$index , $phinxConfig['environments']['default_environment']);
    $this->assertEquals('fusionsuite_env0'.$index , $phinxConfig['environments']['env0'.$index]['name']);
  }

}
