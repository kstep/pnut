<?php
abstract class Model_Db extends Model
{
    protected $_table;
	protected $_view;
	protected $_views;
	protected static $_visibility;

    protected $_pk = "id";

	public static function setVisibility($value = "visible")
	{
		self::$_visibility = $value;
	}

    public function __construct(Storage_Db $db, $id = null)
    {
		$this->_view = self::$_visibility && isset($this->_views[self::$_visibility])? $this->_views[self::$_visibility]: $this->_table;
        parent::__construct($db, $id);
    }

    protected function loadFields()
    {
        return $this->_db->getFieldsList($this->_table);
    }

    public function getTable()
    {
        return $this->_table;
    }

    public function getPk()
    {
        return $this->_pk;
    }

    public function save()
    {
        $values = $this->unparseData();
        if ($this->_id !== null)
		{
            $this->_db->update($this->_table, $values, is_array($this->_pk)? array_combine($this->_pk, $this->_id): array( $this->_pk => $this->_id ));
		}
        else
		{
            $this->_id = $this->_db->insert($this->_table, $values);
			if (is_array($this->_pk))
			{
				$this->_id = array();
				foreach ($this->_pk as $pk) $this->_id[$pk] = $values[$pk];
			}
		}
    }
    public function remove()
    {
        if ($this->_id !== null)
        {
            $this->_db->delete($this->_table, array( $this->_pk => $this->_id ));
            $this->_id = null;
        }
    }

    public function load($id)
    {
        if (!is_array($id))
            $id = array( $this->_pk => (int)$id );
        $values = $this->_db->select($this->_view, array_keys($this->_fields), $id)->fetchFirst();
        if ($values)
        {
            $this->parseData($values);
            return true;
        }
        return false;
    }

}
?>
