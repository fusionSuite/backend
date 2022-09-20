<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

require __DIR__ . '/../../vendor/autoload.php';

$config = include(__DIR__ . '/../../src/config.php');

$capsule = new Capsule();
$capsule->addConnection($config['db']);
$capsule->setEventDispatcher(new Dispatcher(new Container()));
$capsule->setAsGlobal();
$capsule->bootEloquent();

preg_match('/\/truncate\/(\w+)/', $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE);

if (count($matches) == 2)
{
  $capsule->table($matches[1][0])->truncate();
}
