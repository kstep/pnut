<?php
require_once(CLASSES_PATH.'/Model/List.php');
abstract class Model_Ldap_List extends Model_List
{
    protected $_pk = "uid";

    public function __construct(Storage_Ldap $db, $filter = "", $limit = 0)
    {
        if (!$filter instanceof Storage_Ldap_Result)
            $filter = $db->select($this->_pk, $filter, $limit);
        parent::__construct($db, $filter);
    }
}
?>
