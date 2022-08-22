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
use Phinx\Db\Adapter\MysqlAdapter;

final class Properties extends AbstractMigration
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
    // create the table
    $table = $this->table('properties');
    $table->addColumn('name', 'string')
          ->addColumn('internalname', 'string')
          ->addColumn('organization_id', 'integer', ['default' => 1])
          ->addColumn('sub_organization', 'boolean', ['default' => false])
          ->addColumn('valuetype', 'string')
          ->addColumn('regexformat', 'string', ['null' => true])
          ->addColumn('unit', 'string', ['null' => true])
          ->addColumn('default_integer', 'integer', ['null' => true])
          ->addColumn('default_decimal', 'decimal', ['null' => true, 'precision' => 10, 'scale' => 6])
          ->addColumn('default_string', 'string', ['null' => true])
          ->addColumn('default_text', 'text', ['null' => true, 'limit' => MysqlAdapter::TEXT_LONG])
          ->addColumn('default_boolean', 'boolean', ['null' => true])
          ->addColumn('default_datetime', 'datetime', ['null' => true])
          ->addColumn('default_date', 'date', ['null' => true])
          ->addColumn('default_time', 'time', ['null' => true])
          ->addColumn('default_number', 'integer', ['null' => true])
          ->addColumn('default_itemlink', 'integer', ['null' => true])
          ->addColumn('default_itemlinks', 'text', ['null' => true])
          ->addColumn('default_typelink', 'integer', ['null' => true])
          ->addColumn('default_typelinks', 'string', ['null' => true])
          ->addColumn('default_propertylink', 'integer', ['null' => true])
          ->addColumn('default_list', 'integer', ['null' => true])
          ->addColumn('default_password', 'string', ['null' => true])
          ->addColumn('default_passwordhash', 'string', ['null' => true])
          ->addColumn('description', 'text', ['null' => true])
          ->addColumn('created_at', 'datetime')
          ->addColumn('updated_at', 'datetime', ['null' => true])
          ->addColumn('deleted_at', 'datetime', ['null' => true])
          ->addColumn('created_by', 'integer')
          ->addColumn('updated_by', 'integer', ['null' => true])
          ->addColumn('deleted_by', 'integer', ['null' => true])
          ->addColumn('canbenull', 'boolean', ['default' => true])
          ->addColumn('setcurrentdate', 'boolean', ['default' => false])
          ->addIndex(['internalname'], ['unique' => true])
          ->addIndex('deleted_at')
          ->create();
  }
}
