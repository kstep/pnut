<?php
abstract class Controller_Admin_Content extends Controller_Admin
{
	protected function htmlView($template)
	{
		$view = parent::htmlView($template);
        $view->topics = new Model_List_Topic($this->getStorage(), '');
		return $view;
	}
}
