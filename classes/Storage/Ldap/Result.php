<?php
require_once(CLASSES_PATH.'/Storage/Result.php');
final class Storage_Ldap_Result extends Storage_Result
{
    private $_current_entry = null;
    protected $_key_field = "dn";

    public function __construct($resource, $link)
    {
        $this->_num_rows = ldap_count_entries($link, $resource);
        parent::__construct($resource, $link);
    }
    public function __destruct()
    {
        ldap_free_result($this->_resource);
    }

    private function setEntry($entry)
    {
        if ($entry)
        {
            $this->_current_entry = $entry;
            $row = ldap_get_attributes($this->_link, $entry);
            $row["dn"] = ldap_get_dn($this->_link, $entry);
            return $row;
        }
    }

    public function fetchFirst()
    {
        $entry = ldap_first_entry($this->_link, $this->_resource);
        return $this->setEntry($entry);
    }
    public function fetchNext()
    {
        $entry = ldap_next_entry($this->_link, $this->_current_entry);
        return $this->setEntry($entry);
    }
    public function seek($rowno = 0)
    {
        if ($rowno < 0 || $rowno > ($this->_num_rows - 1)) return;
        $this->fetchFirst();
        while ($rowno-- > 0) $this->fetchNext();
    }

    public function fetchAll()
    {
        $result = array();
        foreach ($this as $value)
            $result[] = $value;
        return $result;
    }
}
?>
