<?php

namespace Config\Application;

use Lib\Db\Db as Db;
use Config\Application\Application_Config as Application_Config;

/**
 * Adapter class
 * 
 * @autor Niesuch
 */
class Adapter extends Application_Config {

    /**
     * DB
     * @var type 
     */
    protected $_db;

    /**
     * Class construct
     */
    public function __construct() {
        parent::__construct();

        $config = $this->getConfig();
        $db = $config['db'];

        $this->_db = new Db(array(
            'host' => $db['host'],
            'user' => $db['user'],
            'password' => $db['password']
        ));
    }

}
