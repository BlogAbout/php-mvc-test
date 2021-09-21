<?php
/*
function __autoload($class_name) {
    $array_paths = array(
        '/models/',
        '/components/'
    );

    foreach($array_paths as $path) {
        $path = ROOT . $path . $class_name . '.php';
        if (is_file($path))
            include_once $path;
    }
}*/
$autoload = function($class) {
    $paths = array(
        '/models/',
        '/components/'
    );

    foreach($paths as $path) {
        $path = ROOT . $path . $class . '.php';
        if (is_file($path))
            include_once $path;
    }
};
spl_autoload_register($autoload);