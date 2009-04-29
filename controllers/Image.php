<?php
/**
 * @package Site
 * Controller for image processing
 */
class Controller_Image extends Controller
{
    protected $_actions = array(
        'thumbnail' => 'actionThumbnail',
        'meta'      => 'actionMetadata',
    );


    private function imageNotFound()
    {
        throw new Controller_Exception("Image not found", 404, new View_Html());
    }

    public function actionThumbnail($params)
    {
        $attachment = new Model_Attachment($this->getStorage(), $params['id']);
        if ($attachment->getId())
        {
			if ($attachment->isImage())
			{
				$file = $attachment->getFilepath();
			}
			elseif ($attachment->isVideo())
			{
				if (class_exists("ffmpeg_movie"))
				{
					$film = new ffmpeg_movie($attachment->getFilepath());
					$frame = ceil($film->getFrameCount() * 0.1);
					$file = $film->getFrame($frame)->toGDImage();
				}
				else
				{
					$this->imageNotFound();
				}
			}
			else
			{
				$this->imageNotFound();
			}

			$view = new View_Image_Gd2($file);

            if ($view)
            {
                $view->scale($params['scale']);
            }
            else
            {
                $this->imageNotFound();
            }
        }
        else
        {
            $this->imageNotFound();
        }
        return $view;
    }

    public function actionMetadata($params)
    {
        $attachment = new Model_Attachment($this->getStorage(), $params['id']);
        if ($attachment->getId())
        {
            $view = new View_Json();
            $view->id   = $attachment->getId();
            $view->info = $attachment->toArray();
            if ($attachment->isImage() && $attachment->checkFile())
                $view->image = getimagesize($attachment->getFilepath());
        }
        else
        {
            $this->imageNotFound();
        }
        return $view;
    }
}
?>
