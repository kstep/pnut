<?php
/**
 * @package Site
 * Controller for files processing
 */
class Controller_File extends Controller
{
    protected $_actions = array(
        'download' => 'actionDownload',
        'view'     => 'actionView',
    );

	private function sendFile(Model_Attachment $attach, $download = false)
	{
        $view = new View_Raw($attach->getFilepath());
		$view->mimetype = $attach->mimetype;
		$view->filename = $attach->filename;
		$view->download = $download;
		return $view;
	}

    public function actionDownload($params)
    {
        return $this->sendFile(new Model_Attachment($this->getStorage(), $params["id"]), true);
    }

    public function actionView($params)
    {
        return $this->sendFile(new Model_Attachment($this->getStorage(), $params["id"]), false);
    }

}
?>
