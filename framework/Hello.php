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

class Hello
{
    /***
     * @var Request
     */
    private static $request;

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
        self::$request = Request::initialize();
        self::$error_handler = new ErrorHandler();

        throw new \Exception('Foo Bar');
    }


    /**
     * @return Request
     */
    public static function getRequest(): Request
    {
        return self::$request;
    }
}