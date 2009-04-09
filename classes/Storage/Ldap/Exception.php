<?php
require_once(CLASSES_PATH.'/Storage/Exception.php');
class Storage_Ldap_Exception extends Storage_Exception
{
    protected function getErrorMessage()
    {
        return ldap_error($this->_link);
    }
    protected function getErrorCode()
    {
        return ldap_errno($this->_link);
    }
}
?>
