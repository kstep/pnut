<?php
abstract class Storage_Result implements Iterator, Countable, ArrayAccess
{
    /**
     * @var resource low level link to resource
     * @author kstep
     */
    protected $_link        = null;

    /**
     * @var resource "result" resource to fetch result
     * values one by one
     * @author kstep
     */
    protected $_resource    = null;

    /**
     * @var mixed currently fetched data entry
     * @author kstep
     */
    protected $_current_row = null;

    /**
     * @var mixed currently fetched entry's key
     * @author kstep
     */
    protected $_current_key = 0;

    /**
     * @var int total number of rows in result
     * @author kstep
     */
    protected $_num_rows    = 0;

    protected $_key_field   = null;

    /**
     * this method must fetch all values from result
     * resource and return them as array.
     * @return array of result entries
     * @author kstep
     */
    abstract public function fetchAll();

    /**
     * fetch next row in result set
     * @return mixed a row of type selected by setFetchMode
     * @author kstep
     */
    abstract public function fetchNext();

    /**
     * fetch first row in result set (and thus reset result's cursor)
     * @return mixed a row of type selected by setFetchMode
     * @author kstep
     */
    abstract public function fetchFirst();

    /**
     * set result pointer to some row
     * @param int result row from 0 to $this->_num_rows - 1
     * @return void
     * @author kstep
     */
    abstract public function seek($rowno = 0);

    /**
     * don't forget to free result after using!
     * @return void
     * @author kstep
     */
    abstract public function __destruct();

    /**
     * Storage_Result constructor.
     * Don't forget to set $_num_rows property
     * in children, as it's dependant on concrete storage's
     * implementation!
     * @param resource result resource
     * @param resource low level link to storage
     * @return Storage_Result
     * @author kstep
     */
    public function __construct($resource, $link = null)
    {
        $this->_link     = $link;
        $this->_resource = $resource;
    }

    /**
     * Iterator implementation: return current entry
     * @return mixed current entry
     * @author kstep
     */
    public function current()
    {
        return $this->_current_row;
    }

    /**
     * Iterator implementation: return current entry's key
     * @return mixed current entry's key
     * @author kstep
     */
    public function key()
    {
        return $this->_current_key;
    }

    /**
     * Iterator implementation: fetch next entry from result
     * @return void
     * @author kstep
     */
    public function next()
    {
        $this->_current_row = $this->fetchNext();
        $this->setCurrentKey();
    }

    /**
     * Iterator implementation: reset results' cursor and fetch first
     * entry from result
     * @return void
     * @author kstep
     */
    public function rewind()
    {
        $this->_current_row = $this->fetchFirst();
        $this->setCurrentKey(0);
    }

    private function setCurrentKey($key = null)
    {
        if ($this->_key_field === null)
            $this->_current_key = ($key === null)? ($this->_current_key + 1): (int)$key;
        else
            $this->_current_key = $this->_current_row[$this->_key_field];
    }

    /**
     * Iterator implementation: check if result entry is loaded
     * @return bool true if result entry is correctly loaded
     * @author kstep
     */
    public function valid()
    {
        return (bool)$this->_current_row;
    }

    /**
     * Countable implementation: return number in entries in result
     * @return int number of entries in result
     * @author kstep
     */
    public function count()
    {
        return $this->_num_rows;
    }

    public function offsetGet($offset)
    {
        $this->seek($offset);
        return $this->fetchNext();
    }

    public function offsetExists($offset)
    {
        return $offset >= 0 and $offset < $this->_num_rows;
    }

    public function offsetSet($offset, $value)
    {
        return;
    }

    public function offsetUnset($offset)
    {
        return;
    }
}
?>
