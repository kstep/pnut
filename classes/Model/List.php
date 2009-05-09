<?php
abstract class Model_List implements Iterator, Countable, ArrayAccess
{
    /**
     * @var Storage to be used to load models' data
     * @author kstep
     */
    protected $_db;

    /**
     * @var Storage_Result to load data from
     * @author kstep
     */
    protected $_result;

    /**
     * @var Model loaded at the moment
     * @author kstep
     */
    protected $_current_model = null;

    /**
     * @var bool if true, Model is correctly loaded
     * @author kstep
     */
    protected $_is_valid      = true;

    /**
     * Model_List constructor
     * @param Storage to be used for models' loading
     * @param Storage_Result to be used to fetch models' data
     * @return Model_List
     * @author kstep
     */
    public function __construct(Storage $db)
    {
		$find_args = func_get_args();
		$this->_db = array_shift($find_args);
		if ($find_args) call_user_func_array(array($this, 'find'), $find_args);
    }

    /**
     * Countable interface implementation
     * @return int number of elements in list
     * @author kstep
     */
    public function count()
    {
        return $this->_result->count();
    }

    /**
     * Iterator implementation: fetch next model
     * @return void
     * @author kstep
     */
    public function next()
    {
        $entry = $this->_result->fetchNext();
        if ($this->_is_valid = (bool)$entry)
        {
            $this->_current_model = $this->createClass($entry);
        }
    }

    /**
     * Iterator implementation: start listing from beggining
     * @return void
     * @author kstep
     */
    public function rewind()
    {
        $entry = $this->_result->fetchFirst();
        if ($this->_is_valid = (bool)$entry)
        {
            $this->_current_model = $this->createClass($entry);
        }
    }

    /**
     * Iterator implementation: get current key (model's id)
     * @return void
     * @author kstep
     */
    public function key()
    {
        return $this->_current_model->getId();
    }

    /**
     * Iterator implementation: get current model
     * @return void
     * @author kstep
     */
    public function current()
    {
        return $this->_current_model;
    }

    /**
     * Iterator implementation: check if model is currently loaded
     * @return void
     * @author kstep
     */
    public function valid()
    {
        return $this->_is_valid && (bool)$this->_current_model;
    }

    /**
     * fetch all values from list and return it as array
     * @return array of Model
     * @author kstep
     */
    public function fetchAll()
    {
        $return = array();
        foreach ($this->_result as $entry)
        {
            $return[] = $this->createClass($entry);
        }
        return $return;
    }

    public function offsetGet($offset)
    {
        return $this->_result->offsetGet($offset);
    }

    public function offsetExists($offset)
    {
        return $this->_result->offsetExists($offset);
    }

    public function offsetSet($offset, $value)
    {
        return;
    }

    public function offsetUnset($offset)
    {
        return;
    }

    /**
     * create object of Model class/subclass 
     * @param array entry, returned by Storage_Result class
     * @return Model class
     * @author kstep
     */
    abstract protected function createClass($entry);

	public function getObjectName()
	{
		return strtolower(substr(get_class($this), 11));
	}

	abstract public function remove($list = null);

	public function find(Storage_Result $condition)
	{
		$this->_result = $condition;
	}

	public function create(Storage $db, $object)
	{
		$class = "Model_List_".ucfirst(strtolower($object));
		$list = new $class ($db);
		return $list;
	}
}
?>
