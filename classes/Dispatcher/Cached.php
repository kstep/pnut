<?php
class Dispatcher_Cached extends Dispatcher
{
    /**
     * @var Cache
     */
    protected $_cache = null;

    public function run($path = null)
    {
        if ($path === null)
            $path = $_SERVER["PATH_INFO"];

        $route = $this->findRoute($path);
        if ($route)
        {
            $this->_cache = Cache::getInstance($path);
            $this->_cache->timeout = (int)$route['@attributes']['cache'];
            if (($cachable = $this->_cache->cachable()) and $this->_cache->hit()) return;

            $class = $this->loadController($route["controller"], $route['site']['realm']);
            if (!$class)
                throw new Dispatcher_Exception("Unable to load class $class for controller.");

            try {
                $controller = new $class ();
                $view = $controller->run($route["action"]? $route["action"]: "default", $route["params"]);
            } catch (Controller_Exception $e) {
                $view = $e->getView();
                $cachable = false;
            }

            if ($view && $view instanceof View)
            {
                $view->setSite($route['site']);

                if ($cachable)
                {
                    $this->_cache->start();
                    $view->render();
                    $this->_cache->stop();
                }
                else
                {
                    //header("Expired: ");
                    $view->render();
                }
            }
        }
        else
        {
            throw new Dispatcher_Exception("Unable to find a route for path $path.");
        }
    }

}
?>
