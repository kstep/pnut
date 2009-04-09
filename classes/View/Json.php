<?php
require_once(CLASSES_PATH.'/View.php');
class View_Json extends View
{
    protected $_data;

    public function __construct($data = null)
    {
        $this->_data = $data;
    }

    public function render()
    {
        header("Content-Type: text/json");
        echo $this->parse();
    }

    public function parse()
    {
        return json_encode($this->_data);
    }

}
?>
