<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 6-7-17
 * Time: 23:29
 */

namespace Framework\Core;


use IteratorAggregate;
use Traversable;

class Collection implements IteratorAggregate, \JsonSerializable
{
    /***
     * @var array
     */
    private $data;

    /**
     * Collection constructor.
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
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
        return new \ArrayIterator($this->data);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return $this->data;
    }
}