<?php
/**
 * @package Models
 * @subpackage Content
 * List of topics
 */
class Model_List_Topic extends Model_List_TraversedTree// implements Model_HasVisible
{
    protected $_table = 'topics';
    protected $_model_class_name = 'Model_Topic';

	static private $_visible_only = false;

	static public function setVisibleOnly($value = true)
	{
		self::$_visible_only = (bool)$value;
	}

    public function find($filter = "", $limit = 0, $offset = 0, $order = "", $group = "", $having = "")
	{
		if (self::$_visible_only) $this->_table = 'visible_topics';
		parent::find($filter, $limit, $offset, $order, $group, $having);
		return $this;
	}
}
?>
