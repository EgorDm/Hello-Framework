<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 7-7-17
 * Time: 11:00
 */

namespace Framework\Core;


use IteratorAggregate;
use Traversable;

class Session implements IteratorAggregate
{
    /**
     * @var Session
     */
    public static $instance;

    /***
     * @var bool
     */
    public $started;

    /**
     * Session constructor.
     */
    public function __construct(Request $request)
    {
        self::$instance = $this;
        ini_set('session.use_cookies', 1);
        $this->started = $this->start($request);
    }

    public function start(Request $request)
    {
        if ($this->started) {
            return true;
        }

        if (session_status() == PHP_SESSION_ACTIVE) {
            throw new \Exception('Session has aready been started!');
        }

        session_set_cookie_params(0, '/', $request->host, $request->isSecure(), true);
        if (!session_start()) {
            throw new \Exception('Failed to start a session!');
        };

        if ($this->validate()) {
            if (!$this->antiHijack($request)) { // Somethign wrong
                $_SESSION = [];
                $_SESSION['IP_Address'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['User_Agent'] = $_SERVER['HTTP_USER_AGENT'];
            } elseif (rand(1, 100) <= 5) { // Small chance of regenerating the session
                $this->regenerate();
            }
        } else {
            $this->clear();
        }

        return true;
    }

    public function regenerate()
    {
        // There is already a new id.
        if (isset($_SESSION['OBSOLETE']) || $_SESSION['OBSOLETE'] == true) return;

        $_SESSION['OBSOLETE'] = true;
        $_SESSION['EXPIRES'] = time() + 10;

        session_regenerate_id(false);

        $id = session_id();
        session_write_close();

        session_id($id);
        session_start();

        // Unset from the new session
        unset($_SESSION['OBSOLETE']);
        unset($_SESSION['EXPIRES']);
    }

    public function clear()
    {
        $_SESSION = [];
        session_destroy();
        session_start();
    }

    /***
     * Returns false if there is something fishy
     * @param Request $request
     * @return bool
     */
    protected function antiHijack(Request $request)
    {
        if (!isset($_SESSION['IP_Address']) || !isset($_SESSION['User_Agent']))
            return false;

        if ($_SESSION['IP_Address'] != $request->server->get('REMOTE_ADDR'))
            return false;

        if ($_SESSION['User_Agent'] != $request->server->get('HTTP_USER_AGENT'))
            return false;

        return true;
    }

    protected function validate()
    {
        if (isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES']))
            return false;

        if (isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time())
            return false;

        return true;
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new $_SESSION;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
}