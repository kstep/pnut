<?php
/**
 * @package Models
 * @subpackage User
 * @todo
 * Ldap user list
 */
class Model_Ldap_List_User extends Model_Ldap_List
{
    protected function createClass($entry)
    {
        return new Model_Ldap_User($this->_db, $entry[$this->_pk][0]);
    }
}
?>
