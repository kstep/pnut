<?php
/**
 * @package Site
 * Regenerate topics tree data
 */
class Controller_Regenerate extends Controller
{
    protected $_actions = array(
        'default' => 'actionDefault',
    );

    public function actionDefault($params)
    {
        $topic = new Model_Topic($this->getStorage());
        $group = new Model_Group($this->getStorage());
        $topic->regenerate();
        $group->regenerate();
    }
}
?>
