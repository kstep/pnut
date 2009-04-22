<?php
/**
 * @package Core
 * @subpackage Controller
 * Base abstract controller
 */
abstract class Controller
{
    /**
     * @var array actions description in format:
     * "actionname" => "actionMethodName"
     * @author kstep
     */
    protected $_actions;

    /**
     * @var Storage storage to operate with
     * @author kstep
     */
    protected $_storage;

    /**
     * Controller constructor
     * @param array actions description:
     * if this parameter is not set, and $_actions
     * property is not set in child as well,
     * it will be filled with all "action*" methods
     * in the class.
     * @return Controller
     * @author kstep
     */
    public function __construct($actions = null)
    {
        $this->setActions($actions);
		$this->init();
    }

	protected function init()
	{
	}

    protected function canPerform(Model_Owned $model, $action)
    {
        $method = "can".ucfirst(strtolower($action));
        if (!$model->$method($this->getAuthorizator()->getUser()))
        {
            $view = new View_Html();
            $view->deny($this->_realm);
            $object = strtolower(str_replace("Model_", "", get_class($article)));
            throw new Controller_Exception("Access denied for $action to $object $model.", 401, $view);
        }
    }

    /**
     * runs controller
     * @param string action to execute
     * @param array action parameters
     * @return View will be render()ed by Dispatcher
     * @author kstep
     */
    public function run($action, array $params = null)
    {
        if (array_key_exists($action, $this->_actions))
        {
            $method = $this->_actions[$action];
            if (method_exists($this, $method))
            {
                return $this->$method($params);
            }
            /*elseif (file_exists($method))
            {
                    require($method);
            }*/
            else
            {
                throw new Controller_Exception("Trying to run an action `$action' not defined in Controller class.");
            }
        }
        elseif (method_exists($method = "action".ucfirst($action), $this))
        {
            return $this->$method($params);
        }
        else
        {
            throw new Controller_Exception("Trying to run an action `$action' not set as allowed action in Controller class.");
        }
    }

    /**
     * check if method is an action method
     * @param string method name
     * @return bool true if this is an action method
     * @author kstep
     */
    final public static function isActionMethod($method)
    {
        return substr($method, 0, 6) == "action";
    }

    /**
     * created action name out from method name
     * @param string method name
     * @return string action name
     * @author kstep
     */
    final public static function makeActionFromMethod($method)
    {
        return strtolower(substr($method, 6));
    }

    /**
     * sets actions list to accept
     * @param array @see $_actions property,
     * if not set and if $_actions property is not set
     * as well, this method will make a list of actions
     * from all methods in class (@see makeActionFromMethod,
     * isActionMethod methods above).
     * @return void 
     * @author kstep
     */
    public function setActions(array $actions = null)
    {
        if ($actions === null)
        {
            if (!$this->_actions)
            {
                $methods = array_filter(array(__CLASS__, "isActionMethod"), get_class_methods($this));
                $actions = array_map(array(__CLASS__, "makeActionFromMethod"), $methods);
                $this->_actions = array_combine($actions, $methods);
            }
        }
        else
        {
            $this->_actions = array();
            foreach ($actions as $action => $method)
            {
                if (method_exists($this, $method))
                {
                    $this->_actions[$action] = $method;
                }
                else
                {
                    throw new Controller_Exception("Action `$action' is associated with method `$method' which was not defined in Controller class.");
                }
            }
        }
    }

    /**
     * returns current storage for the controller,
     * create one if not instantiated yet.
     * @return Storage
     * @author kstep
     */
    protected function getStorage()
    {
        if (!$this->_storage)
        {
            $config = Config::getInstance();
            $this->_storage = $config->getStorage("storage");
        }
        return $this->_storage;
    }

}
?>
