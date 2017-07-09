<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 7-7-17
 * Time: 21:01
 */

namespace Framework\Core;


class Router
{
    /***
     * @var array
     */
    public $routes;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->routes = include(__ROOT__ . '/config/routes.php');
    }


    public function handleRequest(Request $request)
    {
        foreach ($this->routes as $route => $data) {
            $route = array_values(array_filter(explode('/', $route)));
            if ($this->matchesRoute($request, $route, $data)) {
                $this->matchController($request, $route, $data);
                return;
            }
        }
        throw new \Exception('This page could not be found!', 404);
    }

    protected function matchesRoute(Request $request, $route, $data)
    {
        if (count($route) > count($request->path)) return false;
        if (isset($data['method']) && $data['method'] != ['*'] && !in_array($request->method, $data['method'])) return false; //TODO: case insensitive
        for($i = 0; $i < count($route); $i++) {
            if (substr($route[$i], 0, 1) == '@' || $route[$i] == '*') continue;
            if(strcasecmp($route[$i], $request->path[$i]) != 0) return false;
        }
        return true;
    }

    protected function matchController(Request $request, $route, $data)
    {
        if (!isset($data['controller'])) throw new \Exception('Route controller is not set.');
        if (!isset($data['action'])) throw new \Exception('Route action is not set.');
        $controllerClass = "Backend\\Controllers\\$data[controller]Controller";
        $action = $data['action']; //TODO: new feature allow empty
        $parameters = [];

        for ($i = 0; $i < count($route); $i++) {
            $part = $route[$i];
            if (substr($part, 0, 1) == '@') {
                $parameters[] = $request->path[$i];
            } elseif ($part == '*') {

            } else if(empty($action)){
                $action = $part;
            }
        }

        $controller = new $controllerClass();
        $this->runRoute($controller, $action, $parameters);
    }

    /**
     * @param Controller $controller
     * @param $action
     * @param $parameters
     */
    protected function runRoute($controller, $action, $parameters)
    {
        $view = $controller->run($action, $parameters);
    }
}