<?php
abstract class Storage_Db extends Storage
{
    protected $_affected_rows = 0;

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
    protected $_database;

    static public $num_queries = 0;
    static public $time_queries = 0.0;
    static public $log_queries = array();
    static public $tlog_queries = array();

    public function __construct($hostname, $port = 0, $username = "", $password = "", $database = null)
    {
        if (is_array($hostname))
        {
            $port = (int)$hostname["port"];
            $username = (string)$hostname["username"];
            $password = (string)$hostname["password"];
            $database = (string)$hostname["database"];
            $hostname = (string)$hostname["hostname"];
        }
        $this->_hostname = $hostname;
        $this->_port = (int)$port;
        $this->_username = $username;
        $this->_password = $password;
        parent::__construct();
        if ($database)
        {
            $this->setDatabase($database);
        }
    }

    /**
     * do sql query to database
     * @param string query
     * @return DbResult or int
     **/
    abstract public function query($query);

    abstract public function quote($value);
    abstract public function getAffectedRows();
    abstract public function getLastInsertId();
    abstract public function getFieldsList($table);

    public function setDatabase($database)
    {
        $this->_database = $database;
        return $this;
    }

    public function getDatabase($database)
    {
        return $this->_database;
    }

    protected function makePairs($key, $value)
    {
		if (is_array($value))
			return "`$key` IN ('" . implode("','", array_map(array($this, 'quote'), $value)) . "')";
		else
			return "`$key` = '" . $this->quote($value) . "'";
    }

    public function select($table, $fields = "*", $condition = "", $limit = 0, $offset = 0, $order = "", $group = "", $having = "")
    {
        $query = "SELECT " . (is_array($fields)? implode(", ", $fields): $fields) . " FROM " . (is_array($table)? implode(", ", $table): $table);
        if ($condition) $query .= " WHERE " . $this->joinValues($condition, " AND ");
        if ($order) $query .= " ORDER BY " . (is_array($order)? implode(", ", $order): $order);
        if ($group) $query .= " GROUP BY " . (is_array($group)? implode(", ", $group): $group);
        if ($having) $query .= " HAVING " . $this->joinValues($having, " AND ");
        if ($limit)
        {
            $query .= " LIMIT " . (int)$limit;
            if ($offset) $query .= " OFFSET " . (int)$offset;
        }

        $start_time = microtime(true);

        $result = $this->query($query);

        $end_time = microtime(true);
        $end_time -= $start_time;
        self::$time_queries += $end_time;
        self::$num_queries++;
        array_push(self::$log_queries, $query);
        array_push(self::$tlog_queries, $end_time);

        return $result;
    }
    public function insert($table, $values)
    {
        $query = "INSERT INTO $table ";
        $query .= "(`" . implode("`,`", array_keys($values)) . "`)";
        $query .= " VALUES ('" . implode("','", array_map(array($this, "quote"), array_values($values))) . "')";
        $this->query($query);
        $this->_affected_rows = 0;
        return $this->getLastInsertId();
    }
    public function update($table, $values, $condition = null)
    {
        $query = "UPDATE $table SET ";
        $query .= $this->joinValues($values, ", ");
        if ($condition)
            $query .= " WHERE " . $this->joinValues($condition, " AND ");

        $this->query($query);
        $this->_affected_rows = 0;
    }
    public function delete($table, $condition = null)
    {
        $query = "DELETE FROM $table";
        if ($condition)
            $query .= " WHERE " . $this->joinValues($condition, " AND ");
        $this->query($query);
        $this->_affected_rows = 0;
    }
}
?>
