<?php
class View_Raw extends View_Http
{
	protected $_templates_path = ATTACHMENTS_PATH;

    public function render()
    {
		$size = filesize($this->_filename);
		$mime = $this->mimetype? $this->mimetype: mime_content_type($this->_filename);
		$file = $this->filename? $this->filename: basename($this->_filename);

		$this->_headers = array(
			"Content-Type: {$mime}; name=\"{$file}\"",
			"Content-Length: {$size}",
		);
		if ($this->download)
			$this->_headers[] = "Content-Disposition: attachment; filename=\"{$file}\"";

		$this->sendHeaders();
		readfile($this->_filename);
    }

    public function parse()
    {
        return file_get_contents($this->_filename);
    }

}
?>
