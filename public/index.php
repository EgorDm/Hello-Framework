<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 6-7-17
 * Time: 21:37
 */

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$loader = require __DIR__ . '/../vendor/autoload.php';

// Define namespaces
$loader->addPsr4('Framework\\', __DIR__.'/../framework');
$loader->addPsr4('Backend\\', __DIR__.'/../backend');


$hello = new Framework\Hello;
echo json_encode($hello->getRequest());