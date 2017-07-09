<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 7-7-17
 * Time: 21:07
 */

namespace Framework\Core;


class Controller
{

    public function run($method, $params)
    {
        return call_user_func_array([$this, $method], $params);
    }

    function __call($name, $arguments)
    {
        throw new \Exception("No such method found: $name");//todo add details
    }


}