<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 6-7-17
 * Time: 23:56
 */

namespace Framework;


use Framework\Core\Request;

class Hello
{
    /***
     * @var Request
     */
    private static $request;

    /**
     * Hello constructor.
     */
    public function __construct()
    {
        self::$request = Request::initialize();
    }


    /**
     * @return Request
     */
    public static function getRequest(): Request
    {
        return self::$request;
    }
}