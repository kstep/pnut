<?php
/**
 * @package Models
 * @subpackage Content
 * List of articles
 */
class Model_List_Article extends Model_List_Paged// implements Model_HasVisible
{
    protected $_table = 'articles';
    protected $_model_class_name = 'Model_Article';

	static private $_visible_only = false;

	static public function setVisibleOnly($value = true)
	{
		self::$_visible_only = (bool)$value;
	}

    public function find($filter = "", $items_per_page = 20, $page = 0, $order = "", $group = "", $having = "")
	{
		if (self::$_visible_only) $this->_table = 'visible_articles';
		return parent::find($filter, $items_per_page, $page, $order, $group, $having);
	}
}
?>
