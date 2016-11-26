<?php

namespace Config\Application\Ini;

/**
 * Ini class
 * 
 * @autor Niesuch
 */
class Ini {

    /**
     * Data
     * @var type 
     */
    private $_data;

    /**
     * Class construct
     * @param type $ini_path
     */
    public function __construct($ini_path) {
        $this->_data = $this->_parseIniFile($ini_path);
    }

    /**
     * Ini file parser
     * @param type $ini_path
     * @return type
     */
    private function _parseIniFile($ini_path) {
        return parse_ini_file($ini_path, true);
    }

    /**
     * Get data
     * @return type
     */
    public function getData() {
        return $this->_data;
    }

}
