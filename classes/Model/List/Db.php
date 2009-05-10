<?php
abstract class Model_List_Db extends Model_List
{
    protected $_table;
	protected $_view;
    protected $_pk = "id";
	protected $_views;
	protected static $_visibility;

    /**
     * @var string name of model's class
     * @author kstep
     */
    protected $_model_class_name = null;

    public function __construct(Storage $db)
    {
		$this->_view = self::$_visibility && isset($this->_views[self::$_visibility])? $this->_views[self::$_visibility]: $this->_table;
		$find_args = func_get_args();
		$this->_db = array_shift($find_args);
		if ($find_args) call_user_func_array(array($this, 'find'), $find_args);
    }

    protected function createClass($entry)
    {
		//$model = new $this->_model_class_name ($this->_db, $entry[$this->_pk]);
		$model = new $this->_model_class_name ($this->_db);
		$model->parseData($entry);
        return $model;
    }

	public static function setVisibility($value = "visible")
	{
		self::$_visibility = $value;
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
			$condition = $this->_db->select($this->_view, "*", $condition, $limit, $offset, $order, $group, $having);
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
