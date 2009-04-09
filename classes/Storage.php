<?php
/**
 * @package Core
 * @subpackage Storage
 * Base abstract storage
 */
abstract class Storage
{
    /**
     * @var resourse link to a low level layer of the storage
     * @author kstep
     */
    protected $_link;

    /**
     * Storage constructor
     * @param string hostname
     * @param int port number
     * @param string username
     * @param string password
     * @return Storage
     * @author kstep
     */
    public function __construct()
    {
        $this->_link = $this->connect();
    }

    /**
     * converts array of "key" => "value" pairs (a hash) into string
     * by joining it with delimiter, preserving key => value pairs
     * @param array or string data to convert
     * @param string delimiter used to join values
     * @return string
     * @author kstep
     */
    protected function joinValues($values, $delim = ",")
    {
        if (is_array($values))
            return implode($delim, array_map(array($this, "makePairs"), array_keys($values), array_values($values)));
        else
            return (string)$values;
    }

    /**
     * constructs real Storage instance (one of childs of this abstract class).
     * @param array config array with all data necessary to build Storage child,
     * parameters depend on Storage's implementation, but one element must be always preset:
     * "class", which can be in formats like: "db mysql", "Db Mysql", "db_mysql" etc.,
     * will be normalized to real Storage's child class name.
     * @return Storage not abstract child class's instance.
     * @author kstep
     */
    static public function create($config)
    {
        $class = str_replace(" ", "_", ucwords(str_replace("_", " ", $config["class"])));
        $class = "Storage_{$class}";
        $storage = new $class ($config);
        return $storage;
    }

    /**
     * connect to database
     * @return resource link
     */
    abstract protected function connect();
    abstract public function __destruct();

    /**
     * this method must create correct string by joining
     * key => value pair, escaping special chars if necessary
     * for concrete storage's implementation (e.g. $key="id", $value=1
     * must be converted to string "`id`=1" for MySQL, while using
     * mysql_escape_string of something if necessary).
     * @param string key name
     * @param mixed value
     * @return string
     * @author kstep
     */
    abstract protected function makePairs($key, $value);
}
?>
