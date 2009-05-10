<?php
class Controller_Default extends Controller
{
	protected $_actions = array(
		//'default' => 'actionTest',
		'default' => 'actionTopic',
		'topic' => 'actionTopic',
		'article' => 'actionArticle',
	);

	public function actionTest($params)
	{
		$view = new View_Html("test");
		return $view;
	}

	public function actionTopic($params)
	{
		$view = new View_Html('topic');
		if (!isset($params['path'])) $params['path'] = 'news';
		$view->topic = new Model_Topic($this->getStorage(), $params['path']);
		return $view;
	}

	public function actionArticle($params)
	{
		$view = new View_Html('article');
		$store = $this->getStorage();
		$view->topic = new Model_Topic($store, $params['path']);
		$view->article = $view->topic->getArticle($params['name']);
		return $view;
	}
}
?>
