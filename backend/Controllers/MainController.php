<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 7-7-17
 * Time: 21:02
 */

namespace Backend\Controllers;


use Framework\Core\Controller;

class MainController extends Controller
{

    public function index()
    {
        echo 'Hello, World!';
        return view();
    }

}