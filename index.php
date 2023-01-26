<?php
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header("Access-Control-Allow-Headers: *");

define("MODEL",  "models" . DIRECTORY_SEPARATOR);
define("DATA",  "data" . DIRECTORY_SEPARATOR);
define("CONTROLLER",  "controllers" . DIRECTORY_SEPARATOR);
define("INC",  "inc" . DIRECTORY_SEPARATOR);


// Autoloading
spl_autoload_register(function ($class) {
    require str_replace('\\', '/', $class) . '.php';
});

// Start the application
new \Core\Application;
