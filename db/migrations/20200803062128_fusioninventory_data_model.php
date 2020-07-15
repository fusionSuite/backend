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

final class FusioninventoryDataModel extends AbstractMigration
{
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

    // ***** Create relationships ***** //
    $this->insertRelationshiptypes([
      'name' => 'As internal component'
    ]);

    // ***** Create properties ***** //

    // Create Address
    $dataProp = [
      'name'      => 'Address',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Postal code
    $dataProp = [
      'name'      => 'Postal code',
      'valuetype' => 'integer',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create City
    $dataProp = [
      'name'      => 'City',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Country
    $dataProp = [
      'name'      => 'Country',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Serial number
    $dataProp = [
      'name'      => 'Serial number',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Inventory number
    $dataProp = [
      'name'      => 'Inventory number',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Screen size
    $dataProp = [
      'name'      => 'Screen size',
      'valuetype' => 'integer',
      'unit'      => '"'
    ];
    $this->insertProperty($dataProp);

    // Create Manufacturer
    $dataProp = [
      'name'      => 'Manufacturer',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Model
    $dataProp = [
      'name'      => 'Model',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Type
    $dataProp = [
      'name'      => 'Type',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Version
    $dataProp = [
      'name'      => 'Version',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Buy date
    $dataProp = [
      'name'      => 'Buy date',
      'valuetype' => 'date',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Manufacturing date
    $dataProp = [
      'name'      => 'Manufacturing date',
      'valuetype' => 'date',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Warrantly duration
    $dataProp = [
      'name'      => 'Warrantly duration',
      'valuetype' => 'integer',
      'unit'      => 'months'
    ];
    $this->insertProperty($dataProp);

    // Create Date
    $dataProp = [
      'name'      => 'Date',
      'valuetype' => 'date',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Enabled
    $dataProp = [
      'name'      => 'Enabled',
      'valuetype' => 'boolean',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Uptodate
    $dataProp = [
      'name'      => 'Uptodate',
      'valuetype' => 'boolean',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Base version
    $dataProp = [
      'name'      => 'Base version',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Chemistry
    $dataProp = [
      'name'      => 'Chemistry',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Capacity
    $dataProp = [
      'name'      => 'Capacity',
      'valuetype' => 'integer',
      'unit'      => 'mWh'
    ];
    $this->insertProperty($dataProp);

    // Create Voltage
    $dataProp = [
      'name'      => 'Voltage',
      'valuetype' => 'integer',
      'unit'      => 'mV'
    ];
    $this->insertProperty($dataProp);

    // Create Core
    $dataProp = [
      'name'      => 'Core',
      'valuetype' => 'integer',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Thread
    $dataProp = [
      'name'      => 'Thread',
      'valuetype' => 'integer',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Speed
    $dataProp = [
      'name'      => 'Speed',
      'valuetype' => 'integer',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Architecture
    $dataProp = [
      'name'      => 'Architecture',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Description
    $dataProp = [
      'name'      => 'Description',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Folder
    $dataProp = [
      'name'      => 'Folder',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create URL
    $dataProp = [
      'name'      => 'URL',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create URL help
    $dataProp = [
      'name'      => 'URL help',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create GUID
    $dataProp = [
      'name'      => 'GUID',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Software category
    $dataProp = [
      'name'      => 'Software category',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Uninstall command
    $dataProp = [
      'name'      => 'Uninstall command',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Installation date
    $dataProp = [
      'name'      => 'Installation date',
      'valuetype' => 'date',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Information source (for software)
    $dataProp = [
      'name'      => 'Information source',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Last boot datetime
    $dataProp = [
      'name'      => 'Last boot',
      'valuetype' => 'date',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Service pack
    $dataProp = [
      'name'      => 'Service pack',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Complete name
    $dataProp = [
      'name'      => 'Complete name',
      'valuetype' => 'string',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);

    // Create Kernel version
    $dataProp = [
      'name'      => 'Kernel version',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list

    // Create Kernel name
    $dataProp = [
      'name'      => 'Kernel name',
      'valuetype' => 'list',
      'unit'      => null
    ];
    $this->insertProperty($dataProp);
    // TODO Manage the list


    // ***** Create types ***** //

    // Create organization

    $this->insertType(['name' => 'Organization']);

      // Attach properties
      $this->attachPropertyToType('Organization', 'Address');
      $this->attachPropertyToType('Organization', 'Postal code');
      $this->attachPropertyToType('Organization', 'City');
      $this->attachPropertyToType('Organization', 'Country');

    // Create Laptop
    $this->insertType(['name' => 'Laptop', 'modeling' => 'physical']);

      // Attach properties
      $this->attachPropertyToType('Laptop', 'Serial number');
      $this->attachPropertyToType('Laptop', 'Model');
      $this->attachPropertyToType('Laptop', 'Type');
      $this->attachPropertyToType('Laptop', 'Manufacturer');


    // Create BIOS
    $this->insertType(['name' => 'BIOS', 'modeling' => 'physical']);

      // Attach properties
      $this->attachPropertyToType('BIOS', 'Version');
      $this->attachPropertyToType('BIOS', 'Date');

    // Create Antivirus
    $this->insertType(['name' => 'Antivirus', 'modeling' => 'physical']);

      // Attach properties
      $this->attachPropertyToType('Antivirus', 'Serial number');
      $this->attachPropertyToType('Antivirus', 'Enabled');
      $this->attachPropertyToType('Antivirus', 'Uptodate');
      $this->attachPropertyToType('Antivirus', 'Base version');
      $this->attachPropertyToType('Antivirus', 'Version');
      $this->attachPropertyToType('Antivirus', 'Manufacturer');

    // Create Battery
    $this->insertType(['name' => 'Battery', 'modeling' => 'physical']);

      // Attach properties
      $this->attachPropertyToType('Battery', 'Chemistry');
      $this->attachPropertyToType('Battery', 'Manufacturing date');
      $this->attachPropertyToType('Battery', 'Serial number');
      $this->attachPropertyToType('Battery', 'Manufacturer');
      $this->attachPropertyToType('Battery', 'Capacity');
      $this->attachPropertyToType('Battery', 'Voltage');

    // Create Processor
    $this->insertType(['name' => 'Processor', 'modeling' => 'physical']);

      // Attach properties
      $this->attachPropertyToType('Processor', 'Serial number');
      $this->attachPropertyToType('Processor', 'Manufacturer');
      $this->attachPropertyToType('Processor', 'Model');
      $this->attachPropertyToType('Processor', 'Core');
      $this->attachPropertyToType('Processor', 'Thread');
      $this->attachPropertyToType('Processor', 'Speed');
      $this->attachPropertyToType('Processor', 'Architecture');

    // Create Software
    $this->insertType(['name' => 'Software', 'modeling' => 'physical']);

      // Attach properties
      $this->attachPropertyToType('Software', 'Version');
      $this->attachPropertyToType('Software', 'Description');
      $this->attachPropertyToType('Software', 'Folder');
      $this->attachPropertyToType('Software', 'Information source');
      $this->attachPropertyToType('Software', 'URL help');
      $this->attachPropertyToType('Software', 'Installation date');
      $this->attachPropertyToType('Software', 'Manufacturer');
      $this->attachPropertyToType('Software', 'Uninstall command');
      $this->attachPropertyToType('Software', 'URL');
      $this->attachPropertyToType('Software', 'GUID');
      $this->attachPropertyToType('Software', 'Architecture');
      $this->attachPropertyToType('Software', 'Software category');

    // create Operating system
    $this->insertType(['name' => 'Operating system', 'modeling' => 'physical']);

      // Attach properties
      $this->attachPropertyToType('Operating system', 'Kernel name');
      $this->attachPropertyToType('Operating system', 'Kernel version');
      $this->attachPropertyToType('Operating system', 'Complete name');
      $this->attachPropertyToType('Operating system', 'Version');
      $this->attachPropertyToType('Operating system', 'Service pack');
      $this->attachPropertyToType('Operating system', 'Installation date');
      $this->attachPropertyToType('Operating system', 'Architecture');
      $this->attachPropertyToType('Operating system', 'Last boot');

    // create Controller
    $this->insertType(['name' => 'Controller', 'modeling' => 'physical']);

      // Attach properties
      $this->attachPropertyToType('Controller', 'Manufacturer');


  }



  private function createFusionInventoryMapping()
  {
    // Create data model for FusionInventory

    $data = [
      'item'       => [
        'querytype'              => 'computerinventory',
        'markup'                 => '',
        'markup_name'            => 'hardware/name',
        'type_id'                => $this->myData['types']['Laptop']['id'],
        'fusioninventoryitem_id' => 0,
      ],
      'properties' => [
        [
          'markup'                 => 'bios/ssn',
          'property_id'            => $this->myData['properties']['Serial number']['id']
        ],
        [
          'markup'                 => 'hardware/chassis_type',
          'property_id'            => $this->myData['properties']['Type']['id']
        ],
        [
          'markup'                 => 'bios/smodel',
          'property_id'            => $this->myData['properties']['Model']['id']
        ],
        [
          'markup'                 => 'bios/smanufacturer',
          'property_id'            => $this->myData['properties']['Manufacturer']['id']
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
        'type_id'                => $this->myData['types']['Operating system']['id'],
        'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
      ],
      'properties' => [
        [
          'markup'                 => 'operatingsystem/kernel_name',
          'property_id'            => $this->myData['properties']['Kernel name']['id']
        ],
        [
          'markup'                 => 'operatingsystem/kernel_version',
          'property_id'            => $this->myData['properties']['Kernel version']['id']
        ],
        [
          'markup'                 => 'operatingsystem/full_name',
          'property_id'            => $this->myData['properties']['Complete name']['id']
        ],
        [
          'markup'                 => 'operatingsystem/version',
          'property_id'            => $this->myData['properties']['Version']['id']
        ],
        [
          'markup'                 => 'operatingsystem/service_pack',
          'property_id'            => $this->myData['properties']['Service pack']['id']
        ],
        [
          'markup'                 => 'operatingsystem/install_date',
          'property_id'            => $this->myData['properties']['Installation date']['id']
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
          'property_id'            => $this->myData['properties']['Architecture']['id']
        ],
        [
          'markup'                 => 'operatingsystem/boot_time',
          'property_id'            => $this->myData['properties']['Last boot']['id']
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
        'type_id'                => $this->myData['types']['Antivirus']['id'],
        'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
      ],
      'properties' => [
        [
          'markup'                 => 'antivirus/company',
          'property_id'            => $this->myData['properties']['Manufacturer']['id']
        ],
        [
          'markup'                 => 'antivirus/version',
          'property_id'            => $this->myData['properties']['Version']['id']
        ],
        [
          'markup'                 => 'antivirus/enabled',
          'property_id'            => $this->myData['properties']['Enabled']['id']
        ],
        [
          'markup'                 => 'antivirus/uptodate',
          'property_id'            => $this->myData['properties']['Uptodate']['id']
        ],
        [
          'markup'                 => 'antivirus/base_version',
          'property_id'            => $this->myData['properties']['Base version']['id']
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
        'type_id'                => $this->myData['types']['Battery']['id'],
        'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
      ],
      'properties' => [
        [
          'markup'                 => 'batteries/chemistry',
          'property_id'            => $this->myData['properties']['Chemistry']['id']
        ],
        [
          'markup'                 => 'batteries/data',
          'property_id'            => $this->myData['properties']['Manufacturing date']['id']
        ],
        [
          'markup'                 => 'batteries/serial',
          'property_id'            => $this->myData['properties']['Serial number']['id']
        ],
        [
          'markup'                 => 'batteries/manufacturer',
          'property_id'            => $this->myData['properties']['Manufacturer']['id']
        ],
        [
          'markup'                 => 'batteries/capacity',
          'property_id'            => $this->myData['properties']['Capacity']['id']
        ],
        [
          'markup'                 => 'batteries/voltage',
          'property_id'            => $this->myData['properties']['Voltage']['id']
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
        'type_id'                => $this->myData['types']['Controller']['id'],
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
          'property_id'            => $this->myData['properties']['Manufacturer']['id']
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
        'type_id'                => $this->myData['types']['Processor']['id'],
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
          'property_id'            => $this->myData['properties']['Core']['id']
        ],
        // [
        //   'markup'                 => 'cpus/corecount',
        //   'property_id'            => 0
        // ],
        [
          'markup'                 => 'cpus/description',
          'property_id'            => $this->myData['properties']['Description']['id']
        ],
        [
          'markup'                 => 'cpus/manufacturer',
          'property_id'            => $this->myData['properties']['Manufacturer']['id']
        ],
        [
          'markup'                 => 'cpus/thread',
          'property_id'            => $this->myData['properties']['Thread']['id']
        ],
        [
          'markup'                 => 'cpus/serial',
          'property_id'            => $this->myData['properties']['Serial number']['id']
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
          'property_id'            => $this->myData['properties']['Model']['id']
        ],
        [
          'markup'                 => 'cpus/speed',
          'property_id'            => $this->myData['properties']['Speed']['id']
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
          'property_id'            => $this->myData['properties']['Architecture']['id']
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
        'type_id'                => $this->myData['types']['Software']['id'],
        'fusioninventoryitem_id' => $this->myData['fusioninventoryitems']['']['id']
      ],
      'properties' => [
        [
          'markup'                 => 'softwares/version',
          'property_id'            => $this->myData['properties']['Version']['id']
        ],
        [
          'markup'                 => 'softwares/comments',
          'property_id'            => $this->myData['properties']['Description']['id']
        ],
      //   [
      //     'markup'                 => 'softwares/filesize',
      //     'property_id'            => $this->myData['properties']['']['id']
      //   ],
        [
          'markup'                 => 'softwares/folder',
          'property_id'            => $this->myData['properties']['Folder']['id']
        ],
        [
          'markup'                 => 'softwares/from', // information source, ie 'registry', 'rpm', 'deb', etc.
          'property_id'            => $this->myData['properties']['Information source']['id']
        ],
        [
          'markup'                 => 'softwares/helplink',
          'property_id'            => $this->myData['properties']['URL help']['id']
        ],
        [
          'markup'                 => 'softwares/installdate',
          'property_id'            => $this->myData['properties']['Installation date']['id']
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
          'property_id'            => $this->myData['properties']['Manufacturer']['id']
        ],
        [
          'markup'                 => 'softwares/uninstall_string',
          'property_id'            => $this->myData['properties']['Uninstall command']['id']
        ],
        [
          'markup'                 => 'softwares/url_info_about',
          'property_id'            => $this->myData['properties']['URL']['id']
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
          'property_id'            => $this->myData['properties']['GUID']['id']
        ],
        [
          'markup'                 => 'softwares/arch',
          'property_id'            => $this->myData['properties']['Architecture']['id']
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
          'property_id'            => $this->myData['properties']['Software category']['id']
        ],
      ]
    ];
    $this->insertFusioninventoryData($data);







  }

  private function insertFusioninventoryData($data)
  {
    $data['item']['created_at'] = date('Y-m-d H:i:s');
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
      $property['fusioninventoryitem_id'] = $itemId;
      $tableItem = $this->table('fusioninventoryproperties');
      $tableItem->insert($property)
                ->save();
    }
  }

  private function insertType($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');

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
      'created_at'  => date('Y-m-d H:i:s')
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

}
