<?php

namespace PHPageBuilder\Repositories;

use PHPageBuilder\Core\DB;

/**
 * Class BaseRepository
 *
 * The base repository passes CRUD calls to DB and initialises class instances for records returned from DB.
 *
 * @package PHPageBuilder\Repositories
 */
class BaseRepository
{
    /**
     * @var DB $db
     */
    protected $db;

    /**
     * The database table of this repository.
     * Note: do not replace this value with user input.
     *
     * @var string
     */
    protected $table;

    /**
     * The class that represents each record of this repository's table.
     *
     * @var string
     */
    protected $class;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        global $phpb_db;
        $this->db = $phpb_db;

        // apply the configured prefix to the table set in the superclass and remove non-alphanumeric characters
        $this->table = phpb_config('storage.database.prefix') . $this->removeNonAlphaNumeric($this->table);
    }

    /**
     * Create a new instance using the given data.
     *
     * @param array $data
     * @return object|null
     */
    protected function create(array $data)
    {
        $columns = array_keys($data);
        foreach ($columns as &$column) {
            $column = $this->removeNonAlphaNumeric($column);
        }
        $columns = implode(', ', $columns);
        $questionMarks = implode(', ', array_fill(0, sizeof($data), '?'));

        $this->db->query(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$questionMarks})",
            array_values($data)
        );

        $id = $this->db->lastInsertId();
        if ($id) {
            return $this->findWithId($id);
        }
        return null;
    }

    /**
     * Update the record with the given id with the given updated data.
     *
     * @param $instance
     * @param array $data
     * @return bool
     */
    protected function update($instance, array $data)
    {
        $set = '';
        foreach ($data as $column => $value) {
            if ($set !== '') {
                $set .= ', ';
            }
            $set .= $this->removeNonAlphaNumeric($column) . '=?';
        }

        $values = array_values($data);
        $values[] = $instance->id ?? $instance->getId();

        return $this->db->query(
            "UPDATE {$this->table} SET {$set} WHERE id=?",
            $values
        );
    }

    /**
     * Remove the given instance from the database.
     *
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->db->query(
            "DELETE FROM {$this->table} WHERE id=?",
            [$id]
        );
    }

    /**
     * Remove all instances from the database that satisfy the given condition.
     *
     * @param string $column
     * @param $value
     * @return bool
     */
    public function destroyWhere(string $column, $value)
    {
        $column = $this->removeNonAlphaNumeric($column);
        return $this->db->query(
            "DELETE FROM {$this->table} WHERE {$column}=?",
            [$value]
        );
    }

    /**
     * Remove all instances from the database.
     *
     * @return bool
     */
    public function destroyAll()
    {
        return $this->db->query(
            "DELETE FROM {$this->table}"
        );
    }

    /**
     * Return an array of all pages.
     *
     * @param array|string $columns
     * @return array
     */
    public function getAll($columns = '*')
    {
        if (is_array($columns)) {
            foreach ($columns as &$column) {
                $column = $this->removeNonAlphaNumeric($column);
            }
        }
        return $this->createInstances($this->db->all($this->table, $columns));
    }

    /**
     * Return the instance with the given id, or null.
     *
     * @param string $id
     * @return object|null
     */
    public function findWithId($id)
    {
        return $this->createInstance($this->db->findWithId($this->table, $id));
    }

    /**
     * Return the instances for which the given condition holds.
     *
     * @param string $column         do NOT pass user input here
     * @param string $value
     * @return array
     */
    public function findWhere($column, $value)
    {
        $column = $this->removeNonAlphaNumeric($column);
        return $this->createInstances($this->db->select(
            "SELECT * FROM {$this->table} WHERE {$column} = ?",
            [$value]
        ));
    }

    /**
     * Remove any non-alphanumeric characters.
     *
     * @param string $string
     * @return string|null
     */
    protected function removeNonAlphaNumeric(string $string)
    {
        return preg_replace('/\W*/', '', $string);
    }

    /**
     * Create an instance using the first record.
     *
     * @param array $records
     * @return object|null
     */
    protected function createInstance(array $records)
    {
        $instances = $this->createInstances($records);
        if (empty($instances)) {
            return null;
        }
        return $instances[0];
    }

    /**
     * For each record create an instance.
     *
     * @param array $records
     * @return array
     */
    protected function createInstances(array $records)
    {
        $result = [];

        if (empty($this->class)) {
            return $records;
        }

        foreach ($records as $record) {
            $instance = new $this->class;
            if (method_exists($instance, 'setData')) {
                $data = [];
                foreach($record as $k => $v) {
                    $data[$k] = $v;
                }
                $instance->setData($data);
            } else {
                foreach($record as $k => $v) {
                    $instance->$k = $v;
                }
            }
            $result[] = $instance;
        }

        return $result;
    }
}
