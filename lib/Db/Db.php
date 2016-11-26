<?php

namespace Lib\Db;

/**
 * Db class
 * 
 * @autor Niesuch
 */
class Db {

    /**
     * Login data to database
     * @var type 
     */
    private $_db_data;

    /**
     * Database connect
     * @var type 
     */
    private $_db_connect;

    /**
     * Selected database
     * @var type 
     */
    private $_db_selected;

    /**
     * Error message
     * @var type 
     */
    private $_error_message;

    /**
     * Class construct
     * @param type $db_data
     */
    public function __construct($db_data) {
        $this->_db_data = $db_data;
        $this->_db_connect();
    }

    /**
     * Connect with database
     */
    private function _db_connect() {
        $this->_db_connect = mysqli_connect($this->_db_data['host'], $this->_db_data['user'], $this->_db_data['password']);

        if (!$this->_db_connect) {
            $this->_error_message = 'Cannot connect to the database because: ' . mysqli_connect_error();
        }
    }

    /**
     * Select database
     * @param type $db_selected
     */
    public function db_select($db_selected) {
        $this->_db_selected = $db_selected;

        if ($this->_db_connect && $this->_db_selected) {
            $select_db = mysqli_select_db($this->_db_connect, $this->_db_selected);

            if (!$select_db) {
                $this->_error_message = 'Cannot select database because: ' . $this->db_error();
            }
        }
    }

    /**
     * Database query
     * @param type $sql
     * @return type
     */
    public function db_query($sql) {
        $query = mysqli_query($this->_db_connect, $sql);

        if (!$query) {
            $this->_error_message = "Error in DB operation:<br>\n" . $this->db_error() . "<br> \n$sql";
        }

        return $query;
    }

    /**
     * Get data from database and return it in array
     * @param type $sql
     * @return type
     */
    public function db_array($sql) {
        $query = $this->db_query($sql);
        $result = array();

        if (!$query) {
            return null;
        }

        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * Begin transaction
     */
    public function db_begin_transaction() {
        $sql = "START TRANSACTION;";

        $this->db_query($sql);
    }

    /**
     * Commit transaction
     */
    public function db_commit() {
        $sql = "COMMIT;";

        $this->db_query($sql);
    }

    /**
     * Rollback transaction
     */
    public function db_rollback() {
        $sql = "ROLLBACK;";

        $this->db_query($sql);
    }

    /**
     * Return DB error
     * @return type
     */
    public function db_error() {
        return mysqli_error($this->_db_connect);
    }

    /**
     * Insert array to table
     * @param type $table_name
     * @param type $array
     */
    public function insert($table_name, $array) {
        $columns = array_keys($array);
        $values = array_values($array);

        $sql = "INSERT INTO $table_name (" . implode(',', $columns) . ") VALUES (\"" . implode("\", \"", $values) . "\" )";

        $this->db_query($sql);
    }

    /**
     * Return string with sql
     * @param type $table
     * @param type $data
     * @param type $settings
     * @return string
     */
    public function get_select($table, $data, $settings = null) {
        $columns = (isset($settings['array_with_value']) 
                && !$settings['array_with_value']) ? $data : array_keys($data);
        $dist = (isset($settings['array_with_value']) 
                && $settings['distinct']) ? "DISTINCT" : "";
        
        $sql = "SELECT $dist " . implode(',', $columns) . " FROM " . $table;

        return $sql;
    }

    /**
     * Get method
     * @param type $property
     * @return type
     */
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * Set method
     * @param type $property
     * @param type $value
     */
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

}
