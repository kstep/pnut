<?php
/**
 * @package Models
 * @subpackage User
 * @todo
 * Ldap user model
 */
class Model_Ldap_User extends Model_Ldap implements Model_User
{
    protected $_attributes = array(
        "username"     => "cn",
//        "icq"          => "icq",
//        "skype"        => "skype",
        "email"        => "mail",
        "phone"        => "Phone",
        "created"      => "createTimestamp",
        "login"        => "uid",
        "password"     => "userPassword",
//        "performer"    => "performer_id",
//        "admin"        => "is_admin",
//        "system_type"  => "system_type",
        "group"        => "ou",
//        "banned"       => "banned",
//        "welcome"      => "welcome",
//        "can_add_type" => "allow_add_type",
//        "show_all"     => "all_visibility",
//        "show_icq"     => "icq_visibility",
//        "show_skype"   => "skype_visibility",
//        "show_email"   => "email_visibility",
//        "show_phone"   => "phone_visibility",
//        "online"       => "online",
//        "busy"         => "is_busy",
//        "rating"       => "rating",
//        "forum_user"   => "forum_user_id",
    );

    protected $_fields = array(
        "cn"           => Model::TYPE_STRING,
        "mail"         => Model::TYPE_STRING,
        "Phone"        => Model::TYPE_STRING,
        "createTimestamp" => Model::TYPE_TIMESTAMP,
        "uid"          => Model::TYPE_STRING,
        "userPassword" => Model::TYPE_STRING,
        "ou"           => Model::TYPE_STRING,
    );

    public function __construct(Storage_Ldap $db, $id = null)
    {
        parent::__construct($db, $id);
    }

    function getGroup()
    {
        return new Model_Ldap_Group($this->_db, $this->_values["group"]);
    }
}
?>
