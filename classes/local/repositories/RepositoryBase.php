<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version
 *
 * @package local_manage_enrollments
 * @copyright 2024 4Linux {@link https://www.lucasmendesdev.com.br}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_manage_enrollments\local\repositories;

use dml_exception;
use moodle_database;
use stdClass;

abstract class RepositoryBase
{

    /** @var string */
    private $table;

    /** @var moodle_database */
    protected $db;

    /**
     * @var bool
     */
    protected $insertReturnId = true;

    public function __construct(string $table, array $config = [])
    {
        global $DB;

        $this->table = trim($table);
        $this->parseConfig($config);
        $this->db = $DB;
    }

    final protected function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param stdClass $object
     * @return void
     * @throws dml_exception
     */
    public function save(stdClass $object): void
    {
        $object->timemodified = time();

        if (!empty($object->id)) {
            $this->db->update_record($this->table, $object);
            return;
        }

        $object->id = $this->db->insert_record($this->table, $object, $this->insertReturnId);
    }

    public function delete(stdClass $object): void
    {
        $this->db->delete_records($this->table, ['id' => $object->id]);
    }

    public function findById(int $id)
    {
        return $this->db->get_record(
            $this->getTable(),
            [
                'id' => $id,
            ]
        );
    }

    /**
     * @param string|array $fields
     * @param array|null $conditions
     * @param string $sort
     * @return array
     * @throws dml_exception
     */
    public function getAllToFilter($fields = 'id,name', array $conditions = null, string $sort = 'id ASC'): array
    {
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }

        return array_values($this->db->get_records($this->getTable(), $conditions, $sort, $fields));
    }

    private function parseConfig(array $config): void
    {
        if (array_key_exists('insert_return_id', $config)) {
            $this->insertReturnId = $config['insert_return_id'];
        }
    }

}
