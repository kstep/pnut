<?php
require_once(CLASSES_PATH.'/Storage/Db.php');
class Storage_Db_Table extends Storage_Db
{
    protected $_table;

    public function setTable($value)
    {
        $this->_table = $value;
        return $this;
    }
    public function getTable()
    {
        return $this->_table;
    }

    public function select($fields = "*", $condition = "", $limit = 0, $offset = 0, $order = "", $group = "", $having = "")
    {
        return parent::select($this->_table, $fields, $condition, $limit, $offset, $order, $group, $having);
    }
    public function insert($values)
    {
        return parent::insert($this->_table, $values);
    }
    public function update($values, $condition = null)
    {
        return parent::update($this->_table, $values, $condition);
    }
    public function delete($condition)
    {
        return parent::delete($this->_table, $condition);
    }

}
?>
