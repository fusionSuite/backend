<?php

// Load json file
$file = file_get_contents('../tests/RESTAPI/schemaValidation/swagger.json');
$json = json_decode($file, true);

$base = '../tests/RESTAPI/schemaValidation';

// Load all folders
$dir = new RecursiveDirectoryIterator($base);
$files = new RecursiveIteratorIterator($dir);
$folders = [];
foreach($files as $file) {
  if ($file->getPath() == $base) {
    continue;
  }
  $folders[$file->getPath()] = '';
}
// print_r($folders);

foreach ($json['paths'] as $endpoint=>$epData)
{
  // Create folder of endpoint
  if (!file_exists($base.$endpoint))
  {
    mkdir($base.$endpoint, 0777, true);
  }

  foreach ($epData as $method=>$mData)
  {
    // create method folder
    if (!file_exists($base.$endpoint.'/'.$method))
    {
      mkdir($base.$endpoint.'/'.$method, 0777, true);
    }
    if (isset($folders[$base.$endpoint.'/'.$method]))
    {
      unset($folders[$base.$endpoint.'/'.$method]);
    }
    // Loop to remove from folders all parents folders
    $folder = $base.$endpoint.'/'.$method;

    while(true)
    {
      $folder = preg_replace('/([\/][\w{}]+)$/', '', $folder);
      if (isset($folders[$folder]))
      {
        unset($folders[$folder]);
      }
      if (!strpos($folder, '/')) {
        break;
      }
    }
  
    // Create a part of json in each folder method
    $endpointJson = [
      'path' => $endpoint,
      'method' => $method,
      'parameters' => $mData['parameters'],
      'responses' => $mData['responses']['200']['schema']['properties']
    ];

    file_put_contents(
      $base.$endpoint.'/'.$method.'/schema.json',
      json_encode(
        $endpointJson, 
        JSON_PRETTY_PRINT
      )
    );
  }
}

if (count($folders))
{
  echo "The following folders needs to be deleted:\n";
  print_r(array_keys($folders));
}
