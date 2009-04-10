<?php
abstract class View_Http extends View implements View_Exceptionable
{
	protected $_filename = "";

    protected $_error_code = null;
    protected $_error_mesg = null;
    protected $_headers = array();
    protected $_redir_to = array();

	protected $_templates_path;

    /**
     * View_Html constructor
     * @param string template filename
     * @param array view's data
     * @return View_Html
     * @author kstep
     */
    public function __construct($filename = "", $data = null)
    {
        if ($filename) $this->setTemplate($filename);
        if ($data) $this->setData($data);
        //$this->_prefix = $prefix;
    }

    public function setTemplate($filename)
    {
        if (!file_exists($filename))
            $filename = "{$this->_templates_path}/$filename";
        if (!file_exists($filename))
            throw new View_Exception("Template file not found: $filename.");
        
        $this->_filename = $filename;
    }

	protected function modified(Model_Timestamped $model, $lifetime = 1800)
	{
		$modified = $model->modified;
		$expires  = $modified + $lifetime;
		//$etag = md5(serialize($model));
		header('Last-Modified: '.date('D, d M Y H:i:s', $modified).' GMT');
		header('Expires: '.date('D, d M Y H:i:s', $expires).' GMT');
		//header('ETag: "'.$etag.'"');
		header('Cache-Control: max-age='.$lifetime.', must-revalidate');
		/*if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
		{
			$ifmodified = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
			if ($ifmodified !== false && $modified <= $ifmodified)
			{
				header('HTTP/1.1 304 Not Modified');
				exit();
			}
		}*/
	}

	protected function sendHeaders()
	{
        if ($this->_error_code)
        {
            header("HTTP/1.0 {$this->_error_code} {$this->_error_mesg}");
        }
		foreach ($this->_headers as $header)
			header($header);
        if ($this->_redir_to)
        {
            header("Location: ".$this->pathTo($this->_redir_to[0], $this->_redir_to[1], $this->_redir_to[2], $this->_redir_to[3]));
			return false;
		}
		return true;
	}

    public function render()
    {
		if ($this->sendHeaders())
        {
            if ($this->_filename)
                require($this->_filename);
        }
    }

    public function parse()
    {
        ob_start();
        $this->render();
        return ob_get_flush();
    }

    /**
     * marks page as "access denied" page
     * @param string realm
     * @return void
     * @author kstep
     */
    public function deny($realm)
    {
        $this->_error_code = 401;
        $this->_error_mesg = "Unauthorized";
        $this->_headers = array(
            "WWW-Authenticate: Basic realm=\"$realm\"",
        );
    }

    public function notfound()
    {
        $this->_error_code = 404;
        $this->_error_mesg  = "Not found";
    }

    /**
     * marks this view to redirect to specific controller & action
     * @param string controller
     * @param string action
     * @param mixed params
     * @return void 
     * @author kstep
     */
    public function redir($controller, $action = "default", $params = array(), $qstring = null)
    {
        $this->_error_code = 302;
        $this->_error_mesg = "Found";
        $this->_redir_to = array( $controller, $action, $params, $qstring );
    }

    public function isError()
    {
        return (bool)$this->_error_code;
    }

    public function setError($code, $message)
    {
        $this->_error_code = (int)$code;
        $this->_error_mesg = $message;
    }

    /**
     * really run redirect
     * @param string path to redirect
     * @return void
     * @author kstep
     */
    private function _redir($path)
    {
        header("HTTP/1.0 302 Found");
        header("Location: $path");
    }

    /**
     * really returns authenticate headers
     * @param string realm
     * @return void 
     * @author kstep
     */
    private function _deny($realm)
    {
        header("HTTP/1.0 401 Unauthorized");
        header("WWW-Authenticate: Basic realm=\"$realm\"");
    }

    /**
     * build path parameters
     * @param array of param name => param value
     * @return string
     * @author kstep
     */
    protected function pathParams($params)
    {
        $result = "";
        if (is_array($params))
        {
            foreach ($params as $name => $value)
            {
                if (!is_int($name))
                    $result .= "/$name";
                $result .= "/$value";
            }
        } else {
            $result = "/$params";
        }
        return $result;
    }

    /**
     * builds path to specific controller & action
     * @param string controller
     * @param string action
     * @param mixed parameters
     * @return string 
     * @author kstep
     */
    public function pathTo($controller, $action = "", array $params = array(), $qstring = null)
    {
        $path = $this->findPath($controller, $action, $params);
		if ($qstring !== null)
		{
			if (is_array($qstring))
				$qstring = '?' . implode('&', array_map(create_function('$a,$b', 'return urlencode($a)."=".urlencode($b);'), array_keys($qstring), array_values($qstring)));
			else
				if (substr($qstring, 0, 1) != '?') $qstring = '?'.$qstring;
		}

        if ($path)
        {
            $vars = array();
            if (is_numeric($path['controller'])) $vars[$path['controller']] = $this->cleanController($controller);
            if (is_numeric($path['action'])) $vars[$path['action']] = $action;
            foreach ($params as $key => $value) $vars[$path['params'][$key]] = $value;
            $result = vsprintf($path['build'], $vars);
        }
        else
        {
            $controller = $this->cleanController(str_replace("Default", "", $controller));
            if ($action == "default")
                $action = "";
            elseif ($action && $controller)
                $action = "_$action";
            $result = str_replace("_", "/", strtolower($controller . $action)) . $this->pathParams($params);
        }
        return $this->_prefix . '/' . $result . $qstring;
    }
}
?>
