<?php
/**
 * @package Core
 * @subpackage Dispatcher
 * Base dispatcher
 */
class Dispatcher
{
    /**
     * @var array routes to check in format:
     * array(
     *     array( "match" => "perl regexp",
     *            "controller" => num,
     *            "action" => num,
     *            "params" => array( "param name" => num, ... )
     *          ),
     *         
     *     ...
     * )
     * "match", "action" & "params" are optional.
     * Routes are checked in order of appearance in this array,
     * until the one which matchs is found.
     * If "match" is present, path is checked against it, and
     * matched parts of path are considered controller name,
     * action name and param values according numbers in found route
     * definition. If no "match" is present, then path is split
     * into parts by delimiter (a slash, "/") and each part
     * is considered controller name, action name and param values
     * according numbers in found route definition.
     * So if "match" is not set for a route, it will always match,
     * and thus called "default route". Such route must be the last
     * in the list, because all routes below it will be ignored.
     * @author kstep
     */
    protected $_routes;

    /**
     * @var string site's prefix to ignore in path
     * @author kstep
     */
    protected $_prefix = "";

    /**
     * Dispatcher constructor
     * @param array routes (@see $_routes property)
     * @param string prefix
     * @return Dispatcher
     * @author kstep
     */
    public function __construct($routes = null, $prefix = "")
    {
        $this->setRoutes($routes);
        $this->_prefix = (string)$prefix;
    }

    /**
     * runs dispatcher
     * @param string site's path, if not set here, it's calculated from environment
     * @return void
     * @author kstep
     */
    public function run($path = null)
    {
        if ($path === null)
            $path = $_SERVER["PATH_INFO"];

        $route = $this->findRoute($path);
        if ($route)
        {
            $class = $this->loadController($route["controller"]);
            if (!$class)
                throw new Dispatcher_Exception("Unable to load class $class for controller.");

            try {
                $controller = new $class ();
                $view = $controller->run($route["action"]? $route["action"]: "default", $route["params"]);
            } catch (Controller_Exception $e) {
                $view = $e->getView();
            }

            if ($view && $view instanceof View)
            {
                $view->setRoutes($this->_routes, $this->_prefix);
                $view->render();
            }
        }
        else
        {
            throw new Dispatcher_Exception("Unable to find a route for path $path.");
        }
    }

    /**
     * constructs Controller class name by its name from path
     * @param string controller name
     * @return string Controller class name
     * @author kstep
     */
    public function loadController($controller)
    {
        if ($controller)
        {
            $controller = str_replace(" ", "_", ucwords(str_replace("/", " ", strtolower($controller))));
            $class = "Controller_$controller";
        }
        else
        {
            $class = "Controller_Default";
        }
        return $class;
    }

    /**
     * sets routes
     * @param array routes (@see $_routes property),
     * if not set, routing table is reset to default
     * routes set.
     * @return void
     * @author kstep
     */
    public function setRoutes($routes = null)
    {
        if ($routes === null)
        {
            $this->_routes = array(
                array( "controller" => 0, "action" => 1 ),
            );
        }
        else
        {
            $this->_routes = $routes;
        }
    }

    /**
     * adds route to routing table
     * @param array single route definition (@see $_routes property)
     * @return void
     * @author kstep
     */
    public function addRoute($route)
    {
        array_unshift($this->_routes, $route);
    }

    /**
     * parses "params" part of route definition
     * @param string path
     * @param array "params" route path to fetch
     * @return array with "param name" => "param value" pairs
     * @author kstep
     */
    protected function parseParams($path, $params)
    {
        $result = array();
        foreach ($params as $param => $position)
        {
            $result[$param] = is_numeric($position)? $path[$position]: $position;
        }
        return $result;
    }

    /**
     * prepares path for parsing, by removing leading & ending
     * path delimiters (slashes) and removing prefix if necessary
     * @param string path
     * @return string cleared path
     * @author kstep
     */
    public function cleanPath($path)
    {
        $preflen = strlen($this->_prefix);
        $path = trim($path, "/");
        if (($preflen > 0) && (substr($path, 0, $preflen) == $this->_prefix))
            $path = substr($path, $preflen + 1);
        return $path;
    }

    /**
     * main routing engine method: determines route by path
     * @param string path
     * @return array found route in format:
     * "controller" => "controller name", "action" => "action name",
     * "params" => array( "param name" => "param value", ... ).
     * @author kstep
     */
    public function findRoute($path)
    {
        $result  = array();
        $matches = null;
        $path    = $this->cleanPath($path);
        foreach ($this->_routes as $route)
        {
            //if (!$route['match'] || preg_match($route['match'], $path, $matches))
            if ($route['match'] && preg_match($route['match'], $path, $matches) || $route['@attributes']['default'])
            {
                if (!$matches) $matches = explode('/', $path);
                $result = $route;
                $result['controller'] = is_numeric($route['controller'])? $matches[$route['controller']]: $route['controller'];
                $result['action'] = is_numeric($route['action'])? $matches[$route['action']]: $route['action'];
                $result['params'] = $route['params']? $this->parseParams(&$matches, &$route['params']): array();
                break;
            }
        }

        if ($result['@attributes']['redirect'])
        {
            $view = new View_Html();
            $view->redir($result['controller'], $result['action'], $result['params'], $result['@attributes']['preserveqs'] === 'true'? $_SERVER['QUERY_STRING']: null);
            $view->setRoutes($this->_routes, $this->_prefix);
            $view->render();
            exit;
        }
        return $result;
    }

}
?>
