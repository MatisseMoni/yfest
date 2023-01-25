<?php

namespace YOOtheme\Joomla;

use Joomla\Database\DatabaseDriver;
use YOOtheme\Database\AbstractDatabase;

class Database extends AbstractDatabase
{
    /**
     * @var DatabaseDriver
     */
    protected $db;

    /**
     * Constructor.
     *
     * @param DatabaseDriver $db
     */
    public function __construct($db)
    {
        $this->db = $db;
        $this->driver = $db->getName();
        $this->prefix = $db->getPrefix();
    }

    /**
     * @inheritdoc
     */
    public function loadResult($statement, array $params = [])
    {
        return $this->db->setQuery($this->prepareQuery($statement, $params))->loadResult();
    }

    /**
     * @inheritdoc
     */
    public function fetchAll($statement, array $params = [])
    {
        return $this->db->setQuery($this->prepareQuery($statement, $params))->loadAssocList();
    }

    /**
     * @inheritdoc
     */
    public function fetchAssoc($statement, array $params = [])
    {
        return $this->db->setQuery($this->prepareQuery($statement, $params))->loadAssoc();
    }

    /**
     * @inheritdoc
     */
    public function fetchArray($statement, array $params = [])
    {
        return $this->db->setQuery($this->prepareQuery($statement, $params))->loadRow();
    }

    /**
     * @inheritdoc
     */
    public function executeQuery($query, array $params = [])
    {
        $result = $this->db->setQuery($this->prepareQuery($query, $params))->execute();

        return $result !== false ? $this->db->getAffectedRows() : false;
    }

    /**
     * @inheritdoc
     */
    public function insert($table, $data)
    {
        $fields = implode(', ', $this->db->quoteName(array_keys($data)));
        $values = implode(
            ', ',
            array_map(function ($field) {
                return ":$field";
            }, array_keys($data))
        );

        return $this->executeQuery("INSERT INTO $table ($fields) VALUES ($values)", $data);
    }

    /**
     * @inheritdoc
     */
    public function update($table, $data, $identifier)
    {
        $fields = implode(
            ', ',
            array_map(function ($field) {
                return "{$this->db->quoteName($field)} = :$field";
            }, array_keys($data))
        );
        $where = implode(
            ' AND ',
            array_map(function ($id) {
                return "{$this->db->quoteName($id)} = :$id";
            }, array_keys($identifier))
        );

        return $this->executeQuery(
            "UPDATE $table SET $fields WHERE $where",
            array_merge($data, $identifier)
        );
    }

    /**
     * @inheritdoc
     */
    public function delete($table, $identifier)
    {
        $where = implode(
            ' AND ',
            array_map(function ($id) {
                return "{$this->db->quoteName($id)} = :$id";
            }, array_keys($identifier))
        );

        return $this->executeQuery("DELETE FROM $table WHERE $where", $identifier);
    }

    /**
     * @inheritdoc
     */
    public function escape($text)
    {
        return "'{$this->db->escape($text)}'";
    }

    /**
     * @inheritdoc
     */
    public function lastInsertId()
    {
        return $this->db->insertid();
    }
}
