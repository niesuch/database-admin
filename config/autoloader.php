<?php

include_once '../env.php';
include_once '../lib/Globals.php';

function __autoload($classname) {
    $filename = dirname(dirname(__FILE__)) . "/" . $classname . ".php";
    include_once($filename);
}
