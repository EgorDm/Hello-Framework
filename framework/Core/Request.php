<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 6-7-17
 * Time: 22:46
 */

namespace Framework\Core;


class Request
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const PATCH = 'PATCH';
    public const DELETE = 'DELETE';


    /***
     * @var string
     */
    public $host;

    /***
     * @var string
     */
    public $method;

    /***
     * @var array
     */
    public $path;

    /***
     * @var Collection
     */
    public $query;

    /***
     * @var Collection
     */
    public $post;


    /***
     * @var Collection
     */
    public $cookies;

    /***
     * @var string
     */
    public $content;

    /***
     * @var Collection
     */
    public $server;

    /**
     * @return Request
     */
    public static function initialize()
    {
        $ret = new Request();
        $ret->host = $_SERVER['HTTP_HOST'];
        $ret->method = $_SERVER['REQUEST_METHOD'];

        $path_str = $_SERVER['REQUEST_URI'];
        if (!empty($_SERVER['QUERY_STRING'])) {
            $query_pos = strpos($_SERVER['REQUEST_URI'], $_SERVER['QUERY_STRING']) - 1;
            $path_str = substr($_SERVER['REQUEST_URI'], 0, $query_pos);
            $ret->path = array_values(array_filter(explode('/', $path_str))); //TODO: helper func remove empty elements
        }
        $ret->path = array_values(array_filter(explode('/', $path_str)));

        $ret->query = new Collection($_GET);
        $ret->post = new Collection($_POST);
        $ret->cookies = new Collection($_COOKIE);

        $ret->content = file_get_contents('php://input');

        $ret->server = new Collection($_SERVER);

        return $ret;
    }

    public function isPost()
    {
        return $this->method == self::POST;
    }

    public function isAjax()
    {
        return $this->getHeader('X-Requested-With') == 'XMLHttpRequest';
    }

    /**
     * Checks if request has a ajax header or accepts json
     * @return bool
     */
    public function isJson()
    {
        return $this->isAjax() || $this->getHeader('Accept') == 'application/json';
    }

    public function getHeader($header)
    {
        return $this->server->get(strtoupper($header));
    }

    public function isSecure()
    {
        return !empty($this->server->get('HTTPS'));
    }

    public function getJson()
    {
        if (!isset($this->json)) {
            $this->json = json_decode($this->content, true);
        }
        return $this->json;
    }

}