<?php
/**
 * @package Site
 * @subpackage Admin
 * Admin controller for topics management
 */
class Controller_Admin_Tag extends Controller_Admin
{
    protected $_actions = array(
        'default'  => 'actionListTags',
        'edit'     => 'actionEdit',
        'new'      => 'actionNew',

        'remove'   => 'actionRemove',
    );

    private function saveTag(Model_Tag $tag, View_Html $view)
    {
        if (isset($_REQUEST['save']))
        {
            $tag->setData($_POST);
            if (!($errors = $tag->validate()))
            {
                $tag->save();

                $view->redir('Admin_Tag', 'default', array( 'id' => $tag->getId() ));
                return true;
            }
            $view->errors = $errors;
        }
        return false;
    }

    public function actionListTags($params)
    {
        $view = $this->htmlView('list_tags');
		$view->tags = Model_List_Tag::getTagsCloud($this->getStorage());
        return $view;
    }


	public function actionNew($params)
	{
		$view = $this->htmlView('edit_tag');
		$view->tag = new Model_Tag($this->getStorage());
		if ($this->saveTag($view->tag, $view))
			return $view;

		return $view;
	}

	public function actionEdit($params)
	{
		$view = $this->htmlView('edit_tag');
		$view->tag = new Model_Tag($this->getStorage(), (int)$params['id']);
		if ($this->saveTag($view->tag, $view))
			return $view;

		return $view;
	}

}
?>
