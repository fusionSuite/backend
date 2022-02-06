<?php

$baseFolder = __DIR__ ;
$folders = scandir($baseFolder);
foreach($folders as $folder)
{
  if ($folder == '.' || $folder == '..')
  {
    continue;
  }
  if (is_dir(__DIR__.'/'.$folder))
  {
    require_once __DIR__ . '/'.$folder.'/vendor/autoload.php';
  }
}

