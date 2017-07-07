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

// Define some constants
define('__ROOT__', __DIR__ . '/..');

// Initial Exception handling
set_exception_handler('exception_handler');
function exception_handler(Throwable $exception)
{
    echo sprintf("Exception: \"%s\" in file: \"%s\" at line %d.\n\tStackTrace: %s",
        $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
}

// Composer autoload
$loader = require __DIR__ . '/../vendor/autoload.php';


// Define namespaces
$loader->addPsr4('Framework\\', __DIR__ . '/../framework');
$loader->addPsr4('Backend\\', __DIR__ . '/../backend');


// Initialize the workflow
$hello = new Framework\Hello;