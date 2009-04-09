<?php
require_once(CLASSES_PATH.'/Dispatcher.php');
class Dispatcher_XmlRpc extends Dispatcher
{
    public function findRoute($path)
    {
        global $HTTP_RAW_POST_DATA;
        $result = parent::findRoute($path);
        //$rawdata = xmlrpc_encode_request("authorize", array( "cons", "cons" ));
        //if ((!$result["action"] || !$result["params"]) && ($rawdata))
        if ((!$result["action"] || !$result["params"]) && ($rawdata = $HTTP_RAW_POST_DATA))
        {
            $params = xmlrpc_decode_request($rawdata, &$action);
            if (!$result["action"]) $result["action"] = $action;
            if (!$result["params"]) $result["params"] = $params;
        }
        return $result;
    }
}
?>
