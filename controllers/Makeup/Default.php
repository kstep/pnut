<?php
/**
 * @package Site
 * Default controller
 */
class Controller_Makeup_Default extends Controller
{

    protected $_actions = array(
        'article' => 'actionArticle',
        'topic'   => 'actionTopic',
        'comment' => 'actionComment',
    );

	public function init()
	{
		Model_List_Topic::setVisibleOnly();
		Model_List_Article::setVisibleOnly();
		Model_Topic::setVisibleOnly();
		Model_Article::setVisibleOnly();
	}

    private function prepareNavigation(&$view, $path)
    {
        $store = $this->getStorage();
        $view->rootTopics = new Model_List_Topic($store, array( 'parent_id' => 0 ));
        $view->topic = new Model_Topic($store, $path);
        if (!$view->topic->getId())
        {
            $view->setTemplate('404');
            throw new Controller_Exception('Not found', 404, $view);
        }

    }

    public function actionTopic($params)
    {
        $view  = new View_Html('makeup-and-style/default');
        $this->prepareNavigation($view, $params['path']);
    
        if ($view->topic->parent == 0)
        {
            $topic = $view->topic->getDefaultSubtopic();
            if ($topic)
            {
                $view->redir('Default', 'topic', array( 'path' => $topic->getPath().'/' ) );
                return $view;
            }
            $view->rootTopic = $view->topic;
        }
        else
        {
            $view->rootTopic = $view->topic->getRoot();
        }

		if ($article = $view->topic->getDefaultArticle())
		{
			$view->redir('Default', 'article', array( 'path' => $view->topic->getPath(), 'article' => $article->name ));
		}
		else
		{
			$view->pageNo  = (int)$params['page'];
			if ($view->pageNo < 1) $view->pageNo = 1;
		}

        return $view;
    }

    private function saveComment(View_Html $view)
    {
        if (in_array("comments", $view->article->flags) && $_POST['comment'] === 'save' && !$_POST['comment_url'])
        {
            $comment = new Model_Comment($this->getStorage());
            $comment->username = nl2br(htmlspecialchars(strip_tags($_POST['comment_username'])));
            $comment->email    = $_POST['comment_email'];
            $comment->title    = nl2br(htmlspecialchars(strip_tags($_POST['comment_title'])));
            $comment->content  = nl2br(htmlspecialchars(strip_tags($_POST['comment_content'])));
            $comment->article  = $view->article->getId();
            //$comment->owner    = $view->article->owner;
            //$comment->group    = $view->article->group;

            if (empty($comment->title))
                $comment->title = "Без темы";

            if (!($errors = $comment->validate()))
            {
                $comment->save();
                $view->redir("Default", "article", array( 'path' => $view->topic->getPath(), 'article' => $view->article->getId() ));
                return true;
            }
            $view->errors = $errors;
            $view->newComment = $comment;
        }
        return false;
    }

    public function actionArticle($params)
    {
        $view = new View_Html('makeup-and-style/article');

        $this->prepareNavigation($view, $params['path']);

        if ($params['article'])
        {
			$idparam = is_numeric($params['article'])? 'id': 'name';
            $view->article = new Model_Article($this->getStorage(), array( 'topic_id' => $view->topic->getId(), $idparam => $params['article'] ));
            if (!$view->article->isLoaded())
            {
                $view->setTemplate('404');
                throw new Controller_Exception('Not found', 404, $view);
            }
            else
            {
                if ($this->saveComment($view))
                    return $view;

                if ($view->topic->parent != 0)
                {
                    $view->rootTopic = $view->topic->getRoot();
                }
                else
                {
                    $view->rootTopic = $view->topic;
                }
            }

            $view->pageNo = (int)$params['page'];
            if ($view->pageNo < 1) $view->pageNo = 1;

			$view->commentsPage = (int)$_GET['cp'];
			if ($view->commentsPage < 1) $view->commentsPage = 1;
        }
        else
        {
            $view->setTemplate('404');
            throw new Controller_Exception("Not found", 404, $view);
        }
        
        return $view;
    }
}
?>
