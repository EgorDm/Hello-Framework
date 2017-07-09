<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 6-7-17
 * Time: 23:56
 */

namespace Framework;


use Framework\Core\ErrorHandler;
use Framework\Core\Logger;
use Framework\Core\Request;
use Framework\Core\Router;
use Framework\Core\Session;
use Framework\ORM\DatabaseConnection;

class Hello
{
    /***
     * @var Request
     */
    private static $request;

    /***
     * @var Session
     */
    public static $session;

    /***
     * @var Router
     */
    public static $router;

    /***
     * @var DatabaseConnection
     */
    protected static $connection;

    /***
     * @var ErrorHandler
     */
    private static $error_handler;

    /**
     * Hello constructor.
     */
    public function __construct()
    {
        Logger::initialize();
        self::$error_handler = new ErrorHandler();
        self::$request = Request::initialize();
        self::$session = new Session(self::$request);
        self::$router = new Router();

        self::$connection = new DatabaseConnection('localhost', 'helloframework', 'root', '', 'utf8');
        self::getConnection()->query('CREATE TABLE hello (id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY, world VARCHAR(225))', []);

        self::$router->handleRequest(self::$request);

    }


    /**
     * @return Request
     */
    public static function getRequest(): Request
    {
        return self::$request;
    }

    /**
     * @return DatabaseConnection
     */
    public static function getConnection(): DatabaseConnection
    {
        return self::$connection;
    }
}