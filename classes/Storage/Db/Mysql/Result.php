<?php
final class Storage_Db_Mysql_Result extends Storage_Db_Result
{
    const FETCH_ASSOC = MYSQL_ASSOC;
    const FETCH_NUM   = MYSQL_NUM;
    const FETCH_BOTH  = MYSQL_BOTH;
	static private $bp = 0;

    public function __construct($resource, $link = null, $key_field = null, $fetch_mode = self::FETCH_ASSOC)
    {
        $this->_num_rows = mysql_num_rows($resource);
        parent::__construct($resource, $link, $key_field, $fetch_mode);
    }
    public function __destruct()
    {
        mysql_free_result($this->_resource);
    }

    public function fetchNext()
    {
        return mysql_fetch_array($this->_resource, $this->_fetch_mode);
    }
    public function seek($rowno = 0)
    {
        if ($rowno < 0 || $rowno > ($this->_num_rows - 1)) return;
        if (!@mysql_data_seek($this->_resource, (int)$rowno))
            throw new Storage_Db_Mysql_Exception($this->_link);
    }
}
?>
