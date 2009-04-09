<?php
class View_Image_Gd2 extends View_Image
{
    public function createImage($width, $height)
    {
        return @imagecreatetruecolor((int)$width, (int)$height);
    }

	protected function loadResource($resource)
	{
		if ("gd" == get_resource_type($resource))
		{
			$this->_width  = imagesx($resource);
			$this->_height = imagesy($resource);
			$this->_type   = "png";
			return $resource;
		}
		else
		{
            throw new View_Image_Exception("Incorrect resource type: 'gd' expected");
		}
	}

    protected function loadImage($filename)
    {
		$info = @getimagesize($filename);
		if (!$info)
            throw new View_Image_Exception("File not loaded: $this->_filename");

		list($this->_width, $this->_height) = $info;
		$this->_type = substr($info['mime'], 6);

		$function = "imagecreatefrom{$this->_type}";
        return $function ($filename);
    }

    public function render()
    {
        header("Content-Type: image/{$this->_type}");
		$this->parse();
	}

	public function parse()
	{
		$function = "image{$this->_type}";
        $function ($this->_resource);
	}

    public function saveTo($filename)
	{
		$function = "image{$this->_type}";
        $function ($this->_resource, $filename);
	}

    public function __destruct()
    {
        imagedestroy($this->_resource);
    }

    public function resize($newwidth, $newheight)
    {
        $newwidth  = (int)$newwidth;
        $newheight = (int)$newheight;
        $newres = $this->createImage($newwidth, $newheight);
        if (@imagecopyresampled($newres, $this->_resource, 0, 0, 0, 0, $newwidth, $newheight, $this->_width, $this->_height))
        {
            imagedestroy($this->_resource);
            $this->_resource = $newres;
            $this->_width    = $newwidth;
            $this->_height   = $newheight;
        }
    }

    public static function create($filename)
    {
        $mime = mime_content_type($filename);
        if (!$mime or substr($mime, 0, 5) != "image") return null;
        $class = "View_Image_Gd2_".ucfirst(strtolower(substr($mime, 6)));
        return new $class ($filename);
    }
}
?>
