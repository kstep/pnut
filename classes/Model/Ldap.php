<?php
require_once(CLASSES_PATH.'/Model.php');
abstract class Model_Ldap extends Model
{
    protected $_pk = "uid";
    protected $_dn;

    public function __construct(Storage_Ldap $db, $id = null)
    {
        parent::__construct($db, $id);
    }

    protected function loadFields()
    {
        return $this->_db->getFieldsList();
    }

    public function getPk()
    {
        return $this->_pk;
    }

    public function getDN()
    {
        return $this->_dn;
    }

    public function save() // @todo
    {
        if ($this->_id !== null)
            $this->_db->update($this->_values, array( $this->_pk => $this->_id ));
        else
            $this->_id = $this->_db->insert($this->_values);
    }
    public function remove() // @todo
    {
        if ($this->id !== null)
        {
            $this->_db->delete(array( $this->_pk => $this->_id ));
            $this->_id = 0;
        }
    }

    public function load($id)
    {
        if (!is_array($id)) $id = array( $this->_pk => $id );
        $values = $this->_db->select(array_keys($this->_fields), $id)->fetchFirst();
        $this->_dn = $this->_values["dn"];

        $this->_values = array();
        $fields = array_flip($this->_attributes);
        for ($i = 0; $i < $values['count']; $i++)
        {
            $attr = $values[$i];
            if ($values[$attr]['count'] > 0)
            {
                if ($values[$attr]['count'] == 1)
                    $this->_values[$fields[$attr]] = $values[$attr][0];
                else
                {
                    unset($values[$attr]['count']);
                    $this->_values[$fields[$attr]] = $values[$attr];
                }
            }
        }

        $this->_id = $this->_values[$this->_pk];
    }

}
?>
