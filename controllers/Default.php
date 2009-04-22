<?php
class Controller_Default extends Controller
{
	protected $_actions = array(
		'default' => 'actionTest',
	);

	public function actionTest($params)
	{
		$view = new View_Html("test");
		return $view;
	}
}
?>
