<?php
/**
 * @package Site
 * @subpackage Admin
 * Base admin panel controller
 */
abstract class Controller_Admin extends Controller_Restricted
{
    protected $_realm = "Admin";

    protected function ajaxView($objname = 'topic')
    {
        if ($_REQUEST["ajax"])
        {
            $view = new View_Json();
            $view->objname = $objname;
        } else {
            $view = new View_Html();
            $view->redir("Admin_Topic");
        }
        return $view;
    }

	protected function canPerform(Model $model, $action)
	{
		return true;
		$user = $this->getAuthorizator()->getUser();
		$result = false;
		switch ($action)
		{
			case "edit": $result = $user->canEdit($model); break;
			case "view": $result = $user->canView($model); break;
			case "remove":
			case "delete": $result = $user->canDelete($model); break;
		}
		if (!$result)
		{
			throw new Controller_Exception("Permission denied.");
		}
	}

    protected function htmlView($template)
    {
        $view = new View_Html("admin/$template");
        $view->topics = new Model_List_Topic($this->getStorage());
		$view->groups = new Model_List_Group($this->getStorage());
        return $view;
    }

    protected function getAuthorizator()
    {
        if (!$this->_authorizator)
        {
            $config = Config::getInstance();
            if (!$config->auth) $config->loadXML("configs/auth.xml", "auth", true, "name");
            $this->_authorizator = new Authorizator_Rules($this->getStorage(), $config->auth);
        }
        return $this->_authorizator;
    }

}
?>
