<?php
class Controller_Admin_Comment extends Controller_Admin
{
    protected $_actions = array(
        'edit'   => 'actionEdit',
        'remove' => 'actionRemove',
    );

    private function saveComment(Model_Comment $comment, View_Html $view)
    {
        if (isset($_REQUEST['save']))
        {
            $comment->setData($_POST);
            if (!($errors = $comment->validate()))
            {
                $comment->save();
                $view->redir('Admin_Article', 'edit', array( 'id' => $comment->article ));
                return true;
            }
            $view->errors = $errors;
        }
        return false;
    }

    public function actionEdit($params)
    {
        $view = $this->htmlView("edit_comment");
        if ($params['id'])
        {
            $view->comment = new Model_Comment($this->getStorage(), $params['id']);
			$this->canPerform($view->comment->getArticle(), 'edit');

            $this->saveComment($view->comment, $view);
        }
        else
        {
            $view->redir('Admin_Topic');
        }
        return $view;
    }

    public function actionRemove($params)
    {
		$view = $this->ajaxView('comment');
        $view->state = 'failed';

        if ($params['id'])
        {
            $comment = new Model_Comment($this->getStorage(), $params['id']);
			$this->canPerform($view->comment->getArticle(), 'edit');

            if ($view instanceof View_Html)
                $view->redir('Admin_Article', 'edit', array( 'id' => $comment->article ));

            if ($comment->isLoaded())
            {
                $view->state = 'removed';
                $view->id = $comment->getId();
                try
                {
                    //$comment->remove();
					Model_Trashcan::put($comment);
                }
                catch (Exception $e)
                {
                    $view->state = 'failed';
                    $view->error = $e->getMessage();
                }
            }
            else
            {
                $view->error = 'Comment not found.';
            }
        }
        else
        {
            $view->error = 'Comment ID is not set.';
        }
        return $view;
    }
}
?>
