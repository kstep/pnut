<?php
require_once(CLASSES_PATH.'/View.php');
class View_XmlRpc extends View
{
    protected $_data;

    public function __construct($data = null)
    {
        $this->_data = $data;
    }

    public function render()
    {
        header("Content-Type: text/xml");
        echo $this->parse();
    }

    public function parse()
    {
        return str_replace("\"?>", "\"?><methodResponse>", xmlrpc_encode($this->_data)) . "</methodResponse>";
    }

    /**
     * mark this view as XML-RPC error report
     * @param string error message
     * @param int error code
     * @return void
     * @author kstep
     */
    public function fail($errorMessage = "", $errorCode = 0)
    {
        $this->_data = array(
            "faultString" => (string)$errorMessage,
            "faultCode"   => (int)$errorCode,
        );
    }
}
?>
