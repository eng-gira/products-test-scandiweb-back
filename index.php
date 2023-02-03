<?php

ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header("Access-Control-Allow-Headers: *");

define("MODEL", "Models" . DIRECTORY_SEPARATOR);
define("DATA", "Data" . DIRECTORY_SEPARATOR);
define("CONTROLLER", "Controllers" . DIRECTORY_SEPARATOR);
define("INC", "Inc" . DIRECTORY_SEPARATOR);


// Autoloading
spl_autoload_register(function ($class) {
    require str_replace('\\', '/', $class) . '.php';
});

// Error Handling
function errorHandler($errno, $errstr, $errfile, $errline)
{
    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
    return true;
}
set_error_handler('errorHandler');

// Start the application
\Core\Application::start();
