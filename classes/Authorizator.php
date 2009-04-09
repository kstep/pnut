<?php
/**
 * @package Core
 * @subpackage Authorizator
 * Base Authorizator
 */
class Authorizator
{
    /**
     * @var Storage storage to load users from
     * @author kstep
     */
    protected $_storage;
    /**
     * @var Model_User authorized user (null if not authorized)
     * @author kstep
     */
    protected $_authorized_user;

    public function __construct(Storage $store)
    {
        $this->_storage = $store;
    }

    public static function create(Storage $store)
    {
        if ($store instanceof Storage_Ldap) {
            $class = "Authorizator_Ldap";
        } else {
            $class = "Authorizator";
        }
        return new $class ($store);
    }

    /**
     * Authorize user by username & passord, should store
     * authorized user into $_authorized_user.
     * @param string username
     * @param string password
     * @return bool true only if user id authorized
     **/
    public function authorize($username, $password)
    {
        $this->_authorized_user = null;
        if (empty($username) || empty($password)) return false;

        $user = $this->createUser($username, $password);

        if ($user && $user instanceof Model_User && $user->isLoaded())
            $this->_authorized_user = $user;

        return (bool)$this->_authorized_user;
    }

    /**
     * create user by given credentials
     * @param string username aka login
     * @param string password
     * @return Model_User created user
     * @author kstep
     */
    protected function createUser($username, $password)
    {
        return new Model_User($this->_storage, array( "login" => $username, "password" => md5($password) ));
    }


    /**
     * check if user can perform some action from controller
     * in determined realm
     * @param string authorization realm
     * @param string or Controller
     * @param string action in controller
     * @return bool true if user is authorized for the action
     * @author kstep
     */
    public function canPerform(Controller_Restricted $controller, $action)
    {
        return (bool)$this->_authorized_user;
    }

    /**
     * get authorized user
     * @return Model_User
     * @author kstep
     */
    public function getUser()
    {
        return $this->_authorized_user;
    }

    /**
     * check if user can even access given realm
     * @param string realm
     * @return bool true if user is authorized to access
     * the given authorization realm
     * @author kstep
     */
    public function canAccess($realm)
    {
        return (bool)$this->_authorized_user && ($this->_authorized_user->role == 'admin' || $this->_authorized_user->role == 'superuser');
    }

}
?>
