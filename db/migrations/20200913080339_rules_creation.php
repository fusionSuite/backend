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

final class RulesCreation extends AbstractMigration
{
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
    // create the table for rule
    $table = $this->table('rules');
    $table->addColumn('name', 'string')
          ->addColumn('type', 'string', ['default' => 'rewritefield'])
          ->addColumn('serialized', 'text', ['null' => true])
          ->addColumn('comment', 'text', ['null' => true])
          ->addColumn('created_at', 'datetime')
          ->addColumn('updated_at', 'datetime', ['null' => true])
          ->addColumn('deleted_at', 'datetime', ['null' => true])
          ->create();

    // create the table for rule criteria
    $table = $this->table('rulecriteria');
    $table->addColumn('name', 'string')
          ->addColumn('rule_id', 'integer')
          ->addColumn('field', 'string')
          ->addColumn('comparator', 'string')
          ->addColumn('values', 'text')
          ->addColumn('comment', 'text', ['null' => true])
          ->addColumn('created_at', 'datetime')
          ->addColumn('updated_at', 'datetime', ['null' => true])
          ->addColumn('deleted_at', 'datetime', ['null' => true])
          ->create();

    // create the table for rule action
    $table = $this->table('ruleactions');
    $table->addColumn('name', 'string')
          ->addColumn('rule_id', 'integer')
          ->addColumn('field', 'string', ['null' => true])
          ->addColumn('type', 'string')
          ->addColumn('values', 'text')
          ->addColumn('comment', 'text', ['null' => true])
          ->addColumn('created_at', 'datetime')
          ->addColumn('updated_at', 'datetime', ['null' => true])
          ->addColumn('deleted_at', 'datetime', ['null' => true])
          ->create();

    $this->createFusionInventoryRules();
  }

  private function createFusionInventoryRules()
  {
    // rule for determine the type (laptop, workstation, server...)
    $data = [
      'rule' => [
        'name'    => 'FusionInventory import - detect type of device',
        'type'    => 'fusioninventorygettype',
        'comment' => 'used to determine the type of the device to import, for exemple: laptop or server...'
      ],
      'criteria' => [
        [
          'name'       => 'laptop',
          'field'      => 'hardware.chassis_type',
          'comparator' => 'in',
          'values'     => ['laptop'],
          'comment'    => 'check the value of hardware/chassis_type'
        ]
      ],
      'actions' => [
        [
          'name'    => 'set type laptop',
          'field'   => null,
          'type'    => 'replace',
          'values'  => '2',
          'comment' => ''
        ]
      ]
    ];
    $this->insertRule($data);

    // rule for laptop
    $data = [
      'rule' => [
        'name'    => 'Search for laptop yet in DB (serialnumber)',
        'type'    => 'searchitem',
        'comment' => 'Search if a laptop with same serialnumber exists'
      ],
      'criteria' => [
        [
          'name'       => 'serial number',
          'field'      => 'input.serialnumber',
          'comparator' => '=',
          'values'     => 2.5,
          'comment'    => 'check the value of serialnumber'
        ]
      ],
      'actions' => [
        [
          'name'    => 'Import',
          'field'   => null,
          'type'    => 'import',
          'values'  => '',
          'comment' => ''
        ]
      ]
    ];
    $this->insertRule($data);

    // rule for laptop - have serial number
    $data = [
      'rule' => [
        'name'    => 'Import laptop serial number',
        'type'    => 'searchitem',
        'comment' => 'Import a laptop if have a serial number'
      ],
      'criteria' => [
        [
          'name'       => 'serial number',
          'field'      => 'input.serialnumber',
          'comparator' => 'regex',
          'values'     => "/.*/",
          'comment'    => 'check the value of serialnumber'
        ]
      ],
      'actions' => [
        [
          'name'    => 'Import',
          'field'   => null,
          'type'    => 'import',
          'values'  => '',
          'comment' => ''
        ]
      ]
    ];
    $this->insertRule($data);

    // rule for operatingsystem
    $data = [
      'rule' => [
        'name'    => 'Search for operating system yet in DB',
        'type'    => 'searchitem',
        'comment' => 'Search if a operating system yet exists'
      ],
      'criteria' => [
        [
          'name'       => 'name',
          'field'      => 'input.name',
          'comparator' => '=',
          'values'     => 8.0,
          'comment'    => 'check the value of name'
        ]
      ],
      'actions' => [
        [
          'name'    => 'Import',
          'field'   => null,
          'type'    => 'import',
          'values'  => '',
          'comment' => ''
        ]
      ]
    ];
    $this->insertRule($data);

    // rule for antivirus

    // rule for batteries

    // rule for controllers
    $data = [
      'rule' => [
        'name'    => 'Search for controllers yet in DB',
        'type'    => 'searchitem',
        'comment' => 'Search if a controller yet exists'
      ],
      'criteria' => [
        [
          'name'       => 'name',
          'field'      => 'input.name',
          'comparator' => '=',
          'values'     => 9.0,
          'comment'    => 'check the value of name'
        ],
        [
          'name'       => 'manufacturer',
          'field'      => 'input.manufacturer',
          'comparator' => '=',
          'values'     => 9.8,
          'comment'    => 'check the value of manufacturer'
        ]
      ],
      'actions' => [
        [
          'name'    => 'Import',
          'field'   => null,
          'type'    => 'import',
          'values'  => '',
          'comment' => ''
        ]
      ]
    ];
    $this->insertRule($data);

    // rule for cpus
    $data = [
      'rule' => [
        'name'    => 'Search for processors yet in DB',
        'type'    => 'searchitem',
        'comment' => 'Search if a processor yet exists'
      ],
      'criteria' => [
        [
          'name'       => 'name',
          'field'      => 'input.name',
          'comparator' => '=',
          'values'     => 6.0,
          'comment'    => 'check the value of name'
        ],
        [
          'name'       => 'serial number',
          'field'      => 'input.serialnumber',
          'comparator' => '=',
          'values'     => 6.5,
          'comment'    => 'check the value of serial number'
        ]
      ],
      'actions' => [
        [
          'name'    => 'Import',
          'field'   => null,
          'type'    => 'import',
          'values'  => '',
          'comment' => ''
        ]
      ]
    ];
    $this->insertRule($data);

    // rule for softwares
    $data = [
      'rule' => [
        'name'    => 'Search for softwares yet in DB',
        'type'    => 'searchitem',
        'comment' => 'Search if a software yet exists'
      ],
      'criteria' => [
        [
          'name'       => 'name',
          'field'      => 'input.name',
          'comparator' => '=',
          'values'     => 7.0,
          'comment'    => 'check the value of name'
        ],
        [
          'name'       => 'manufacturer',
          'field'      => 'input.manufacturer',
          'comparator' => '=',
          'values'     => 7.8,
          'comment'    => 'check the value of manufacturer'
        ]
      ],
      'actions' => [
        [
          'name'    => 'Import',
          'field'   => null,
          'type'    => 'import',
          'values'  => '',
          'comment' => ''
        ]
      ]
    ];
    $this->insertRule($data);
  }

  private function insertRule($data)
  {
    $data['rule']['created_at'] = date('Y-m-d H:i:s');
    $table = $this->table('rules');
    $table->insert($data['rule'])
          ->save();
    $ruleId = $this->adapter->getConnection()->lastInsertId();
    foreach ($data['criteria'] as $criterium)
    {
      $criterium['rule_id'] = $ruleId;
      $this->insertCriterium($criterium);
    }
    foreach ($data['actions'] as $action)
    {
      $action['rule_id'] = $ruleId;
      $this->insertAction($action);
    }
    return true;
  }

  private function insertCriterium($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    if (is_array($data['values'])) {
      $data['values'] = json_encode($data['values']);
    }
    $table = $this->table('rulecriteria');
    $table->insert($data)
          ->save();
    return $this->adapter->getConnection()->lastInsertId();
  }

  private function insertAction($data)
  {
    $data['created_at'] = date('Y-m-d H:i:s');
    $table = $this->table('ruleactions');
    $table->insert($data)
          ->save();
    return $this->adapter->getConnection()->lastInsertId();
  }

}
