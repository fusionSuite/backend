<?php

/**
 * FusionSuite - Frontend
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

declare(strict_types=1);

// require __DIR__ . '/../../vendor/autoload.php';

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
    $token = new stdClass();
    $token->organization_id = 1;
    $token->user_id = $GLOBALS['user_id'];

    // Create types
    $this->createItemlist($token);
    $this->createItem();
    $this->createCSVimport($token);
    $this->createGlobalmenu();
    $this->createHomepage();
  }

  private function createItemlist($token)
  {
    // create properties
    // * type_id
    // * nb_per_page
    // * cols (example: [2,6,4])
    $ctrlProperty = new \App\v1\Controllers\Config\Property();

    $data = new stdClass();
    $data->name = 'type_id';
    $data->internalname = 'internal.typeId';
    $data->valuetype = 'typelink';
    $data->regexformat = '';
    $data->listvalues = [];
    $data->unit = '';
    $data->default = null;
    $data->description = 'the id of the type';
    $this->propIdTypeId = $ctrlProperty->createProperty($data, $token);

    $data = new stdClass();
    $data->name = 'elements per page';
    $data->internalname = 'internal.elementsPerPage';
    $data->valuetype = 'integer';
    $data->regexformat = '';
    $data->default = null;
    $data->description = 'number of elements to display per page';
    $propIdElementsPerPage = $ctrlProperty->createProperty($data, $token);

    $data = new stdClass();
    $data->name = 'property ids list (string)';
    $data->internalname = 'internal.properties';
    $data->valuetype = 'string';
    $data->regexformat = '';
    $data->default = null;
    $data->description = 'list of properties in a string';
    $propIdProperties = $ctrlProperty->createProperty($data, $token);

    $data = new stdClass();
    $data->name = 'property ids hidden list (string)';
    $data->internalname = 'internal.propertieshidden';
    $data->valuetype = 'string';
    $data->regexformat = '';
    $data->default = null;
    $data->description = 'list of properties hidden in a string';
    $propIdPropertiesHidden = $ctrlProperty->createProperty($data, $token);

    // create the type
    $ctype = new \App\v1\Controllers\Config\Type();
    $type = $ctype->createType((object)[
      'name'         => 'itemlist userparam',
      'internalname' => 'userparam.itemlist',
      'modeling'     => 'userparam'
    ], $token);

    // associate the properties
    $ctype->associateProperty($type, $this->propIdTypeId);
    $ctype->associateProperty($type, $propIdElementsPerPage);
    $ctype->associateProperty($type, $propIdProperties);
    $ctype->associateProperty($type, $propIdPropertiesHidden);
  }

  private function createItem()
  {
  }

  private function createCSVimport($token)
  {
    // create properties
    // * type_id
    // * mappingcols (example: [0, 2,null,4])
    // * joiningField (example: [0, 1, 0, 0, 1])
    $ctrlProperty = new \App\v1\Controllers\Config\Property();

    $data = new stdClass();
    $data->name = 'property ids cols mapping (string)';
    $data->internalname = 'internal.mappingcols';
    $data->valuetype = 'string';
    $data->regexformat = '';
    $data->listvalues = [];
    $data->unit = '';
    $data->default = '0';
    $data->description = 'list of properties for cols mapping in a string';
    $propIdMappingcols = $ctrlProperty->createProperty($data, $token);

    $data->name = 'joining fields (string)';
    $data->internalname = 'internal.joiningfields';
    $data->valuetype = 'string';
    $data->description = 'define for each field / column if it is a joining field';
    $propIdJoiningFields = $ctrlProperty->createProperty($data, $token);

    // create the type
    $ctype = new \App\v1\Controllers\Config\Type();
    $type = $ctype->createType((object)[
      'name'         => 'CSV cols mapping cols userparam',
      'internalname' => 'userparam.csvimport',
      'modeling'     => 'userparam'
    ], $token);

    // associate the properties
    $ctype->associateProperty($type, $this->propIdTypeId);
    $ctype->associateProperty($type, $propIdMappingcols);
    $ctype->associateProperty($type, $propIdJoiningFields);
  }

  private function createGlobalmenu()
  {
  }

  private function createHomepage()
  {
  }
}
