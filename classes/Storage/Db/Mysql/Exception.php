<?php
require_once(CLASSES_PATH.'/Storage/Db/Exception.php');
class Storage_Db_Mysql_Exception extends Storage_Db_Exception
{
    protected function getErrorMessage()
    {
        return mysql_error($this->_link);
    }
    protected function getErrorCode()
    {
        return mysql_errno($this->_link);
    }
}
?>
