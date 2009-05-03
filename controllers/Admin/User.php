<?php
/**
 * @package Site
 * @subpackage Admin
 * Admin controller for users management
 */
class Controller_Admin_User extends Controller_Admin_Access
{
    protected $_actions = array(
        "remove"  => "actionRemove",
        "rename"  => "actionRename",
        "edit"    => "actionEdit",
        "new"     => "actionNew",
        "save"    => "actionSave",
		"move"    => "actionMove",
    );

	public function saveUser(Model_User $user, View_Html $view)
	{
        if (isset($_REQUEST['save']))
        {
			$errors = array();
			if ($_POST['password'] !== $_POST['checkpass'])
				$errors['checkpass'] = _('Passwords don\'t match');

			if (!isset($_POST['flags'])) $_POST['flags'] = array();
            $user->setData($_POST);
			$errors += $user->validate();

            if (!$errors)
            {
				$user->save();
				$view->redir('Admin_Group', 'default', array( 'id' => $user->group ));
				return true;
            }
            $view->errors = $errors;
        }
        return false;
	}

    public function actionRemove($params)
    {
        $view = $this->ajaxView();
        $view->state = "failed";

        if ($params["id"])
        {
            $user = new Model_User($this->getStorage(), $params["id"]);
            //$this->canPerform($user, "delete");
            $view->id = $user->getId();

            if ($view->id)
            {
                $view->state = "removed";
                try
                {
                    $user->remove();
                }
                catch (Exception $e)
                {
                    $view->state = "failed";
                    $view->error = $e->getMessage();
                }
            }
            else
            {
                $view->error = "User not found.";
            }
        }
        else
        {
            $view->error = "User ID is not set.";
        }
        return $view;
    }

    public function actionEdit($params)
    {
        $view = $this->htmlView("edit_user");
        if ($params["id"])
        {
            $store = $this->getStorage();
            $view->user = new Model_User($store, $params["id"]);
            if ($this->saveUser($view->user, $view))
                return $view;
           
            //$this->canPerform($view->article, "edit");

        } else {
            $view->redir("Admin_Group");
        }
        return $view;
    }

	public function actionNew($params)
	{
        $store = $this->getStorage();
        $view = $this->htmlView('edit_user');
        $view->user = new Model_User($store);
        if ($this->saveUser($view->user, $view))
            return $view;

		$view->user->group  = $params['id'];
		$view->user->role   = $view->user->getGroup()->role;
		$view->user->rating = 0.00;
		$view->user->gender = 'female';
        return $view;
	}

	public function actionMove($params)
	{
        $view = $this->ajaxView('user');
        $view->state = "failed";

        if ($params["id"] && $params["targid"])
        {
            $user  = new Model_User($this->getStorage(), $params["id"]);
            $group = new Model_Group($this->getStorage(), $params["targid"]);
            $view->id       = $user->getId();
            $view->group_id = $group->getId();

            if ($view->id && $view->group_id)
            {
                if ($user->group != $group->getId())
                {
                    //$this->canPerform($article, "edit");
                    //$this->canPerform($topic, "edit");

					$view->state = "moved";
					$user->group = $group->getId();
					try
					{
						$user->save();
					}
					catch (Exception $e)
					{
						$view->state = "failed";
						$view->error = $e->getMessage();
                    }
                }
                else
                {
                    $view->state = "notmoved";
                }
            }
            else
            {
                $view->error = "User or group not found.";
            }
        }
        else
        {
            $view->error = "User or group ID is not set.";
        }
        return $view;
	}

    public function actionRename($params)
    {
        $view = $this->ajaxView('user');
        $view->state = "failed";

        if ($params["id"])
        {
            $user  = new Model_User($this->getStorage(), $params["id"]);
            $view->id = $user->getId();

            if ($view->id)
            {
                $this->canPerform($user, "edit");

                $user->username = $_GET["username"];
                $user->login    = $_GET["login"];
                $user->email    = $_GET["email"];

                $view->username = $user->username;
                $view->login    = $user->login;
                $view->email    = $user->email;

                if ($errors = $user->validate())
                {
                    $view->error = "validation failed";
                    $view->errors = $errors;
                }
                else
                {
                    $view->state = "renamed";
                    try
                    {
                        $user->save();
                    }
                    catch (Exception $e)
                    {
                        $view->state = "failed";
                        $view->error = $e->getMessage();
                    }
                }
            }
        }
        return $view;
    }
}
?>
