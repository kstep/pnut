<?php
/**
 * @package Core
 * @subpackage View
 * Base abstract view
 */
abstract class View
{
    /**
     * View's data to fill in real view
     * @var array
     * @author kstep
     */
    protected $_data;

    /**
     * @var string site's prefix to be used for URL's
     * construction or something (depending of real View's nature)
     * @author kstep
     */
    protected $_prefix = "";

    protected $_routes = array();
	protected $_realm = "";

    /**
     * this method must build real view (based on some template
     * in necessary) using data set in the view
     * @return void
     * @author kstep
     */
    abstract public function render();

    /**
     * basicly this method must act just like render(),
     * but istead of running view immediately, it must
     * return some object (e.g. HTML string) representing
     * the view in some raw format to be used later (e.g. it
     * should be serializable to be saved in to cache).
     * @return mixed serializable data
     * @author kstep
     */
    abstract public function parse();

    /**
     * sets application's prefix to be used for paths building
     * (URL's in case of Web-application).
     * @param string prefix
     * @return void
     * @author kstep
     */
    public function setSite(array $site)
    {
        $prefix = (string)$site['prefix'];
		$realm  = (string)$site['realm'];
        if ($prefix && substr($prefix, 0, 1) != "/") $prefix = "/$prefix";
        $this->_prefix = $prefix;
		$this->_realm  = $site['realm'];
		$this->_routes = array();
		foreach ($site['route'] as $route) 
			if ($route['build'])
				$this->_routes[$route['@attributes']['controller']][$route['@attributes']['action']][] = $route;
    }

	public function cleanController($controller)
	{
		if ($this->_realm && substr($controller, 0, $realmlen = strlen($this->_realm)) == $this->_realm)
			$controller = substr($controller, $realmlen + 1);
		return str_replace("_", "/", strtolower($controller));
	}

    public function findPath($controller, $action, array $params = array())
    {
        $path = array();
		if ($this->_realm)
		{
			$realmlen = strlen($this->_realm);
			if (substr($controller, 0, $realmlen) == $this->_realm)
				$controller = substr($controller, $realmlen + 1);
		}
		if (($route = $this->_routes[$controller][$action])
			or ($route = $this->_routes[$controller][''])
			or ($route = $this->_routes[''][$action])
			or ($route = $this->_routes['']['']))
		{
			foreach ($route as $item)
			{
				if (!$item['params'] || (count($item['params']) == count($params) && count(array_intersect_key($item['params'], $params)) == count($item['params'])))
				{
					$path = $item;
					break;
				}
			}
		}
        return $path;
    }

    public function setData(array $data)
    {
        $this->_data = $data;
    }

    public function __get($name)
    {
        return $this->_data[$name];
    }
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

}
?>
