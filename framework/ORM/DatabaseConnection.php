<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 9-7-2017
 * Time: 21:49
 */

namespace Framework\ORM;


use InvalidArgumentException;
use PDO;

class DatabaseConnection
{
    /**
     * @var \PDO
     */
    protected $PDO;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $charset;

    /**
     * @var int
     */
    protected $mode = PDO::FETCH_OBJ;

    /**
     * DatabaseConnection constructor.
     * @param string $host
     * @param string $database
     * @param string $username
     * @param string $password
     * @param string $charset
     * @param array $options
     * @param string $prefix
     */
    public function __construct($host, $database, $username, $password, $charset, $options = [], $prefix = '')
    {
        $this->database = $database;
        $this->prefix = $prefix;
        $this->PDO = new \PDO("mysql:host=$host;dbname=$database;charset=$charset", $username, $password, $options);
    }

    /**
     * @param string $query
     * @param array|null $bindings
     * @return false|\PDOStatement
     */
    public function query($query, $bindings =[])
    {
        return $this->run($query, $bindings, function ($query, $bindings) {
            if (empty($bindings)) return $this->getPDO()->query($query);
            $stmt = $this->getPDO()->prepare($query);
            $this->bindValues($stmt, $bindings);
            return $stmt->execute();
        });
    }

    public function select($query, $bindings =[])
    {
        //$stmt->setFetchMode($this->mode);
        throw new \Exception('TODO');
    }

    /**
     * @param $query
     * @param $bindings
     * @param \Closure $call
     * @return mixed
     */
    protected function run($query, $bindings, \Closure $call)
    {
        if (!is_string($query) || empty($query)) {
            throw new InvalidArgumentException('Specified query is invalid!');
        }
        return $call($query, $bindings);
    }



    /**
     * @param \PDOStatement $stmt
     * @param array $bindings
     */
    protected function bindValues($stmt, $bindings)
    {
        $bindings = $this->prepareBindings($bindings);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue(
                is_string($key) ? $key : $key + 1, $value,
                is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR
            );
        }
    }

    /**
     * @param array $bindings
     * @return array
     */
    protected function prepareBindings(array $bindings)
    {
        foreach ($bindings as $key => $value) {
            if ($value instanceof \DateTimeInterface) {
                $bindings[$key] = $value->format($this->getDateFormat());
            }
        }
        return $bindings;
    }

    /**
     * @return PDO
     */
    public function getPDO(): PDO
    {
        return $this->PDO;
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }


}