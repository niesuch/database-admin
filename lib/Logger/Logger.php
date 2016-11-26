<?php

namespace Lib\Logger;

use Config\Application\Adapter as Adapter;

/**
 * Logger class
 * 
 * @autor Niesuch
 */
class Logger extends Adapter {

    /**
     * Log name
     * @var type 
     */
    private $_name;

    /**
     * Log
     * @var type 
     */
    private $_log;

    /**
     * Type of logs
     */
    const TYPE_OF_LOGS = array(LOGS_LOG_QUERY, LOGS_LOG_ERROR, LOGS_LOG_HISTORY_QUERY);

    /**
     * Class construct
     * @param type $name
     * @param type $db
     */
    public function __construct($name) {
        parent::__construct();

        if (!in_array($name, self::TYPE_OF_LOGS)) {
            trigger_error("Log $name not defined", E_USER_ERROR);
        }
        
        $config = $this->getConfig();
        $fields = explode(",", $config[$name]['fields']);

        $this->_name = $name;
        $this->_log = $config[$name];        
        $this->_log['fields'] = array_fill_keys($fields, '');
    }

    /**
     * Save query in log defined by user
     */
    public function save() {
        if ($this->_log) {
            $this->_db->db_select($this->_log['base']);
            $this->_db->insert($this->_log['table'], $this->_log['fields']);
        }
    }

    /**
     * Set values
     * @param type $array_values
     */
    public function set_values($array_values) {
        $columns = array_keys($this->_log['fields']);
        $this->_log['fields'] = array_combine($columns, $array_values);
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
