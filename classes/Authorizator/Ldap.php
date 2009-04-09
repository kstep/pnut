<?php
require_once(CLASSES_PATH.'/Authorizator.php');
class Authorizator_Ldap extends Authorizator
{
    public function __construct(Storage_Ldap $store)
    {
        parent::__construct($store);
    }

    protected function createUser($username, $password)
    {
        $user = new Model_Ldap_User($this->_storage, $username);
        if (!$this->_storage->login($user->getDN(), $password)) return false;
        return $user;
    }

    public function canAccess($realm)
    {
        if (!$this->_authorized_user) return false;
        $group = $this->_authorized_user->getGroup();
        return $group->name == "developers" || $group->name == "managers";
    }
}
?>
