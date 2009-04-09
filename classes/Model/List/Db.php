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


    public function __construct(Storage_Db $db, $filter = "", $limit = 0, $offset = 0, $order = "", $group = "", $having = "")
    {
        if (!$filter instanceof Storage_Db_Result)
            $filter = $db->select($this->_table, "*", $filter, $limit, $offset, $order, $group, $having);
            //$filter = $db->select($this->_table, $this->_pk, $filter, $limit, $offset, $order, $group, $having);
        parent::__construct($db, $filter);
    }

    protected function createClass($entry)
    {
		//$model = new $this->_model_class_name ($this->_db, $entry[$this->_pk]);
		$model = new $this->_model_class_name ($this->_db);
		$model->parseData($entry);
        return $model;
    }

}
?>
