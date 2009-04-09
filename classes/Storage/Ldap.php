<?php
require_once(CLASSES_PATH.'/Storage.php');
class Storage_Ldap extends Storage
{
    const DEREF_NEVER  = LDAP_DEREF_NEVER;
    const DEREF_ALWAYS = LDAP_DEREF_ALWAYS;
    const DEREF_SEARCH = LDAP_DEREF_SEARCHING;
    const DEREF_FIND   = LDAP_DEREF_FINDING;

    /**
     * @var string common data to establish connection to
     * storage server: hostname, port, username aka login,
     * password.
     * @author kstep
     */
    protected $_hostname;
    protected $_port;
    protected $_username;
    protected $_password;
    protected $_base_dn   = "";
    protected $_logged_in = false;

    public function __construct($hostname, $port = 0, $username = "", $password = "", $base_dn = null)
    {
        if (is_array($hostname))
        {
            $port = (int)$hostname["port"];
            $username = (string)$hostname["username"];
            $password = (string)$hostname["password"];
            $base_dn  = (string)$hostname["base_dn"];
            $hostname = (string)$hostname["hostname"];
        }

        $this->_hostname = $hostname;
        $this->_port = (int)$port;
        $this->_username = $username;
        $this->_password = $password;

        parent::__construct();
        if ($base_dn)
        {
            $this->setBaseDN($base_dn);
        }
        if (!empty($password) && !empty($username))
            $this->login($username, $password);
    }
    protected function connect()
    {
        $link = ldap_connect($this->_hostname, $this->_port);
        if (!$link)
        {
            throw new Storage_Ldap_Exception($link);
        }
        return $link;
    }

    public function __destruct()
    {
        ldap_close($this->_link);
    }

    public function query($filter, $attributes = null, $attrsonly = false, $limit = 0, $tmlimit = 0, $deref = self::DEREF_NEVER)
    {
        if ($result = ldap_search($this->_link, $this->_base_dn, $filter, $attributes, $attrsonly, $limit, $tmlimit, $deref))
        {
            if (is_resource($result))
                return new Storage_Ldap_Result($result, $this->_link);
        } else {
            throw new Storage_Ldap_Exception($this->_link);
        }
    }

    public function select($fields, $condition, $limit = 0)
    {
        if (is_array($condition)) $condition = "(&(" . $this->joinValues($condition, ")(") . "))";
        if (!is_array($fields)) $fields = explode(",", $fields);
        return $this->query($condition, $fields, false, $limit, 0, self::DEREF_NEVER);
    }
    public function insert($dn, $entry)
    {
        ldap_add($this->_link, $this->joinValues($dn), $entry);
    }
    public function update($dn, $entry)
    {
        ldap_modify($this->_link, $this->joinValues($dn), $entry);
    }
    public function delete($dn)
    {
        ldap_delete($this->_link, $this->joinValues($dn));
    }
    public function rename($dn, $newrdn, $newparent, $deleteoldrdns = true)
    {
        ldap_rename($this->_link, $this->joinValues($dn), $this->joinValues($newrdn), $this->joinValues($newparent), (bool)$deleteoldrdns);
    }

    protected function makePairs($key, $value)
    {
        return "$key=$value";
    }

    public function login($dn, $password)
    {
        if (!$dn) return false;

        $this->_username = $this->joinValues($dn);
        $this->_password = $password;

        if (strpos($this->_username, "=") === false) $this->_username = "cn=$this->_username";
        if ((strpos($this->_username, ",") === false) && !empty($this->_base_dn))
            $this->_username = "{$this->_username},{$this->_base_dn}";

        ldap_set_option($this->_link, LDAP_OPT_PROTOCOL_VERSION, 3);

        $this->_logged_in = ldap_bind($this->_link, $this->_username, $this->_password);
        return $this->_logged_in;
    }
    public function isLoggedIn()
    {
        return $this->_logged_in;
    }

    public function setBaseDN($base_dn)
    {
        $this->_base_dn = $base_dn;
        return $this;
    }
    public function getBaseDN()
    {
        return $this->_base_dn;
    }

}
