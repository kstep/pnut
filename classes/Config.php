<?php
/**
 * @package Core
 * Configurator
 */
class Config implements Serializable, ArrayAccess, IteratorAggregate
{
    /**
     * @var Config instantinated config object
     * @author kstep
     */
    static private $_instance;

    /**
     * @var array loaded config data by section:
     * array( "section" => array( ... ) )
     * @author kstep
     */
    private $_config = array();

    /**
     * @var array instatinated storage
     * @author kstep
     */
    private $_storages = array();

    final private function __clone() { ; }

    public function load(array $config, $section = "main", $overwrite = false)
    {
        $section = (string)$section;
        if (!$overwrite && $this->_config[$section])
            $config = array_merge($config, $this->_config[$section]);

        $this->_config[$section] = $config;
        return $config;
    }

    /**
     * load config from file
     * @param string filename
     * @param string section to load config to from file
     * @param bool if true, loaded data overwrite existing
     * config data in correspondent section, otherwise
     * loaded data will be merged with already existing
     * in the section (if any exist)
     * @return array just loaded config data
     * @author kstep
     */
    public function loadXML($filename, $section = "main", $overwrite = false, $akey = "key")
    {
        return $this->load($this->xml2array(new SimpleXMLElement($filename, null, true), $akey), $section, $overwrite);
    }

    public function loadDb($store, $section = "main", $overwrite = false)
    {
        if (!$store instanceof Storage_Db) $store = $this->getStorage($store);
        $result = $store->select("config, config as section", "config.name, config.value", "section.section_id = 0 and config.section_id = section.id and section.name = '$main'");
        return $this->load($result->fetchAll("name"), $section, $overwrite);
    }

    /**
     * convert SimpleXMLElement object into clean nested array (recursively)
     * @param SimpleXMLElement or array object (or array of objects) to convert
     * @return array converted array with config data
     * @author kstep
     */
    protected function xml2array($xml, $akey = "key")
    {
        $result = (array)$xml;
	if (empty($result)) return "";
        foreach ($result as $key => $value)
        {
            if ($key === 'comment')
            {
                unset($result[$key]);
            }
            else
            {
                if (is_array($value) or $value instanceof SimpleXMLElement)
                {
                    $result[$key] = $this->xml2array($value, $akey);
                    if ($result[$key] and ($ikey = $result[$key]['@attributes'][$akey]))
                    {
                        $result[$ikey] = $result[$key];
                        unset($result[$key]);
                    }
                }
            }
        }
        return $result;
    }

    public function __get($name)
    {
        return $this->_config[(string)$name];
    }

    /**
     * Singleton implementation: get instance method:
     * use it to get an instance of Config object, not "new Config"
     * statement!
     * @return Config instantinated Config object
     * @author kstep
     */
    static public function getInstance()
    {
        if (!self::$_instance)
        {
            $class = __CLASS__;
            self::$_instance = new $class ();
        }
        return self::$_instance;
    }

    /**
     * returns storage (instantiate it if not yet).
     * @param string config section to take storage config from.
     * @return Storage
     * @author kstep
     */
    public function getStorage($section = "storage")
    {
        $section = (string)$section;
        if (!$this->_storages[$section] && $this->_config[$section])
        {
            $this->_storages[$section] = Storage::create($this->_config[$section]);
        }
        return $this->_storages[$section];
    }

    public function serialize()
    {
        return serialize($this->_config);
    }

    public function unserialize($serialized)
    {
        $this->_config = unserialize($serialized);
        self::$_instance = $this;
    }

	public function offsetGet($offset)
	{
		return $this->_config[(string)$offset];
	}
	public function offsetSet($offset, $value)
	{
		$this->load($value, $offset, true);
	}
	public function offsetUnset($offset)
	{
		unset($this->_config[(string)$offset]);
	}
	public function offsetExists($offset)
	{
		return isset($this->_config[(string)$offset]);
	}

	public function getIterator()
	{
		return ArrayIterator($this->_config);
	}

}
?>
