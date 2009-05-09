<?php
abstract class Model_List_Db extends Model_List
{
    protected $_table;
    protected $_pk = "id";

    /**
     * @var string name of model's class
     * @author kstep
     */
    protected $_model_class_name = null;

    protected function createClass($entry)
    {
		//$model = new $this->_model_class_name ($this->_db, $entry[$this->_pk]);
		$model = new $this->_model_class_name ($this->_db);
		$model->parseData($entry);
        return $model;
    }

	public function remove($list = null)
	{
		if ($list === null)
		{
			$list = array();
			foreach ($this as $key => $value)
			{
				$list[] = $key;
			}
		}
		$this->_db->delete($this->_table, array( $this->_pk => $list ));
	}

	public function find($condition, $limit = 0, $offset = 0, $order = "", $group = "", $having = "")
	{
		if (!$condition instanceof Storage_Db_Result)
			$condition = $this->_db->select($this->_table, "*", $condition, $limit, $offset, $order, $group, $having);
		return parent::find($condition);
	}

	public function getTable()
	{
		return $this->_table;
	}

	public function getPk()
	{
		return $this->_pk;
	}
}
?>
