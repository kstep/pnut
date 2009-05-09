<?php
/**
 * @package Site
 * @subpackage Admin
 * Admin controller for topics management
 */
class Controller_Admin_Topic extends Controller_Admin_Content
{
    protected $_actions = array(
        'default'  => 'actionListArticles',
        'edit'     => 'actionEdit',
        'new'      => 'actionNew',

        'remove'   => 'actionRemove',
        'move'     => 'actionMove',
        'reorder'  => 'actionReorder',
        'rename'   => 'actionRename',
        'create'   => 'actionCreate',
        'trashcan' => 'actionTrashcan',
    );

	protected function init()
	{
		if ($_GET['tf']) $_SESSION['tf'] = $_GET['tf'];
		if ($_SESSION['tf'] == 'visible')
		{
			Model_List_Topic::setVisibleOnly();
			Model_List_Article::setVisibleOnly();
			Model_Topic::setVisibleOnly();
			Model_Article::setVisibleOnly();
		}
	}

    private function saveTopic(Model_Topic $topic, View_Html $view)
    {
        if (isset($_REQUEST['save']))
        {
			if (!isset($_POST['flags'])) $_POST['flags'] = array();
            $topic->setData($_POST);
            if (!($errors = $topic->validate()))
            {
                $topic->save();
				$topic->getRights()->setRights($_POST['rights'], $_POST['owner'], $_POST['group'])->save();

                $view->redir('Admin_Topic', 'default', array( 'id' => $topic->getId() ));
                return true;
            }
            $view->errors = $errors;
        }
        return false;
    }

    public function actionListArticles($params)
    {
        $view = $this->htmlView('list_articles');
        if ($params['id'])
        {
            $view->topic = new Model_Topic($this->getStorage(), $params['id']);
        }
		if ($_REQUEST['ajax'])
		{
			$view->topicId = $view->topic? $view->topic->getId(): 0;
			$view->setTemplate('manage/nav_topics');
		}
        return $view;
    }


    public function actionCreate($params)
    {
        $store = $this->getStorage();
        $view  = $this->ajaxView();
        $topic = new Model_Topic($store);

        $topic->title = $_GET["title"];
        $topic->name  = $_GET["name"];
        $topic->owner = $this->getAuthorizator()->getUser()->getId();
        $topic->group = $this->getAuthorizator()->getUser()->group;

        $view->title  = $topic->title;
        $view->name   = $topic->name;

        if ($errors = $topic->validate())
        {
            $view->error = "validation failed";
            $view->errors = $errors;
        }
        else
        {
            $view->state = "created";
            try
            {
                $topic->save();
                $view->id = $topic->getId();
            }
            catch (Exception $e)
            {
                $view->state = "failed";
                $view->error = $e->getMessage();
            }
        }
        return $view;
    }

    public function actionNew($params)
    {
        $store = $this->getStorage();
        $view  = $this->htmlView('edit_topic');
        $view->topic   = new Model_Topic($store);

        if ($this->saveTopic($view->topic, $view))
            return $view;

        $view->parents = new Model_List_Topic($store);
        $view->users   = new Model_List_User($store);
        $view->groups  = new Model_List_Group($store);

        $view->topic->getRights()->setRights(array('ur','uw','ux','gr','gw','or'), $this->getAuthorizator()->getUser(), $this->getAuthorizator()->getUser()->getGroup());
        $view->topic->order = 0;
		$view->topic->flags   = array();
		$view->topic->parent  = (int)$params['id'];
		$view->topic->items_per_page = 20;
        return $view;
    }

    public function actionEdit($params)
    {
        $view = $this->htmlView("edit_topic");
        if ($params["id"])
        {
            $store = $this->getStorage();
            $view->topic = new Model_Topic($store, $params["id"]);
            $this->canPerform($view->topic, "edit");

            if ($this->saveTopic($view->topic, $view))
                return $view;

            $view->parents = new Model_List_Topic($store, "id <> ".$view->topic->getId());
            $view->users   = new Model_List_User($store);
            $view->groups  = new Model_List_Group($store);
        } else {
            $view->redir("Admin_Topic");
        }
        return $view;
    }

    public function actionMove($params)
    {
        $view = $this->ajaxView();
        $view->state = "failed";

        if ($params["id"])
        {
            $topic    = new Model_Topic($this->getStorage(), $params["id"]);
            $view->id = $topic->getId();

            $view->parent_id = (int)$params["targid"];
            if ($view->parent_id != 0)
            {
                $targTopic = new Model_Topic($this->getStorage(), $params["targid"]);
                $view->parent_id = $targTopic->getId();
                if (!$view->parent_id)
                {
                    $this->error = "Target topic not found.";
                    return $view;
                }
            }

            if ($view->id)
            {
                if ($view->id != $view->parent_id)
                {
					$this->canPerform($topic, "edit");
					if ($targTopic) $this->canPerform($targTopic, "edit");
                    $topic->parent = $view->parent_id;

                    if ($errors = $topic->validate())
                    {
                        $view->error = "validation failed";
                        $view->errors = $errors;
                    }
                    else
                    {
                        $view->state = "moved";
                        try
                        {
                            $topic->save();
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
                    $view->error = "Topic can't be root of itself.";
                }
            }
            else
            {
                $view->error = "Topic(s) not found.";
            }
        }
        else
        {
            $view->error = "Topic ID(s) are not set.";
        }
        return $view;
    }

    public function actionRemove($params)
    {
        $view = $this->ajaxView();
        $view->state = "failed";

        if ($params["id"])
        {
            $topic    = new Model_Topic($this->getStorage(), $params["id"]);
            $view->id = $topic->getId();

            if ($view->id)
            {
				$this->canPerform($topic, "delete");
				if ($topic->parent)
					$this->canPerform($topic->getParent(), "edit");
                $children = $topic->getDescendants();

                $view->state = "removed";
                try
                {
                    foreach ($children as $child)
                    {
                        $child->remove();
                    }
                    $topic->remove();
                }
                catch (Exception $e)
                {
                    $view->state = "failed";
                    $view->error = $e->getMessage();
                }
            }
            else
            {
                $view->error = "Topic not found.";
            }
        }
        else
        {
            $view->error = "Topic ID is not set.";
        }
        return $view;
    }

    public function actionRename($params)
    {
        $view = $this->ajaxView();
        $view->state = "failed";

        if ($params["id"])
        {
            $topic    = new Model_Topic($this->getStorage(), $params["id"]);
            $view->id = $topic->getId();

            if ($view->id)
            {
				$this->canPerform($topic, "edit");
                $topic->title = $_GET["title"];
                $topic->name  = $_GET["name"];
                $view->title  = $topic->title;
                $view->name   = $topic->name;

                if ($errors = $topic->validate())
                {
                    $view->error = "validation failed";
                    $view->errors = $errors;
                }
                else
                {
                    $view->state = "renamed";
                    try
                    {
                        $topic->save();
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
                $view->error = "Topic not found.";
            }
        }
        else
        {
            $view->error = "Topic ID is not set.";
        }
        return $view;
    }

	public function actionReorder($params)
	{
		$view = $this->ajaxView();
		$view->state = 'failed';

		if ($params['id'] && $params['targid'])
		{
			$otopic = new Model_Topic($this->getStorage(), $params['id']);
			$ttopic = new Model_Topic($this->getStorage(), $params['targid']);
			$view->id        = $otopic->getId();
			$view->target_id = $ttopic->getId();
			$view->parent_id = $otopic->parent;

			if ($otopic->isLoaded() && $ttopic->isLoaded())
			{
				$this->canPerform($otopic, 'edit');
				$view->state = 'reordered';
				$view->place = $params['place'];

				switch ($params['place'])
				{
				case 'before':
					$method = 'insertBefore';
					break;
				case 'after':
					$method = 'insertAfter';
					break;
				default:
					if ($otopic->isBefore($ttopic))
					{
						$method = 'insertAfter';
						$view->place = 'after';
					}
					else
					{
						$method = 'insertBefore';
						$view->place = 'before';
					}
				}

				try
				{
					$otopic->$method($ttopic);
				}
				catch (Exception $e)
				{
					$view->state = 'failed';
					$view->error = $e->getMessage();
				}
			}
			else
			{
				$view->error = 'Topic(s) not found.';
			}
		}
		else
		{
			$view->error = 'Topic IDs are not set.';
		}
		return $view;
	}

	public function actionTrashcan($params)
	{
		$view  = $this->htmlView("list_trashcan");
		$store = $this->getStorage();

		if ($_POST && isset($_POST['objects']))
		{
			$mode = isset($_POST['restore'])? 'update': (isset($_POST['cleanup'])? 'delete': '');
			if ($mode)
			{
				foreach ($_POST['objects'] as $objname => $idlist)
				{
					// @fixme: this is really ugly & incorrect way to do things,
					// I need to find some better way to do it:
					// - model class names should be incapsulated into some class,
					// - real removing & restoring objects should be incapsulated into
					// model class,
					// - removing of a model which can be put into trashcan should be
					// put into model class and it should mark object as removed
					// instead of removing it for real, there can be other method
					// for real removal of a model,
					// - getting list of removed objects should be incapsulated into
					// model list class.
					$class = "Model_".ucfirst($objname);
					$model = new $class ($store);
					if ($mode == 'delete')
						$store->delete($model->getTable(), array($model->getPk() => $idlist));
					else
						$store->update($model->getTable(), "flags = REPLACE(flags, 'removed', '')",  array($model->getPk() => $idlist));
					unset($model);
				}
			}
		}
		$olist = array();
		foreach (array('Статьи' => 'Article', 'Разделы' => 'Topic'/*, 'Опросы' => 'Poll'*/, 'Комментарии' => 'Comment') as $title => $oname)
		{
			$class = "Model_List_$oname";
			$model = new $class ($store, "FIND_IN_SET('removed', flags) > 0");
			if (count($model)) $olist[$title] = $model;
			unset($model);
		}
		$view->trashcan = $olist;
		return $view;
	}
}
?>
