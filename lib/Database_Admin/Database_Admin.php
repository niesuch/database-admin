<?php

namespace Lib\Database_Admin;

use Lib\Utility as Utility;
use Config\Application\Adapter as Adapter;
use Lib\Logger\Logger as Logger;

/**
 * Class to SQL operations. Created for mini phpMyAdmin.
 * 
 * @autor Niesuch
 */
class Database_Admin extends Adapter {

    /**
     * Choosen databases
     * @var type 
     */
    private $_db_choosen;

    /**
     * SQL text
     * @var type 
     */
    private $_sql_text;

    /**
     * SQL text array
     * @var type 
     */
    private $_sql_text_array = array();

    /**
     * Output message
     * @var type 
     */
    private $_output_message;

    /**
     * Turn transaction
     * @var type 
     */
    private $_turn_transaction;

    /**
     * Log query
     * @var type 
     */
    private $_log;

    /**
     * Updated bases
     * @var type 
     */
    private $_updated;

    /**
     * Not updated bases
     * @var type 
     */
    private $_not_updated;

    /**
     * Class construct
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Update databases using query
     */
    public function db_update() {
        $this->_updated = array();
        $this->_not_updated = $this->_db_choosen;

        foreach ($this->_db_choosen as $db) {
            $this->_db->db_select($db);

            if (!$this->_do_sql()) {
                break;
            }

            array_push($this->_updated, $db);
            array_shift($this->_not_updated);
        }
    }

    /**
     * Do multi SQL's
     */
    private function _do_sql() {
        if ($this->_turn_transaction) {
            $this->_db->db_begin_transaction();
        }

        if (!$this->_do_multi_sql()) {
            if ($this->_turn_transaction) {
                $this->_db->db_rollback();
            } else {
                $this->_save_log_query();
            }

            $this->_output_message = "Error: " . $this->_db->db_error() . " <br/>Base: " . $this->_db->_db_selected . ".";
            $this->_save_log_error();

            return false;
        } else {
            if ($this->_turn_transaction) {
                $this->_db->db_commit();
            }

            $this->_save_log_query();
            $this->_save_log_history_query();
            $this->_output_message = "Done.";

            return true;
        }
    }

    /**
     * Saving log query
     */
    private function _save_log_query() {
        if ($this->_log[LOGS_LOG_QUERY]) {
            foreach ($this->_sql_text_array as $sql) {
                $this->_log[LOGS_LOG_QUERY]->set_values(array(
                    1 => null,
                    2 => $this->_db->_db_selected,
                    3 => $sql,
                    4 => date("Y-m-d H:i:s")
                        )
                );

                $this->_log[LOGS_LOG_QUERY]->save();
            }

            $this->_sql_text_array = array();
        }
    }

    /**
     * Saving log error
     */
    private function _save_log_error() {
        if ($this->_log[LOGS_LOG_ERROR]) {
            $this->_log[LOGS_LOG_ERROR]->set_values(array(
                1 => null,
                2 => $this->_sql_text,
                3 => $this->_output_message . " " . $this->_db->_error_message,
                4 => date("Y-m-d H:i:s")
                    )
            );

            $this->_log[LOGS_LOG_ERROR]->save();
        }
    }

    /**
     * Saving log history query
     */
    private function _save_log_history_query() {
        if ($this->_log[LOGS_LOG_HISTORY_QUERY]) {
            $this->_log[LOGS_LOG_HISTORY_QUERY]->set_values(array(
                1 => null,
                2 => $this->_db->_db_selected,
                3 => nl2br($this->_sql_text),
                4 => date("Y-m-d H:i:s")
                    )
            );

            $this->_log[LOGS_LOG_HISTORY_QUERY]->save();
        }
    }

    /**
     * Do one SQL
     * @param type $sql
     * @return boolean
     */
    private function _do_one_sql($sql) {
        $sql_temp = preg_replace("/;$/", "", trim($sql));

        if ($sql_temp) {
            return $this->_db->db_query($sql_temp);
        }

        return false;
    }

    /**
     * Do multi SQL's
     * @return boolean
     */
    private function _do_multi_sql() {
        $sql_temp = null;
        $char = null;
        $current_position = 0;

        for ($i = 0; $i < strlen($this->_sql_text); $i++) {
            if ($char) {
                list($char_temp, $position_temp) = Utility::get_close_char($this->_sql_text, $position + strlen($char), $char);

                if ($char_temp) {
                    if ($char == '--' || $char == '#') {
                        $sql_temp .= substr($this->_sql_text, $current_position, $position - $current_position);
                    } else {
                        $sql_temp .= substr($this->_sql_text, $current_position, $position_temp + strlen($char_temp) - $current_position);
                    }

                    $current_position = $position_temp + strlen($char_temp);
                    $position = 0;
                    $char = '';
                }
            } else {
                list($char, $position) = Utility::get_open_char($this->_sql_text, $current_position);

                if ($char == ';') {
                    $sql_temp .= substr($this->_sql_text, $current_position, $position - $current_position + 1);

                    if (!$this->_do_one_sql($sql_temp)) {
                        return false;
                    }

                    array_push($this->_sql_text_array, $sql_temp);

                    $current_position = $position + strlen($char);
                    $position = 0;
                    $char = '';
                    $sql_temp = '';
                }
            }
        }

        return true;
    }

    /**
     * Get available databases and return template to select
     * @return string
     */
    public function get_databases() {
        $array_DB = $this->_db->db_array("SHOW DATABASES;");
        $result = '';

        if (!is_array($array_DB)) {
            $array_DB = array(0 => array('Database' => $this->_db->_db_selected));
        }

        foreach ($array_DB as $db) {
            $base = $db['Database'];
            $selected = '';

            if ($this->_db_choosen) {
                $selected = in_array($base, $this->_db_choosen) ? 'selected' : '';
            }

            $result .= "<option value='$base'" . $selected . ">$base</option>";
        }

        return $result;
    }

    /**
     * Get history of query and return template to select
     * @return string
     */
    public function get_select_history() {
        $config = $this->getConfig();
        $settings = array(
            'array_with_value' => false,
            'distinct' => true
        );
        
        $this->_db->db_select($config[LOGS_LOG_HISTORY_QUERY]['base']);
        $sql = $this->_db->get_select(LOGS_LOG_HISTORY_QUERY, array("query"), $settings);
        $array_query = $this->_db->db_array($sql);
        $result = '';

        $i = 0;
        foreach ($array_query as $query) {
            $string = str_replace("<br />", "/#", $query['query']);
            $result .= '<option value="' . $i++ . '">' . $string. '</option>';
        }

        return $result;
    }

    /**
     * Set log
     * @param type $log
     */
    public function set_log($log) {
        $this->_log[$log->_name] = $log;
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
