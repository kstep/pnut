<?php
abstract class View_Image extends View_Http
{
    protected $_resource = null;

    protected $_width  = null;
    protected $_height = null;

	protected $_type   = null;

	protected $_templates_path = ATTACHMENTS_PATH;

    public function __construct($width = null, $height = null)
    {
        if ($width && $height)
        {
            $this->init($width, $height);
        }
        elseif ($width)
        {
			if (is_resource($width))
				$this->_resource = $this->loadResource($width);
			else
				parent::__construct($width);
        }
    }

    /**
     * create new empty image.
     * @param integer width
     * @param integer height
     * @return resource
     */
    abstract protected function createImage($width, $height);

    /**
     * load image from file.
     * @param string filename
     * @return resource
     */
    abstract protected function loadImage($filename);

	abstract protected function loadResource($resource);

    /**
     * load image from file.
     * @param string filename
     * @return void
     */
    public function setTemplate($filename)
    {
		parent::setTemplate($filename);
        $this->_resource = $this->loadImage($this->_filename);
    }

    /**
     * scale image by some factor
     * @param mixed either float factor to multiply current width/height by,
     * or integer = maximum width/height (by max coord)
     * @return void
     */
    public function scale($factor)
    {
        if (!is_float($factor))
        {
            $factor = $factor / max($this->_height, $this->_width);
        }
        $newheight = $this->_height * $factor;
        $newwidth  = $this->_width * $factor;
        $this->resize($newwidth, $newheight);
    }

    /**
     * create new empty image.
     * @param integer width
     * @param integer height
     * @return void
     */
    public function init($width, $height)
    {
        $this->_width    = (int)$width;
        $this->_height   = (int)$height;
        $this->_resource = $this->createImage($this->_width, $this->_height);
        if (!$this->_resource)
        {
            throw new View_Image_Exception("Error creating image");
        }
    }

    /**
     * resize image to these size.
     * @param integer new width
     * @param integer new height
     * @return void
     */
    abstract public function resize($newwidth, $newheight);

    /**
     * save image to this file.
     * @param string filename
     * @return void
     */
    abstract public function saveTo($filename);

}
?>
