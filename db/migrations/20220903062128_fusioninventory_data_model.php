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

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$GLOBALS['user_id'] = 2; // admin user
// Define the permission for installation
$GLOBALS['permissions'] = (object)[
  'structure' => 'grant',
  'data'      => 'grant',
  'custom'    => [
    'structure' => [],
    'data'      => []
  ]
];

final class FusioninventoryDataModel extends AbstractMigration
{
  public $propertiesId = [];
  public $itemsId = [];

  private $myData = [
    'types'                => [],
    'properties'           => [],
    'relationshiptypes'    => [],
    'items'                => [],
    'fusioninventoryitems' => []
  ];

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
    $this->createCMDBModel();
    $this->createFusionInventoryMapping();
  }

  private function createCMDBModel()
  {
    $config = include('src/config.php');
    $capsule = new Capsule();
    $capsule->addConnection($config['db']);
    $capsule->setEventDispatcher(new Dispatcher(new Container()));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    $type = new \App\v1\Controllers\Config\Type();
    $item = new \App\v1\Controllers\Item();
    $property = new \App\v1\Controllers\Config\Property();

    // ***** Create relationships ***** //
    $this->insertRelationshiptypes([
      'name' => 'As internal component'
    ]);

    $token = new stdClass();
    $token->organization_id = 1;
    $token->user_id = $GLOBALS['user_id'];

    // ***** Create properties ***** //

    // Create first name
    $dataProp = $this->createPropObject('First name', 'userfirstname');
    $this->propertiesId['propIdFirstname'] = $property->createProperty($dataProp, $token);

    // Create last name
    $dataProp = $this->createPropObject('Last name', 'userlastname');
    $this->propertiesId['propIdLastname'] = $property->createProperty($dataProp, $token);

    // Create refreshtoken
    $dataProp = $this->createPropObject('User refreshtoken', 'userrefreshtoken');
    $this->propertiesId['propIdUserrefreshtoken'] = $property->createProperty($dataProp, $token);

    // Create jwtid
    $dataProp = $this->createPropObject('jwtid', 'userjwtid');
    $this->propertiesId['propIdJwtid'] = $property->createProperty($dataProp, $token);

    // Create active
    $dataProp = $this->createPropObject('activated', 'activated', 'boolean', true);
    $this->propertiesId['propIdActivated'] = $property->createProperty($dataProp, $token);

    // Create Address
    $dataProp = $this->createPropObject('Address', 'address');
    $this->propertiesId['address'] = $property->createProperty($dataProp, $token);

    // Create Postal code
    $dataProp = $this->createPropObject('Postal code', 'postalcode', 'number', 0);
    $this->propertiesId['postalcode'] = $property->createProperty($dataProp, $token);

    // Create City
    $dataProp = $this->createPropObject('City', 'city');
    $this->propertiesId['city'] = $property->createProperty($dataProp, $token);

    // Create Country
    $dataProp = $this->createPropObject('Country', 'country');
    $this->propertiesId['country'] = $property->createProperty($dataProp, $token);

    // Create Serial number
    $dataProp = $this->createPropObject('Serial number', 'serialnumber');
    $this->propertiesId['serialnumber'] = $property->createProperty($dataProp, $token);

    // Create Inventory number
    $dataProp = $this->createPropObject('Inventory number', 'inventorynumber');
    $this->propertiesId['inventorynumber'] = $property->createProperty($dataProp, $token);

    // Create Screen size
    $dataProp = $this->createPropObject('Screen size', 'screensize', 'number', 0, '"');
    $this->propertiesId['screensize'] = $property->createProperty($dataProp, $token);

    // Create Manufacturer
    $dataProp = $this->createPropObject('Manufacturer', 'manufacturer', 'list', null);
    $this->propertiesId['manufacturer'] = $property->createProperty($dataProp, $token);

    // Create Model
    $dataProp = $this->createPropObject('Model', 'model', 'list', null);
    $this->propertiesId['model'] = $property->createProperty($dataProp, $token);

    // Create Type
    $dataProp = $this->createPropObject('Type', 'type', 'list', null);
    $this->propertiesId['type'] = $property->createProperty($dataProp, $token);

    // Create Version
    $dataProp = $this->createPropObject('Version', 'version', 'list', null);
    $this->propertiesId['version'] = $property->createProperty($dataProp, $token);

    // Create Buy date
    $dataProp = $this->createPropObject('Buy date', 'buydate', 'date', null);
    $this->propertiesId['buydate'] = $property->createProperty($dataProp, $token);

    // Create Manufacturing date
    $dataProp = $this->createPropObject('Manufacturing date', 'manufacturingdate', 'date', null);
    $this->propertiesId['manufacturingdate'] = $property->createProperty($dataProp, $token);

    // Create Warrantly duration
    $dataProp = $this->createPropObject('Warrantly duration', 'warrantlyduration', 'number', 0, 'months');
    $this->propertiesId['warrantlyduration'] = $property->createProperty($dataProp, $token);

    // Create Date
    $dataProp = $this->createPropObject('Date', 'date', 'date', null);
    $this->propertiesId['date'] = $property->createProperty($dataProp, $token);

    // Create Enabled
    $dataProp = $this->createPropObject('Enabled', 'enabled', 'boolean', false);
    $this->propertiesId['enabled'] = $property->createProperty($dataProp, $token);

    // Create Uptodate
    $dataProp = $this->createPropObject('Uptodate', 'uptodate', 'boolean', false);
    $this->propertiesId['uptodate'] = $property->createProperty($dataProp, $token);

    // Create Base version
    $dataProp = $this->createPropObject('Base version', 'baseversion', 'list', null);
    $this->propertiesId['baseversion'] = $property->createProperty($dataProp, $token);

    // Create Chemistry
    $dataProp = $this->createPropObject('Chemistry', 'chemistry', 'list', null);
    $this->propertiesId['chemistry'] = $property->createProperty($dataProp, $token);

    // Create Capacity
    $dataProp = $this->createPropObject('Capacity', 'capacity', 'number', 0, 'mWh');
    $this->propertiesId['capacity'] = $property->createProperty($dataProp, $token);

    // Create Voltage
    $dataProp = $this->createPropObject('Voltage', 'voltage', 'number', 0, 'mV');
    $this->propertiesId['voltage'] = $property->createProperty($dataProp, $token);

    // Create Core
    $dataProp = $this->createPropObject('Core', 'core', 'number', 0);
    $this->propertiesId['core'] = $property->createProperty($dataProp, $token);

    // Create Thread
    $dataProp = $this->createPropObject('Thread', 'thread', 'number', 0);
    $this->propertiesId['thread'] = $property->createProperty($dataProp, $token);

    // Create Speed
    $dataProp = $this->createPropObject('Speed', 'speed', 'number', 0);
    $this->propertiesId['speed'] = $property->createProperty($dataProp, $token);

    // Create Architecture
    $dataProp = $this->createPropObject('Architecture', 'architecture', 'list', null);
    $this->propertiesId['architecture'] = $property->createProperty($dataProp, $token);

    // Create Description
    $dataProp = $this->createPropObject('Description', 'description', 'text');
    $this->propertiesId['description'] = $property->createProperty($dataProp, $token);

    // Create Folder
    $dataProp = $this->createPropObject('Folder', 'folder');
    $this->propertiesId['folder'] = $property->createProperty($dataProp, $token);

    // Create URL
    $dataProp = $this->createPropObject('URL', 'url');
    $this->propertiesId['URL'] = $property->createProperty($dataProp, $token);

    // Create URL help
    $dataProp = $this->createPropObject('URL help', 'urlhelp');
    $this->propertiesId['URLhelp'] = $property->createProperty($dataProp, $token);

    // Create GUID
    $dataProp = $this->createPropObject('GUID', 'guid');
    $this->propertiesId['GUID'] = $property->createProperty($dataProp, $token);

    // Create Software category
    $dataProp = $this->createPropObject('Software category', 'softwarecategory', 'list', null);
    $this->propertiesId['softwarecategory'] = $property->createProperty($dataProp, $token);

    // Create Uninstall command
    $dataProp = $this->createPropObject('Uninstall command', 'uninstallcommand');
    $this->propertiesId['uninstallcommand'] = $property->createProperty($dataProp, $token);

    // Create Installation date
    $dataProp = $this->createPropObject('Installation date', 'installationdate', 'date', null);
    $this->propertiesId['installationdate'] = $property->createProperty($dataProp, $token);

    // Create Information source (for software)
    $dataProp = $this->createPropObject('Information source', 'informationsource', 'list', null);
    $this->propertiesId['informationsource'] = $property->createProperty($dataProp, $token);

    // Create Last boot datetime
    $dataProp = $this->createPropObject('Last boot', 'lastboot', 'date', null);
    $this->propertiesId['lastboot'] = $property->createProperty($dataProp, $token);

    // Create Service pack
    $dataProp = $this->createPropObject('Service pack', 'servicepack', 'list', null);
    $this->propertiesId['servicepack'] = $property->createProperty($dataProp, $token);

    // Create Complete name
    $dataProp = $this->createPropObject('Complete name', 'completename');
    $this->propertiesId['completename'] = $property->createProperty($dataProp, $token);

    // Create Kernel version
    $dataProp = $this->createPropObject('Kernel version', 'kernelversion', 'list', null);
    $this->propertiesId['kernelversion'] = $property->createProperty($dataProp, $token);

    // Create Kernel name
    $dataProp = $this->createPropObject('Kernel name', 'kernelname', 'list', null);
    $this->propertiesId['kernelname'] = $property->createProperty($dataProp, $token);


    // ***** Create types ***** //

    // Create organization
    $myType = $type->createType((object)[
      'name'         => 'Organization',
      'internalname' => 'organization',
      'tree'         => true
    ], $token);
    $this->itemsId['organization'] = $myType->id;

      // Attach properties
      $type->associateProperty($myType, $this->propertiesId['address']);
      $type->associateProperty($myType, $this->propertiesId['postalcode']);
      $type->associateProperty($myType, $this->propertiesId['city']);
      $type->associateProperty($myType, $this->propertiesId['country']);

    // Create first level organization
    $data = (object)[
      'name'         => 'My organization',
      'type_id'      => $this->itemsId['organization']
    ];
    $item->createItem($data, $token);

    // Create users
    $myType = $type->createType((object)[
      'name'         => 'Users',
      'internalname' => 'users',
      'unique_name'  => true
    ], $token);
    $this->itemsId['users'] = $myType->id;

      // Attach properties
      $type->associateProperty($myType, $this->propertiesId['propIdFirstname']);
      $type->associateProperty($myType, $this->propertiesId['propIdLastname']);
      $type->associateProperty($myType, $this->propertiesId['propIdUserrefreshtoken']);
      $type->associateProperty($myType, $this->propertiesId['propIdJwtid']);
      $type->associateProperty($myType, $this->propertiesId['propIdActivated']);

    // create first user
    $data = (object)[
      'name'       => 'admin',
      'type_id'    => $this->itemsId['users'],
      'properties' => [
        (object)[
          'property_id' => $this->propertiesId['propIdFirstname'],
          'value'       => 'Steve'
        ],
        (object)[
          'property_id' => $this->propertiesId['propIdLastname'],
          'value'       => 'Rogers'
        ]
      ]
    ];
    $item = new \App\v1\Controllers\Item();
    $item->createItem($data, $token);
      // TODO + add password
      // password_hash('admin', PASSWORD_ARGON2I)


    // Create Laptop
    $myType = $type->createType((object)[
      'name'         => 'Laptop',
      'internalname' => 'laptop',
      'modeling'     => 'physical'
    ], $token);
    $this->itemsId['laptop'] = $myType->id;

      // Attach properties
      $type->associateProperty($myType, $this->propertiesId['serialnumber']);
      $type->associateProperty($myType, $this->propertiesId['model']);
      $type->associateProperty($myType, $this->propertiesId['type']);
      $type->associateProperty($myType, $this->propertiesId['manufacturer']);
      $type->associateProperty($myType, $this->propertiesId['inventorynumber']);

    // Create BIOS
    $myType = $type->createType((object)[
      'name'         => 'BIOS',
      'internalname' => 'bios',
      'modeling'     => 'physical'
    ], $token);
    $this->itemsId['bios'] = $myType->id;

      // Attach properties
      $type->associateProperty($myType, $this->propertiesId['version']);
      $type->associateProperty($myType, $this->propertiesId['date']);

    // Create Antivirus
    $myType = $type->createType((object)[
      'name'         => 'Antivirus',
      'internalname' => 'antivirus',
      'modeling'     => 'physical'
    ], $token);
    $this->itemsId['antivirus'] = $myType->id;

      // Attach properties
      $type->associateProperty($myType, $this->propertiesId['serialnumber']);
      $type->associateProperty($myType, $this->propertiesId['enabled']);
      $type->associateProperty($myType, $this->propertiesId['uptodate']);
      $type->associateProperty($myType, $this->propertiesId['baseversion']);
      $type->associateProperty($myType, $this->propertiesId['version']);
      $type->associateProperty($myType, $this->propertiesId['manufacturer']);

    // Create Battery
    $myType = $type->createType((object)[
      'name'         => 'Battery',
      'internalname' => 'battery',
      'modeling'     => 'physical'
    ], $token);
    $this->itemsId['battery'] = $myType->id;

      // Attach properties
      $type->associateProperty($myType, $this->propertiesId['chemistry']);
      $type->associateProperty($myType, $this->propertiesId['manufacturingdate']);
      $type->associateProperty($myType, $this->propertiesId['serialnumber']);
      $type->associateProperty($myType, $this->propertiesId['manufacturer']);
      $type->associateProperty($myType, $this->propertiesId['capacity']);
      $type->associateProperty($myType, $this->propertiesId['voltage']);

    // Create Processor
    $myType = $type->createType((object)[
      'name'         => 'Processor',
      'internalname' => 'processor',
      'modeling'     => 'physical'
    ], $token);
    $this->itemsId['processor'] = $myType->id;

      // Attach properties
      $type->associateProperty($myType, $this->propertiesId['serialnumber']);
      $type->associateProperty($myType, $this->propertiesId['manufacturer']);
      $type->associateProperty($myType, $this->propertiesId['model']);
      $type->associateProperty($myType, $this->propertiesId['core']);
      $type->associateProperty($myType, $this->propertiesId['thread']);
      $type->associateProperty($myType, $this->propertiesId['speed']);
      $type->associateProperty($myType, $this->propertiesId['architecture']);

    // Create Software
    $myType = $type->createType((object)[
      'name'         => 'Software',
      'internalname' => 'software',
      'modeling'     => 'physical'
    ], $token);
    $this->itemsId['software'] = $myType->id;

      // Attach properties
      $type->associateProperty($myType, $this->propertiesId['version']);
      $type->associateProperty($myType, $this->propertiesId['description']);
      $type->associateProperty($myType, $this->propertiesId['folder']);
      $type->associateProperty($myType, $this->propertiesId['informationsource']);
      $type->associateProperty($myType, $this->propertiesId['URLhelp']);
      $type->associateProperty($myType, $this->propertiesId['installationdate']);
      $type->associateProperty($myType, $this->propertiesId['manufacturer']);
      $type->associateProperty($myType, $this->propertiesId['uninstallcommand']);
      $type->associateProperty($myType, $this->propertiesId['URL']);
      $type->associateProperty($myType, $this->propertiesId['GUID']);
      $type->associateProperty($myType, $this->propertiesId['architecture']);
      $type->associateProperty($myType, $this->propertiesId['softwarecategory']);

    // create Operating system
    $myType = $type->createType((object)[
      'name'         => 'Operating system',
      'internalname' => 'operatingsystem',
      'modeling'     => 'physical'
    ], $token);
    $this->itemsId['operatingsystem'] = $myType->id;

      // Attach properties
      $type->associateProperty($myType, $this->propertiesId['kernelname']);
      $type->associateProperty($myType, $this->propertiesId['kernelversion']);
      $type->associateProperty($myType, $this->propertiesId['completename']);
      $type->associateProperty($myType, $this->propertiesId['version']);
      $type->associateProperty($myType, $this->propertiesId['servicepack']);
      $type->associateProperty($myType, $this->propertiesId['installationdate']);
      $type->associateProperty($myType, $this->propertiesId['architecture']);
      $type->associateProperty($myType, $this->propertiesId['lastboot']);

    // create Controller
    $myType = $type->createType((object)[
      'name'         => 'Controller',
      'internalname' => 'controller',
      'modeling'     => 'physical'
    ], $token);
    $this->itemsId['controller'] = $myType->id;

      // Attach properties
      $type->associateProperty($myType, $this->propertiesId['manufacturer']);
  }


  private function createFusionInventoryMapping()
  {
    // Create data model for FusionInventory

    $data = [
      'item'       => [
        'querytype'              => 'computerinventory',
        'markup'                 => '',
        'markup_name'            => 'hardware/name',
        'type_id'                => $this->itemsId['laptop'],
        'fusioninventoryitem_id' => 0,
      ],
      'properties' => [
        [
          'markup'                 => 'bios/ssn',
          'property_id'            => $this->propertiesId['serialnumber']
        ],
        [
          'markup'                 => 'hardware/chassis_type',
          'property_id'            => $this->propertiesId['type']
        ],
        [
          'markup'                 => 'bios/smodel',
          'property_id'            => $this->propertiesId['model']
        ],
        [
          'markup'                 => 'bios/smanufacturer',
          'property_id'            => $this->propertiesId['manufacturer']
        ]
      ]
    ];
    $this->insertFusioninventoryData($data);

    // Operatingsystem
    $data = [
      'item'       => [
        'querytype'              => 'computerinventory',
        'markup'                 => 'operatingsystem',
        'markup_name'            => 'operatingsystem/name',
        'type_id'                => $this->itemsId['operatingsystem'],
        'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
      ],
      'properties' => [
        [
          'markup'                 => 'operatingsystem/kernel_name',
          'property_id'            => $this->propertiesId['kernelname']
        ],
        [
          'markup'                 => 'operatingsystem/kernel_version',
          'property_id'            => $this->propertiesId['kernelversion']
        ],
        [
          'markup'                 => 'operatingsystem/full_name',
          'property_id'            => $this->propertiesId['completename']
        ],
        [
          'markup'                 => 'operatingsystem/version',
          'property_id'            => $this->propertiesId['version']
        ],
        [
          'markup'                 => 'operatingsystem/service_pack',
          'property_id'            => $this->propertiesId['servicepack']
        ],
        [
          'markup'                 => 'operatingsystem/install_date',
          'property_id'            => $this->propertiesId['installationdate']
        ],
        // [
        //   'markup'                 => 'operatingsystem/fqdn',
        //   'property_id'            => 0
        // ],
        // [
        //   'markup'                 => 'operatingsystem/dns_domain',
        //   'property_id'            => 0
        // ],
        // [
        //   'markup'                 => 'operatingsystem/hostid',
        //   'property_id'            => 0
        // ],
        // [
        //   'markup'                 => 'operatingsystem/ssh_key',
        //   'property_id'            => 0
        // ],
        [
          'markup'                 => 'operatingsystem/arch',
          'property_id'            => $this->propertiesId['architecture']
        ],
        [
          'markup'                 => 'operatingsystem/boot_time',
          'property_id'            => $this->propertiesId['lastboot']
        ],
        // [
        //   'markup'                 => 'operatingsystem/timezone/name',
        //   'property_id'            => 0
        // ],
        // [
        //   'markup'                 => 'operatingsystem/timezone/offset',
        //   'property_id'            => 0
        // ],
      ]
    ];
    $this->insertFusioninventoryData($data);


    // Antivirus

    $data = [
      'item'       => [
        'querytype'              => 'computerinventory',
        'markup'                 => 'antivirus',
        'markup_name'            => 'antivirus/name',
        'type_id'                => $this->itemsId['antivirus'],
        'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
      ],
      'properties' => [
        [
          'markup'                 => 'antivirus/company',
          'property_id'            => $this->propertiesId['manufacturer']
        ],
        [
          'markup'                 => 'antivirus/version',
          'property_id'            => $this->propertiesId['version']
        ],
        [
          'markup'                 => 'antivirus/enabled',
          'property_id'            => $this->propertiesId['enabled']
        ],
        [
          'markup'                 => 'antivirus/uptodate',
          'property_id'            => $this->propertiesId['uptodate']
        ],
        [
          'markup'                 => 'antivirus/base_version',
          'property_id'            => $this->propertiesId['baseversion']
        ]
      ]
    ];
    $this->insertFusioninventoryData($data);

    // Batteries
    $data = [
      'item'       => [
        'querytype'              => 'computerinventory',
        'markup'                 => 'batteries',
        'markup_name'            => 'batteries/name',
        'type_id'                => $this->itemsId['battery'],
        'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
      ],
      'properties' => [
        [
          'markup'                 => 'batteries/chemistry',
          'property_id'            => $this->propertiesId['chemistry']
        ],
        [
          'markup'                 => 'batteries/data',
          'property_id'            => $this->propertiesId['manufacturingdate']
        ],
        [
          'markup'                 => 'batteries/serial',
          'property_id'            => $this->propertiesId['serialnumber']
        ],
        [
          'markup'                 => 'batteries/manufacturer',
          'property_id'            => $this->propertiesId['manufacturer']
        ],
        [
          'markup'                 => 'batteries/capacity',
          'property_id'            => $this->propertiesId['capacity']
        ],
        [
          'markup'                 => 'batteries/voltage',
          'property_id'            => $this->propertiesId['voltage']
        ]
      ]
    ];
    $this->insertFusioninventoryData($data);

    // BIOS
    // TODO disable for moment because have problems on it
    // $data = [
    //   'item'       => [
    //     'querytype'              => 'computerinventory',
    //     'markup'                 => 'bios',
    //     'markup_name'            => 'bios/bversion',
    //     'type_id'                => $this->myData['types']['BIOS']['id'],
    //     'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
    //   ],
    //   'properties' => [
    //     [
    //       'markup'                 => 'bios/bdate',
    //       'property_id'            => $this->myData['properties']['Date']['id']
    //     ]
    //   ]
    // ];
    // $this->insertFusioninventoryData($data);

    // // Controllers
    $data = [
      'item'       => [
        'querytype'              => 'computerinventory',
        'markup'                 => 'controllers',
        'markup_name'            => 'controllers/name',
        'type_id'                => $this->itemsId['controller'],
        'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
      ],
      'properties' => [
    //     [
    //       'markup'                 => 'controllers/driver',
    //       'property_id'            => 0
    //     ],
    //     [
    //       'markup'                 => 'controllers/caption',
    //       'property_id'            => 0
    //     ],
        [
          'markup'                 => 'controllers/manufacturer',
          'property_id'            => $this->propertiesId['manufacturer']
        ],
    //     [
    //       'markup'                 => 'controllers/pciclass',
    //       'property_id'            => 0
    //     ],
    //     [
    //       'markup'                 => 'controllers/vendorid',
    //       'property_id'            => 0
    //     ],
    //     [
    //       'markup'                 => 'controllers/productid',
    //       'property_id'            => 0
    //     ],
    //     [
    //       'markup'                 => 'controllers/pcisubsystemid',
    //       'property_id'            => 0
    //     ],
    //     [
    //       'markup'                 => 'controllers/pcislot',
    //       'property_id'            => 0
    //     ],
    //     [
    //       'markup'                 => 'controllers/type',
    //       'property_id'            => 0
    //     ],
    //     [
    //       'markup'                 => 'controllers/rev',
    //       'property_id'            => 0
    //     ]
      ]
    ];
    $this->insertFusioninventoryData($data);

    // CPUs
    $data = [
      'item'       => [
        'querytype'              => 'computerinventory',
        'markup'                 => 'cpus',
        'markup_name'            => 'cpus/name',
        'type_id'                => $this->itemsId['processor'],
        'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
      ],
      'properties' => [
        // [
        //   'markup'                 => 'cpus/driver',
        //   'property_id'            => 0
        // ],
        // [
        //   'markup'                 => 'cpus/cache',
        //   'property_id'            => 0
        // ],
        [
          'markup'                 => 'cpus/core',
          'property_id'            => $this->propertiesId['core']
        ],
        // [
        //   'markup'                 => 'cpus/corecount',
        //   'property_id'            => 0
        // ],
        [
          'markup'                 => 'cpus/description',
          'property_id'            => $this->propertiesId['description']
        ],
        [
          'markup'                 => 'cpus/manufacturer',
          'property_id'            => $this->propertiesId['manufacturer']
        ],
        [
          'markup'                 => 'cpus/thread',
          'property_id'            => $this->propertiesId['thread']
        ],
        [
          'markup'                 => 'cpus/serial',
          'property_id'            => $this->propertiesId['serialnumber']
        ],
        // [
        //   'markup'                 => 'cpus/stepping',
        //   'property_id'            => 0
        // ],
        // [
        //   'markup'                 => 'cpus/familyname',
        //   'property_id'            => 0
        // ],
        // [
        //   'markup'                 => 'cpus/familynumber',
        //   'property_id'            => 0
        // ],
        [
          'markup'                 => 'cpus/model',
          'property_id'            => $this->propertiesId['model']
        ],
        [
          'markup'                 => 'cpus/speed',
          'property_id'            => $this->propertiesId['speed']
        ],
        // [
        //   'markup'                 => 'cpus/id',
        //   'property_id'            => 0
        // ],
        // [
        //   'markup'                 => 'cpus/external_clock',
        //   'property_id'            => 0
        // ],
        [
          'markup'                 => 'cpus/arch',
          'property_id'            => $this->propertiesId['architecture']
        ]
      ]
    ];
    $this->insertFusioninventoryData($data);

    // Softwares
    // TODO complete properties
    $data = [
      'item'       => [
        'querytype'              => 'computerinventory',
        'markup'                 => 'softwares',
        'markup_name'            => 'softwares/name',
        'type_id'                => $this->itemsId['software'],
        'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
      ],
      'properties' => [
        [
          'markup'                 => 'softwares/version',
          'property_id'            => $this->propertiesId['version']
        ],
        [
          'markup'                 => 'softwares/comments',
          'property_id'            => $this->propertiesId['description']
        ],
      //   [
      //     'markup'                 => 'softwares/filesize',
      //     'property_id'            => $this->myData['properties']['']['id']
      //   ],
        [
          'markup'                 => 'softwares/folder',
          'property_id'            => $this->propertiesId['folder']
        ],
        [
          'markup'                 => 'softwares/from', // information source, ie 'registry', 'rpm', 'deb', etc.
          'property_id'            => $this->propertiesId['informationsource']
        ],
        [
          'markup'                 => 'softwares/helplink',
          'property_id'            => $this->propertiesId['URLhelp']
        ],
        [
          'markup'                 => 'softwares/installdate',
          'property_id'            => $this->propertiesId['installationdate']
        ],
      //   [
      //     'markup'                 => 'softwares/no_remove',
      //     'property_id'            => $this->myData['properties']['']['id']
      //   ],
      //   [
      //     'markup'                 => 'softwares/release_type',
      //     'property_id'            => $this->myData['properties']['']['id']
      //   ],
        [
          'markup'                 => 'softwares/publisher',
          'property_id'            => $this->propertiesId['manufacturer']
        ],
        [
          'markup'                 => 'softwares/uninstall_string',
          'property_id'            => $this->propertiesId['uninstallcommand']
        ],
        [
          'markup'                 => 'softwares/url_info_about',
          'property_id'            => $this->propertiesId['URL']
        ],
      //   [
      //     'markup'                 => 'softwares/version_minor',
      //     'property_id'            => $this->myData['properties']['']['id']
      //   ],
      //   [
      //     'markup'                 => 'softwares/version_major',
      //     'property_id'            => $this->myData['properties']['']['id']
      //   ],
        [
          'markup'                 => 'softwares/guid',
          'property_id'            => $this->propertiesId['GUID']
        ],
        [
          'markup'                 => 'softwares/arch',
          'property_id'            => $this->propertiesId['architecture']
        ],
      //   [
      //     'markup'                 => 'softwares/username',
      //     'property_id'            => $this->myData['properties']['']['id']
      //   ],
      //   [
      //     'markup'                 => 'softwares/userid',
      //     'property_id'            => $this->myData['properties']['']['id']
      //   ],
        [
          'markup'                 => 'softwares/system_category',
          'property_id'            => $this->propertiesId['softwarecategory']
        ],
      ]
    ];
    $this->insertFusioninventoryData($data);
  }

  private function insertFusioninventoryData($data)
  {
    $data['item']['created_at'] = date('Y-m-d H:i:s');
    $data['item']['updated_at'] = date('Y-m-d H:i:s');
    $tableItem = $this->table('fusioninventoryitems');
    $tableItem->insert($data['item'])
              ->save();
    $itemId = $this->adapter->getConnection()->lastInsertId();
    $this->myData['fusioninventoryitems'][$data['item']['markup']] = [
      'id' => $itemId
    ];

    foreach ($data['properties'] as $property)
    {
      $property['created_at'] = date('Y-m-d H:i:s');
      $property['updated_at'] = date('Y-m-d H:i:s');
      $property['fusioninventoryitem_id'] = $itemId;
      $tableItem = $this->table('fusioninventoryproperties');
      $tableItem->insert($property)
                ->save();
    }
  }

  private function insertType($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $table = $this->table('types');
    $table->insert($data)
          ->save();
    $id = $this->adapter->getConnection()->lastInsertId();
    $this->myData['types'][$data['name']] = [
      'id' => $id,
      'properties' => []
    ];
  }

  private function insertProperty($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $table = $this->table('properties');
    $table->insert($data)
          ->save();
    $id = $this->adapter->getConnection()->lastInsertId();
    $this->myData['properties'][$data['name']] = [
      'id'         => $id,
      'listvalues' => []
    ];
  }

  private function attachPropertyToType($typeName, $propertyName)
  {
    $data = [
      'type_id'     => $this->myData['types'][$typeName]['id'],
      'property_id' => $this->myData['properties'][$propertyName]['id'],
      'created_at'  => date('Y-m-d H:i:s'),
      'updated_at'  => date('Y-m-d H:i:s')
    ];

    $table = $this->table('property_type');
    $table->insert($data)
          ->save();
    $id = $this->adapter->getConnection()->lastInsertId();
    return $id;
  }

  private function insertRelationshiptypes($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $table = $this->table('relationshiptypes');
    $table->insert($data)
          ->save();
    $id = $this->adapter->getConnection()->lastInsertId();
    $this->myData['relationshiptypes'][$data['name']] = [
      'id' => $id
    ];
  }

  private function insertItem($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $table = $this->table('items');
    $table->insert($data)
          ->save();
    $id = $this->adapter->getConnection()->lastInsertId();
    $this->myData['items'][$data['name']] = [
      'id' => $id
    ];
  }

  private function attacheItemToitem($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $table = $this->table('item_item');
    $table->insert($data)
          ->save();
    $id = $this->adapter->getConnection()->lastInsertId();
    return $id;
/*
    $table->addColumn('parent_item_id', 'integer')
          ->addColumn('child_item_id', 'integer')
          ->addColumn('relationshiptype_id', 'integer', ['null' => true])
          ->addColumn('logical', 'boolean', ['default' => true])
          ->addColumn('physicalinternal', 'boolean', ['default' => false])
          ->addColumn('propagate', 'boolean', ['default' => false])
          ->addColumn('created_at', 'datetime')
          ->addColumn('updated_at', 'datetime', ['null' => true])
          ->addColumn('deleted_at', 'datetime', ['null' => true])

*/
  }

  private function createPropObject(
    $name,
    $internalname,
    $valuetype = 'string',
    $default = '',
    $unit = null,
    $listvalues = []
  )
  {
    $dataProp = new stdClass();
    $dataProp->name = $name;
    $dataProp->internalname = $internalname;
    $dataProp->valuetype = $valuetype;
    $dataProp->unit = $unit;
    $dataProp->default = $default;
    if ($valuetype == 'list')
    {
      $dataProp->listvalues = $listvalues;
    }
    return $dataProp;
  }
}
