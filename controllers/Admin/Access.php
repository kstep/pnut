<?php
abstract class Controller_Admin_Access extends Controller_Admin
{
	protected function htmlView($template)
	{
		$view = parent::htmlView($template);
        $view->groups = new Model_List_Group($this->getStorage());
		return $view;
	}
}
