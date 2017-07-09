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

        self::$router->handleRequest(self::$request);


        //self::$session->set('hello', 'world');
        //echo self::$session->get('hello');

        //throw new \Exception('Foo Bar');
    }


    /**
     * @return Request
     */
    public static function getRequest(): Request
    {
        return self::$request;
    }
}