<?php
abstract class Controller_Restricted extends Controller
{
    /**
     * @var Authorizator to be used to determine user's rights to do actions in the controller
     * @author kstep
     */
    protected $_authorizator;

    /**
     * @var authorization realm
     * @author kstep
     */
    protected $_realm = "RestrictedArea";

    public function __construct(Authorizator $authorizator = null, $realm = null, $actions = null)
    {
        $this->_authorizator = $authorizator;
        if ($realm !== null)
            $this->_realm = $realm;

        if (!$this->authorize() || !$this->_authorizator->canAccess($this->_realm))
        {
            $this->accessDenied();
            exit;
        }

        parent::__construct($actions);
    }

    public function run($action, $params)
    {
        if ($this->getAuthorizator()->canPerform($this, $action))
            return parent::run($action, $params);
        else
            $this->accessDenied();
    }

    /**
     * authorizes user to access this controller's actions
     * @return bool true if user is authorized
     * @author kstep
     */
    protected function authorize()
    {
        $result = false;
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
        {
            $user = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            $result = $this->getAuthorizator()->authorize($user, $pass);
        }
        return $result;
    }

    /**
     * action to be executed if access denied for the user
     * @return void
     * @author kstep
     */
    protected function accessDenied()
    {
        $view = new View_Html();
        $view->deny($this->_realm);
        throw new Controller_Exception("Access denied to $this->_realm.", 401, $view);
    }

    /**
     * returns Authorizator to be used for authorization, creates one
     * if it's not instantiated yet.
     * @return Authorizator
     * @author kstep
     */
    protected function getAuthorizator()
    {
        if (!$this->_authorizator)
        {
            $this->_authorizator = Authorizator::create($this->getStorage());
        }
        return $this->_authorizator;
    }

    public function getRealm()
    {
        return $this->_realm;
    }
}
?>
