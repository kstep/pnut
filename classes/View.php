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
    public function setRoutes(array $routes, $prefix = "")
    {
        $prefix = (string)$prefix;
        if ($prefix && substr($prefix, 0, 1) != "/") $prefix = "/$prefix";
        $this->_prefix = $prefix;
        $this->_routes = $routes;
    }

    public function findPath($controller, $action, array $params = array())
    {
        $path = array();
        foreach ($this->_routes as $route)
        {
            if (!$route['@attributes'] || !$route['build']) continue;
            $sign = $route['@attributes'];

            if (($sign['controller'] && $sign['controller'] === substr($controller, 0, strlen($sign['controller'])))
                && (!$sign['action'] || $sign['action'] === substr($action, 0, strlen($sign['action'])))
                && (!$route['params'] || (count($route['params']) == count($params) && count(array_intersect_key($route['params'], $params)) == count($route['params']))))
            {
                $path = $route;
                break;
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
