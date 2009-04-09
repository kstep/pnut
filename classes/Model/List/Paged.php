<?php
class Model_List_Paged extends Model_List_Ordered implements Model_Pagable
{

    private $_count_all = null;

    private $_filter = "";
    private $_page = 0;
    private $_items_per_page = 20;

    public function __construct(Storage_Db $db, $filter = "", $items_per_page = 20, $page = 0, $order = "", $group = "", $having = "")
    {
        if (!$page) $items_per_page = 0;
        if ($page < 1) $page = 1;

        $this->_filter = $filter;
        $this->_page = (int)$page;
        $this->_items_per_page = (int)$items_per_page;

        parent::__construct($db, $this->_filter, $this->_items_per_page, ($this->_page-1)*$this->_items_per_page, $order, $group, $having);
    }

    public function getPager()
    {
        return new Filter_Pager($this->countAll(), $this->_page, $this->_items_per_page);
    }

    public function countAll()
    {
        if ($this->_count_all === null)
        {
            $count = $this->_db->select($this->_table, "count(*) as numitems", $this->_filter)->fetchFirst();
            $this->_count_all = $count['numitems'];
        }
        return $this->_count_all;
    }


}
?>
