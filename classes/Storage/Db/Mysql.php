<?php
final class Storage_Db_Mysql extends Storage_Db
{
    protected function connect()
    {
        $host = $this->_hostname;
        if ($this->_port) $host .= ":" . $this->_port;

        if ($link = mysql_connect($host, $this->_username, $this->_password))
        {
            mysql_query("SET NAMES utf8", $link);
            return $link;
        } else {
            throw new Storage_Db_Mysql_Exception($link);
        }
    }

    public function setDatabase($database)
    {
        if (!mysql_select_db($database))
        {
            throw new Storage_Db_Mysql_Exception($this->_link);
        }
        return parent::setDatabase($database);
    }

    public function query($query)
    {
        if ($result = mysql_query($query))
        {
            if (is_resource($result))
                return new Storage_Db_Mysql_Result($result, $this->_link);
        } else {
            throw new Storage_Db_Mysql_Exception($this->_link);
        }
    }

    public function quote($value)
    {
        return mysql_real_escape_string($value, $this->_link);
    }

    public function getAffectedRows()
    {
        if (!$this->_affected_rows)
            $this->_affected_rows = mysql_affected_rows($this->_link);
        return $this->_affected_rows;
    }

    public function getLastInsertId()
    {
        return mysql_insert_id($this->_link);
    }

    public function getFieldsList($table)
    {
        if (!($result = mysql_query("SHOW COLUMNS FROM {$table}", $this->_link)))
            throw new Storage_Db_Mysql_Exception($this->_link);

        $num_fields = mysql_num_rows($result);
        $return = array();
        for ($i = 0; $i < $num_fields; $i++)
        {
            $row = mysql_fetch_row($result);
            $return[$row[0]] = $row[1];
        }
        mysql_free_result($result);
        return $return;
    }

    public function __destruct()
    {
        mysql_close($this->_link);
    }
}
?>
