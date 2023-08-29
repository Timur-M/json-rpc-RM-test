<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');



spl_autoload_register(function ($class_name) {
    $file = __DIR__.'/includes/'.$class_name.'.php';
    if ( file_exists($file) ) {
        require_once $file;
    }
});

$json = file_get_contents('php://input');

$request = json_decode($json, true);

//var_dump($request);

$one_gate = new One_gate($request);