<?php

namespace Config\Application;

use Config\Application\Ini\Ini as Ini;

/**
 * Application config class
 * 
 * @autor Niesuch
 */
class Application_Config {

    /**
     * Config data
     * @var type 
     */
    private $_config;

    /**
     * Class construct
     */
    public function __construct() {
        $this->_loadConfig(APPLICATION_ENV, APPLICATION_INI);
    }

    /**
     * Load config data
     * @param type $environment
     * @param type $ini_path
     */
    private function _loadConfig($environment, $ini_path) {
        $ini = new Ini($ini_path);
        $ini_config = $ini->getData();

        if ($ini_config) {
            if ($ini_config[$environment]) {
                $this->_config = $ini_config[$environment];
            } else {
                trigger_error('User config not defined', E_USER_ERROR);
            }
        } else {
            trigger_error('Config file not exist', E_USER_ERROR);
        }
    }

    /**
     * Get config
     * @return type
     */
    public function getConfig() {
        return $this->_config;
    }

}
