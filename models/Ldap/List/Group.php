<?php
/**
 * @package Models
 * @subpackage User
 * @todo
 * Ldap user group list
 */
class Model_Ldap_List_Group extends Model_Ldap_List
{
    protected function createClass($entry)
    {
        return new Model_Ldap_Group($this->_db, $entry[$this->_pk][0]);
    }
}
?>
