<?php
/**
 * @package Core
 * @subpackage Model
 * Base abstract model
 */
abstract class Model implements Serializable
{
    const TYPE_INTEGER   = 0;
    const TYPE_STRING    = 1;
    const TYPE_TIMESTAMP = 2;
    const TYPE_FLOAT     = 3;
    const TYPE_BOOLEAN   = 4;
    const TYPE_ENUM      = 5;
    const TYPE_SET       = 6;

    /**
     * @var Storage storage to save to/load from model
     * @author kstep
     */
    protected $_db;

    /**
     * @var mixed id of a model, usually integer
     * @author kstep
     */
    protected $_id = null;

    /**
     * @var array attributes: property name => db field name
     */
    protected $_attributes = array();

    /**
     * @var array fields: db field name => type
     */
    protected $_fields = array();

    /**
     * @var array values: property name => value
     */
    protected $_values = array();

    /**
     * this method must load model from storage by its id into $_values
     * @param mixed model's id
     * @return void
     * @author kstep
     */
    abstract public function load($id);

    /**
     * this method must save model into storage (if $_id is not set,
     * new model (i.e. record in the storage) should be created
     * @return void
     * @author kstep
     */
    abstract public function save();

    /**
     * this method must remove this model from the storage
     * (i.e. delete record in the storage which stores
     * data of the model), if $_id is not set it should do nothing.
     * @return void
     * @author kstep
     */
    abstract public function remove();

    /**
     * this method must dynamically load lists of fields
     * for the model (i.e. list of storage's field names
     * in format "field name" => type_id).
     * @return array fields list
     * @author kstep
     */
    abstract protected function loadFields();

    /**
     * Model constructor
     * @param Storage to load model from
     * @param mixed id of a model to load on object creation
     * @return Model
     * @author kstep
     */
    public function __construct(Storage $db, $id = null)
    {
        $this->_db = $db;
        if ($id) $this->load($id);
    }

    /**
     * returns model's id
     * @return mixed model's $_id value
     * @author kstep
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * sets model's attributes
     * @param array attributes description (@see $_attributes property),
     * if not set, this list is build from fields list).
     * @return void
     * @author kstep
     */
    protected function setAttributes($attributes = null)
    {
        if ($attributes === null)
        {
            if (!$this->_attributes)
            {
                $fields = array_keys($this->_fields);
                $this->_attributes = array_combine($fields, $fields);
            }
        }
        else
        {
            $this->_attributes = $attributes;
        }
    }

    /**
     * sets model's fields
     * @param array fields description (@see $_fields property),
     * if not set, it is loaded from storage using loadFields method.
     * @return void
     * @author kstep
     */
    protected function setFields($fields = null)
    {
        if ($fields === null)
        {
            if (!$this->_fields)
                $this->_fields = $this->loadFields();
        }
        else
        {
            $this->_fields = $fields;
        }
    }

    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * get attributes list.
     * @param bool if true, array of attribute name => type is returned,
     * otherwise (false is default) array of attribute name => storage field
     * is returned
     * @return array
     */
    public function getAttributes($types = false)
    {
        $result = $this->_attributes;
        if ($types)
        {
            foreach ($result as &$value)
            {
                $value = $this->_fields[$value];
            }
        }
        return $result;
    }

	public function getStorage()
	{
		return $this->_db;
	}

    /**
     * check if model is loaded from storage
     * @return bool true if model is loaded
     * @author kstep
     */
    public function isLoaded()
    {
        return (bool)$this->_values;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes))
            return $this->_values[$name];
    }
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->_attributes))
            $this->_values[$name] = $value;
    }

    /**
     * return model's data as array
     * @return array "attribute name" => "attribute value" pairs
     * @author kstep
     */
    public function toArray()
    {
        $result = array();
        foreach ($this->_attributes as $prop => $field)
        {
            $result[$prop] = $this->_values[$prop];
        }
        return $result;
    }

    /**
     * put data into model
     * @param array of attribute name => value pairs
     */
    public function setData(array $values)
    {
        $this->_values = array_merge($this->_values, array_intersect_key($values, $this->_attributes));
    }

    /**
     * convert data, stored in model, into array suitable for storing in storage.
     * @return array
     */ 
    public function unparseData()
    {
        $result = array();
        foreach ($this->_values as $attr => $value)
        {
            $field = $this->_attributes[$attr];
            if (!$field) continue;
            switch ($this->_fields[$field])
            {
            case Model::TYPE_INTEGER:
                $value = (int)$value; break;
            case Model::TYPE_BOOLEAN:
                $value = (bool)$value; break;
            case Model::TYPE_FLOAT:
                $value = (float)$value; break;
            case Model::TYPE_TIMESTAMP:
                $value = strftime("%F %T", $value);
                break;
            case Model::TYPE_SET:
                if (is_array($value)) $value = implode(",", $value);
                break;
            case Model::TYPE_STRING:
            case Model::TYPE_ENUM:
            default:
                $value = (string)$value; break;
            }
            $result[$field] = $value;
        }
        return $result;
    }

    /**
     * parse raw data from storage and put them into model's attributes.
     * @param array values, loaded from storage
     * @return void
     */
    public function parseData(array $values)
    {
        $attrs = array_flip($this->_attributes);
        foreach ($values as $field => &$value)
        {
            $attr = $attrs[$field];
            if (!$attr) continue;
            switch ($this->_fields[$field])
            {
            case Model::TYPE_INTEGER:
                $value = (int)$value; break;
            case Model::TYPE_BOOLEAN:
                $value = (bool)$value; break;
            case Model::TYPE_FLOAT:
                $value = (float)$value; break;
            case Model::TYPE_TIMESTAMP:
                $time = strptime($value, "%F %T");
                $value = mktime($time["tm_hour"], $time["tm_min"], $time["tm_sec"], $time["tm_mon"] + 1, $time["tm_mday"], $time["tm_year"] + 1900);
                break;
            case Model::TYPE_SET:
                if (!is_array($value)) $value = explode(",", $value);
                break;
            case Model::TYPE_STRING:
            case Model::TYPE_ENUM:
            default:
                $value = (string)$value; break;
            }
            $this->_values[$attr] = $value;
        }
		if (is_array($this->_pk))
		{
			$this->_id = array();
			foreach ($this->_pk as $pk) $this->_id[$pk] = $values[$pk];
		}
		else
			$this->_id = $values[$this->_pk];
    }

    public static function create(Storage $db, $object, $id = null)
    {
        if ($db instanceof Storage_Ldap) {
            $class = "Model_Ldap_".ucfirst($object);
        } else {
            $class = "Model_".ucfirst($object);
        }
        return new $class ($db, $id);
    }

    public function serialize()
    {
        $data = $this->unparseData();
        $data[$this->_pk] = $this->_id;
        return serialize($data);
    }

    public function unserialize($serialized)
    {
        $config = Config::getInstance();
        $this->_db = $config->getStorage();
        $this->parseData(unserialize($serialized));
    }

}
?>
