<?php
abstract class Storage_Db_Result extends Storage_Result
{
    protected $_fetch_mode  = 0;

    public function __construct($resource, $link = null, $key_field = null, $fetch_mode = 0)
    {
        $this->_key_field  = $key_field;
        $this->_fetch_mode = $fetch_mode;
        parent::__construct($resource, $link);
    }

    /**
     * fetch all results in array
     * @return array
     **/
    public function fetchAll($key_field = null)
    {
        $result = array();
        if ($key_field === null) $key_field = $this->_key_field;

        if ($key_field === null)
            foreach ($this as $value)
                $result[] = $value;
        else
            foreach ($this as $value)
                $result[$value[$key_field]] = $value;
        return $result;
    }
    public function fetchFirst()
    {
        $this->seek(0);
        return $this->fetchNext();
    }

    public function setFetchMode($mode)
    {
        $this->_fetch_mode = (int)$mode;
        return $this;
    }
    public function getFetchMode()
    {
        return $this->_fetch_mode;
    }
    public function setKeyField($key_field)
    {
        $this->_key_field = $key_field;
        return $this;
    }
    public function getKeyField()
    {
        return $this->_key_field;
    }
}
?>
