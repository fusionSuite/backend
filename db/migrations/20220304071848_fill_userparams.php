<?php
declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

final class FillUserparams extends AbstractMigration
{
  private $propIdTypeId = 0;

  /**
   * Change Method.
   *
   * Write your reversible migrations using this method.
   *
   * More information on writing migrations is available here:
   * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
   *
   * Remember to call "create()" or "update()" and NOT "save()" when working
   * with the Table class.
   */
  public function change(): void
  {

    $config = include(__DIR__.'/../../src/config.php');

    $capsule = new Capsule;
    $capsule->addConnection($config['db']);
    $capsule->setEventDispatcher(new Dispatcher(new Container));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();


    // Create types
    $this->createItemlist();
    $this->createItem();
    $this->createCSVimport();
    $this->createGlobalmenu();
    $this->createHomepage();
  }
 
  private function createItemlist()
  {
    // create properties
    // * type_id
    // * nb_per_page
    // * cols (example: [2,6,4])
    $controllerProperty = new \App\v1\Controllers\Config\Property;
    
    $data = new stdClass;
    $data->name = 'type_id';
    $data->internalname = 'internal.typeId';
    $data->valuetype = 'typelink';
    $data->regexformat = '';
    $data->listvalues = [];
    $data->unit = '';
    $data->default = null;
    $data->description = 'the id of the type';
    $this->propIdTypeId = $controllerProperty->createProperty($data);   

    $data = new stdClass;
    $data->name = 'elements per page';
    $data->internalname = 'internal.elementsPerPage';
    $data->valuetype = 'integer';
    $data->regexformat = '';
    $data->default = null;
    $data->description = 'number of elements to display per page';
    $propIdElementsPerPage = $controllerProperty->createProperty($data);    

    $data = new stdClass;
    $data->name = 'property ids list (string)';
    $data->internalname = 'internal.properties';
    $data->valuetype = 'string';
    $data->regexformat = '';
    $data->default = null;
    $data->description = 'list of properties in a string';
    $propIdProperties = $controllerProperty->createProperty($data);    

    $data = new stdClass;
    $data->name = 'property ids hidden list (string)';
    $data->internalname = 'internal.propertieshidden';
    $data->valuetype = 'string';
    $data->regexformat = '';
    $data->default = null;
    $data->description = 'list of properties hidden in a string';
    $propIdPropertiesHidden = $controllerProperty->createProperty($data);    

    // create the type
    $type = new \App\v1\Models\Config\Type;
    $type->name = 'itemlist userparam';
    $type->internalname = 'userparam.itemlist';
    $type->modeling = 'userparam';
    $type->save();
    $typeId = $type->id;

    // associate the properties
    $type->properties()->attach($this->propIdTypeId);
    $type->properties()->attach($propIdElementsPerPage);
    $type->properties()->attach($propIdProperties);
    $type->properties()->attach($propIdPropertiesHidden);
  }

  private function createItem()
  {
    
  }

  private function createCSVimport()
  {
    // create properties
    // * type_id
    // * mappingcols (example: [0, 2,null,4])
    // * joiningField (example: [0, 1, 0, 0, 1])
    $controllerProperty = new \App\v1\Controllers\Config\Property;

    $data = new stdClass;
    $data->name = 'property ids cols mapping (string)';
    $data->internalname = 'internal.mappingcols';
    $data->valuetype = 'string';
    $data->regexformat = '';
    $data->listvalues = [];
    $data->unit = '';
    $data->default = '0';
    $data->description = 'list of properties for cols mapping in a string';
    $propIdMappingcols= $controllerProperty->createProperty($data);    
    
    $data->name = 'joining fields (string)';
    $data->internalname = 'internal.joiningfields';
    $data->valuetype = 'string';
    $data->description = 'define for each field / column if it is a joining field';
    $propIdJoiningFields = $controllerProperty->createProperty($data);    
    
    // create the type
    $type = new \App\v1\Models\Config\Type;
    $type->name = 'CSV cols mapping cols userparam';
    $type->internalname = 'userparam.csvimport';
    $type->modeling = 'userparam';
    $type->save();
    $typeId = $type->id;

    // associate the properties
    $type->properties()->attach($this->propIdTypeId);
    $type->properties()->attach($propIdMappingcols);
    $type->properties()->attach($propIdJoiningFields);
  }

  private function createGlobalmenu()
  {
    
  }

  private function createHomepage()
  {
    
  }

}
