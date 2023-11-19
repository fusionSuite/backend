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

final class Items extends AbstractMigration
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
    $table = $this->table('items');
    $table->addColumn('name', 'string')
          ->addColumn('id_bytype', 'integer')
          ->addColumn('type_id', 'integer')
          ->addColumn('organization_id', 'integer', ['default' => 1])
          ->addColumn('sub_organization', 'boolean', ['default' => false])
          ->addColumn('owner_user_id', 'integer', ['null' => true])
          ->addColumn('owner_group_id', 'integer', ['null' => true])
          ->addColumn('state_id', 'integer', ['null' => true])
          ->addColumn('byfusioninventory', 'boolean', ['default' => false])
          ->addColumn('parent_id', 'integer', ['null' => true])
          ->addColumn('treepath', 'string', ['null' => true])
          ->addColumn('created_at', 'datetime')
          ->addColumn('updated_at', 'datetime', ['null' => true])
          ->addColumn('deleted_at', 'datetime', ['null' => true])
          ->addColumn('created_by', 'integer')
          ->addColumn('updated_by', 'integer', ['null' => true])
          ->addColumn('deleted_by', 'integer', ['null' => true])
          ->addIndex('type_id')
          ->addIndex('id_bytype')
          ->addIndex(['id', 'deleted_at'])
          ->addIndex(['type_id', 'id_bytype'], ['unique' => true])
          ->addIndex(['type_id', 'id_bytype', 'deleted_at'])
          ->addIndex(['type_id', 'treepath', 'deleted_at'])
          ->addIndex(['type_id', 'organization_id', 'sub_organization', 'deleted_at'])
          ->addIndex('treepath')
          ->create();
  }
}
