<?php
class Controller_Admin_Group extends Controller_Admin_Access
{
    protected $_actions = array(
        "default" => "actionListUsers",
        "edit"    => "actionEdit",
		"new"     => "actionNew",
        "remove"  => "actionRemove",
		"rename"  => "actionRename",
		"move"    => "actionMove",
		"create"  => "actionCreate",
    );

	public function saveGroup(Model_Group $group, View_Html $view)
	{
        if (isset($_REQUEST['save']))
        {
            $group->setData($_POST);

            if (!($errors = $group->validate()))
            {
				$group->save();
				$view->redir('Admin_Group', 'default', array( 'id' => $group->getId() ));
				return true;
            }
            $view->errors = $errors;
        }
        return false;
	}

    public function actionListUsers($params)
    {
        $view = $this->htmlView('list_users');
        if ($params['id'])
        {
            $view->group = new Model_Group($this->getStorage(), $params['id']);
        }
        return $view;
    }

	public function actionEdit($params)
	{
		$view = $this->htmlView('edit_group');
        if ($params["id"])
        {
            $store = $this->getStorage();
            $view->group   = new Model_Group($store, $params["id"]);

            if ($this->saveGroup($view->group, $view))
                return $view;

            $view->parents = new Model_List_Group($store, "id <> ".$view->group->getId());
        } else {
            $view->redir("Admin_Group");
        }
        return $view;
	}

	public function actionNew($params)
	{
        $store = $this->getStorage();
        $view = $this->htmlView('edit_group');
        $view->group   = new Model_Group($store);
        if ($this->saveGroup($view->group, $view))
            return $view;

        $view->parents = new Model_List_Group($store);
		$view->group->role   = 'user';
		$view->group->parent = (int)$params['id'];
        return $view;
	}

	public function actionRemove($params)
	{
        $view = $this->ajaxView('group');
        $view->state = "failed";

        if ($params["id"])
        {
            $group = new Model_Group($this->getStorage(), $params["id"]);
            //$this->canPerform($user, "delete");
            $view->id = $group->getId();

            if ($view->id)
            {
				if (count($group->getUsers()) > 0)
				{
					$view->error = "Group is not empty.";
				}
				else
				{
					$view->state = "removed";
					try
					{
						$group->remove();
					}
					catch (Exception $e)
					{
						$view->state = "failed";
						$view->error = $e->getMessage();
					}
				}
            }
            else
            {
                $view->error = "Group not found.";
            }
        }
        else
        {
            $view->error = "Group ID is not set.";
        }
        return $view;
	}

	public function actionRename($params)
	{
        $view = $this->ajaxView('group');
        $view->state = "failed";

        if ($params["id"])
        {
            $group = new Model_Group($this->getStorage(), $params["id"]);
            //$this->canPerform($view->topic, "edit");
            $view->id = $group->getId();

            if ($view->id)
            {
                $group->title = $_GET["title"];
                $group->name  = $_GET["name"];
                $view->title  = $group->title;
                $view->name   = $group->name;

                if ($errors = $group->validate())
                {
                    $view->error = "validation failed";
                    $view->errors = $errors;
                }
                else
                {
                    $view->state = "renamed";
                    try
                    {
                        $group->save();
                    }
                    catch (Exception $e)
                    {
                        $view->state = "failed";
                        $view->error = $e->getMessage();
                    }
                }
            }
            else
            {
                $view->error = "Group not found.";
            }
        }
        else
        {
            $view->error = "Group ID is not set.";
        }
        return $view;
	}

    public function actionCreate($params)
    {
        $store = $this->getStorage();
        $view  = $this->ajaxView('group');
        $group = new Model_Group($store);

        $group->title = $_GET["title"];
        $group->name  = $_GET["name"];

        $view->title  = $group->title;
        $view->name   = $group->name;

        if ($errors = $group->validate())
        {
            $view->error = "validation failed";
            $view->errors = $errors;
        }
        else
        {
            $view->state = "created";
            try
            {
                $group->save();
                $view->id = $group->getId();
            }
            catch (Exception $e)
            {
                $view->state = "failed";
                $view->error = $e->getMessage();
            }
        }
        return $view;
    }

    public function actionMove($params)
    {
        $view = $this->ajaxView('group');
        $view->state = "failed";

        if ($params["id"])
        {
            $group    = new Model_Group($this->getStorage(), $params["id"]);
            $view->id = $group->getId();

            $view->parent_id = (int)$params['targid'];
            if ($view->parent_id != 0)
            {
                $targGroup = new Model_Group($this->getStorage(), $params["targid"]);
                $view->parent_id = $targGroup->getId();
                if (!$view->parent_id)
                {
                    $this->error = "Target group not found.";
                    return $view;
                }
            }

            if ($view->id)
            {
                if ($view->id != $view->parent_id)
                {
                    $group->parent = $view->parent_id;

                    if ($errors = $group->validate())
                    {
                        $view->error = "validation failed";
                        $view->errors = $errors;
                    }
                    else
                    {
                        $view->state = "moved";
                        try
                        {
                            $group->save();
                        }
                        catch (Exception $e)
                        {
                            $view->state = "failed";
                            $view->error = $e->getMessage();
                        }
                    }
                }
                else
                {
                    $view->state = "notmoved";
                    $view->error = "Group can't be root of itself.";
                }
            }
            else
            {
                $view->error = "Group(s) not found.";
            }
        }
        else
        {
            $view->error = "Group ID(s) are not set.";
        }
        return $view;
    }
}
?>
