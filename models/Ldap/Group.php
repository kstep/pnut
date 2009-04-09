<?php
/**
 * @package Models
 * @subpackage User
 * @todo
 * Ldap user group model
 */
class Model_Ldap_Group extends Model_Ldap implements Model_Group
{
    protected $_pk = "ou";
    protected $_attributes = array(
        "name"     => "ou",
    );

    protected $_fields = array(
        "ou"       => Model::TYPE_STRING,
    );

    public function __construct(Storage_Ldap $db, $id = null)
    {
        parent::__construct($db, $id);
    }

    function getUsers()
    {
        return new Model_Ldap_List_User($this->_db, array("ou" => $this->_id));
    }
}
?>
