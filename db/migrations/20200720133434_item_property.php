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

final class ItemProperty extends AbstractMigration
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
    $table = $this->table('item_property');
    $table->addColumn('item_id', 'integer')
          ->addColumn('property_id', 'integer')
          ->addColumn('value_integer', 'integer', ['null' => true])
          ->addColumn('value_decimal', 'decimal', ['null' => true, 'precision' => 10, 'scale' => 6])
          ->addColumn('value_string', 'string', ['null' => true])
          ->addColumn('value_text', 'text', ['null' => true, 'limit' => MysqlAdapter::TEXT_LONG])
          ->addColumn('value_boolean', 'boolean', ['null' => true])
          ->addColumn('value_datetime', 'datetime', ['null' => true])
          ->addColumn('value_date', 'date', ['null' => true])
          ->addColumn('value_time', 'time', ['null' => true])
          ->addColumn('value_number', 'integer', ['null' => true])
          ->addColumn('value_itemlink', 'integer', ['null' => true])
          ->addColumn('value_itemlinks', 'integer', ['null' => true])
          ->addColumn('value_typelink', 'integer', ['null' => true])
          ->addColumn('value_typelinks', 'integer', ['null' => true])
          ->addColumn('value_propertylink', 'integer', ['null' => true])
          ->addColumn('value_list', 'string', ['null' => true])
          ->addColumn('value_password', 'string', ['null' => true])
          ->addColumn('value_passwordhash', 'string', ['null' => true])
          ->addColumn('byfusioninventory', 'boolean', ['default' => false])
          ->addColumn('created_at', 'datetime')
          ->addColumn('updated_at', 'datetime', ['null' => true])
          ->addIndex(['property_id', 'item_id', 'value_itemlink'], ['unique' => true])
          ->create();
  }
}
