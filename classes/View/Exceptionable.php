<?php
interface View_Exceptionable
{
    public function setError($code, $message);
    public function isError();
}
?>
