<?php
class Controller_Admin_Attachment extends Controller_Admin_Content
{
    protected $_actions = array(
        'edit'   => 'actionEdit',
    );

    private function saveAttachment(Model_Attachment $attachment, View_Html $view)
    {
        if (isset($_REQUEST['save']))
        {
            $attachment->setData($_POST);
			if (!($errors = $attachment->validate()))
			{
				if ($_FILES && $_FILES['attach'])
				{
					if (!$attachment->uploadFile($_FILES['attach']))
					{
						$errors['attach'] = 'File attachment failed.';
						return false;
					}
				}
                $attachment->save();
				$attachment->getRights()->setRights($_POST['rights'], $_POST['owner'], $_POST['group'])->save();

                $view->redir('Admin_Article', 'edit', array( 'id' => $attachment->article ));
                return true;
            }
            $view->errors = $errors;
        }
        return false;
    }

    public function actionEdit($params)
    {
        $view = $this->htmlView("edit_attachment");
        if ($params['id'])
        {
			$store = $this->getStorage();
            $view->attachment = new Model_Attachment($store, $params['id']);
			$this->canPerform($view->attachment, 'edit');

			if ($this->saveAttachment($view->attachment, $view))
				return $view;

			$view->groups = new Model_List_Group($store);
			$view->users  = new Model_List_User($store);
        }
        else
        {
            $view->redir('Admin_Topic');
        }
        return $view;
    }
}
?>
