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

define('STDOUT', fopen('php://stdout', 'w'));
fwrite(STDOUT, 'URI: '.print_r($_SERVER['REQUEST_URI'], true));
fwrite(STDOUT, "\n");

preg_match('/\/truncate\/(\w+)/', $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE);

if (count($matches) == 2)
{
  $capsule->table($matches[1][0])->truncate();
}

preg_match('/\/count\/(\w+)\/(\d+)/', $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE);
if (count($matches) == 3)
{
  header('Content-Type: application/json; charset=utf-8');
  $data = [
    'count' => 0,
    'rows'  => []
  ];

  $data['count'] = $capsule->table($matches[1][0])->count();

  $data['rows'] = $capsule->table($matches[1][0])->orderBy('id')->skip($matches[2][0])->take(200)->get()->toArray();
  echo json_encode($data);
  exit;
}

preg_match('/\/count\/(\w+)/', $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE);
if (count($matches) == 2)
{
  header('Content-Type: application/json; charset=utf-8');
  $data = [
    'count' => 0,
    'rows'  => []
  ];
  $data['count'] = $capsule->table($matches[1][0])->count();
  echo json_encode($data);
}
