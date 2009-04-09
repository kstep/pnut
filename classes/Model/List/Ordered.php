<?php
class Model_List_Ordered extends Model_List_Db
{
    protected $_order_by_fields = "sortorder,id";

    public function __construct(Storage_Db $db, $filter = "", $limit = 0, $offset = 0, $order = "", $group = "", $having = "")
    {
        if (!$order) $order = $this->_order_by_fields;
        parent::__construct($db, $filter, $limit, $offset, $order, $group, $having);
    }
}
?>
