<?php
require_once(CLASSES_PATH.'/Storage.php');
class Storage_Xml extends Storage
{
    protected $_filename = "";

    public function __construct($filename)
    {
        $this->_filename = $filename;
        $this->_xml = new SimpleXMLElement($this->_filename, null, true);
    }

    
}
?>
