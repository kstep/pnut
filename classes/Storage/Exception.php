<?php
abstract class Storage_Exception extends Exception
{
    /**
     * @var resource low level link to storage resource
     * @author kstep
     */
    protected $_link;

    /**
     * these methods must return error message & code,
     * as it's depends on concrete Storage implementation
     * (e.g. calls to mysql_error() & mysql_errno() for MySQL).
     * @return string error desctiption
     * @author kstep
     */
    abstract protected function getErrorMessage();

    /**
     * @return int error code
     * @author kstep
     */
    abstract protected function getErrorCode();

    public function __construct($link)
    {
        $this->_link = $link;
        parent::__construct($this->getErrorMessage(), $this->getErrorCode());
    }
}
?>
