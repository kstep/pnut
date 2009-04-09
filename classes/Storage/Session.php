<?php
require_once(CLASSES_PATH.'/Storage.php');
class Storage_Session extends Storage
{
    protected function connect()
    {
        session_start();
    }

    public function __destruct()
    {
        return;
    }
}
?>
