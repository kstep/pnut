<?php
final class Model_Factory // implements Serializable
{
    static private $_instance = null;
    private $_registered_models = array();
    private $_db = null;

    static public function getInstance(Db $db)
    {
        if (self::$_instance) return self::$_instance;
        $class = __CLASS__;
        self::$_instance = new $class ($db);
    }

    public function registerModel($classname, $tablename, $pk = "id", $attributes = null)
    {
        $this->_registered_models[$tablename] = array( "class" => $classname, "pk" => $pk );
        if ($attributes && is_array($attributes))
            $this->_registered_models[$tablename]["attrs"] = $attributes;
    }

    public function constructModel($tablename, $id = null)
    {
        if (!$this->_registered_models[$tablename])
            throw new Exception("Table $tablename is not registered as model source.");

        $class = $this->_registered_models[$tablename]["class"];
        $instance = new $class ($this->_db, $tablename, $id, $this->_registered_models[$tablename]["pk"], $this->_registered_models[$tablename]["attrs"]);
    }

    public function registerModels($array)
    {
        foreach ($array as $tablename => $data)
            $this->registerModel($data["class"], $tablename, $data["pk"], $data["attrs"]);
    }

    /*
     *     public function unserialize($serialized)
     *     {
     *         $array = unserialize($serialized);
     *         if (is_array($array))
     *             $this->registerModels($array);
     *         self::$_instance = $this;
     *     }
     * 
     *     public function serialize()
     *     {
     *         return serialize($this->_registered_models);
     *     }
     */

    private function __construct(Db $db) { $this->_db = $db; }
        private function __clone() { }
}
?>
