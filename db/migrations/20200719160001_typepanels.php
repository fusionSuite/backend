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

final class Typepanels extends AbstractMigration
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
    $table = $this->table('typepanels');
    $table->addColumn('name', 'string')
          ->addColumn('icon', 'string', ['null' => true, 'default' => null])
          ->addColumn('position', 'integer', ['default' => 0])
          ->addColumn('displaytype', 'string', ['default' => 'default'])
          ->addColumn('type_id', 'integer')
          ->addColumn('created_at', 'datetime')
          ->addColumn('updated_at', 'datetime', ['null' => true])
          ->addIndex(['type_id', 'name'], ['unique' => true])
          ->create();
  }
}
