<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 6-7-17
 * Time: 21:37
 */

//For debugging purposes. Will be removed at stable
error_reporting(E_ALL);
ini_set('display_errors', 'On');

define('__ROOT__', __DIR__ . '/..');
$logger = false;


// Exception handling
set_exception_handler('exception_handler');
function exception_handler(Throwable $exception)
{
    echo sprintf("Exception: \"%s\" in file: \"%s\" at line %d.\n\tStackTrace: %s",
        $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());

    if (class_exists('\\Framework\\Core\\Logger')) {
        \Framework\Core\Logger::error($exception);
        \Framework\Core\Logger::close();
    }
    //todo: $exception->getTrace()
}

// Composer autoload
$loader = require __DIR__ . '/../vendor/autoload.php';


// Define namespaces
$loader->addPsr4('Framework\\', __DIR__ . '/../framework');
$loader->addPsr4('Backend\\', __DIR__ . '/../backend');

// Logger
$logger = \Framework\Core\Logger::initialize();


//throw new Exception('Hello, World?');

//Initialize the workflow
$hello = new Framework\Hello;

echo json_encode($hello->getRequest());