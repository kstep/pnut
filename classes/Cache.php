<?php
/**
 * @package Core
 */
class Cache
{
    static private $_instances = array();
    static public $buffer_size = 2048;

    private $_filename = "";
    private $_fh = null;
	private $_filemtime = null;
    public $timeout = 1800;

    /**
     * construct Cache object.
     * @param string key
     * @return Cache
     */
    private function __construct($key)
    {
        $this->_filename = self::getFile($key);
		$this->_filemtime = @filemtime($this->_filename);
    }

    /**
     * destruct Cache object: flush all caches and close file handlers.
     * @return void
     */
    public function __destruct()
    {
        $this->stop();
        if ($this->_fh) fclose($this->_fh);
    }

    /**
     * we are singleton, no cloning allowed.
     */
    private function __clone() {}

    /**
     * Singleton implementation: get instance by cache key.
     * @param string key
     * @return Cache
     */
    public static function getInstance($key)
    {
        if (!self::$_instances[$key])
            self::$_instances[$key] = new Cache($key);

        return self::$_instances[$key];
    }

    /**
     * start caching via output buffer.
     * @return void
     */
    public function start()
    {
        $this->_fh = fopen($this->_filename, "wb");
        ob_start(array($this, "append"), self::$buffer_size);
    }

    /**
     * stop caching, flush all buffers.
     * @return void
     */
    public function stop()
    {
        while (@ob_end_flush()){}
    }

    /**
     * output buffer callback.
     * @param string buffer
     * @param integer flags
     * @return mixed string|bool
     * @see ob_start
     */
    public function append($buffer, $flags)
    {
        if ($this->_fh)
        {
            fwrite($this->_fh, $buffer);
        }
        return false;
    }

    /**
     * try to read cache from file.
     * @param integer timeout in seconds
     * @return bool
     */
    public function hit()
    {
        if (!$_REQUEST['nocache'] && !$_POST && $this->_filemtime)
        {
            if (($size = filesize($this->_filename)) > 0
                and (time() - $this->_filemtime) < $this->timeout)
            {
                header('Content-Type: '.mime_content_type($this->_filename));
                header('Content-Length: '.$size);
				header('Last-Modified: '.date('D, d M Y H:i:s', $this->_filemtime).' GMT');
				header('Expires: '.date('D, d M Y H:i:s', $this->_filemtime + $this->timeout).' GMT');
				header('Cache-Control: max-age='.$this->timeout.', must-revalidate');
				//header('ETag: "'.md5_file($this->_filename).'"');
				if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
				{
					$ifmodified = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
					if ($ifmodified !== false && $modified <= $ifmodified)
					{
						header('HTTP/1.0 304 Not Modified');
						return true;
					}
				}
                readfile($this->_filename);
                return true;
            }
        }
        return false;
    }

	public function __invoke()
	{
		$this->hit();
	}

    /**
     * get filename to store cached data.
     * @return string
     */
    public function getCacheFile()
    {
        return $this->_filename;
    }

    /**
     * generate cache file from key.
     * @return string
     */
    static public function getFile($key)
    {
       return CACHE_PATH.'/_'.str_replace('/', '_', trim($key, '/'));
    }

    /**
     * read cached data by key.
     * @param string key
     * @return Object
     */
    static public function read($key, $min_time = 0)
    {
        return self::getInstance($key)->get($min_time);
    }

    /**
     * write cached data by key.
     * @param string key
     * @param Object
     * @return bool
     */
    static public function write($key, $object)
    {
        return self::getInstance($key)->put($object);
    }

    /**
     * make sure we can use this cache file.
     */
    public function cachable()
    {
        //if ($this->timeout < 1 or $_POST or $_SERVER['PHP_AUTH_USER'] or $_REQUEST['ajax']) return false;
        if ($this->timeout < 1 or $_REQUEST['ajax']) return false;
        return !file_exists($this->_filename) or is_writable($this->_filename);
    }

    /**
     * put data into current cache storage.
     * @param Object
     * @return bool
     */
    public function put($object)
    {
        if ($this->cachable() && $object instanceof Serializable)
        {
            $fh = fopen($this->_filename, "wb");
            if ($fh)
            {
                fwrite($fh, serialize($object));
                fclose($fh);
                return true;
            }
        }
        return false;
    }

    /**
     * get object from cache.
     * @return Object
     */
    public function get($min_time = 0)
    {
        if (file_exists($this->_filename))
        {
            if (!$min_time || filemtime($this->_filename) >= $min_time)
            {
                $fh = fopen($this->_filename, "rb");
                if ($fh)
                {
                    $data = "";
                    while (!feof($fh))
                    {
                        $data .= fread($fh, 2048);
                    }
                    fclose($fh);
                    return unserialize($data);
                }
            }
        }
        return false;
    }

    /**
     * utility method: clear cache, i.e. remove all files from cache dir.
     * @return void
     */
    static public function clear()
    {
        $dir = opendir(CACHE_PATH);
        if (!$dir) return;

        while (($file = readdir($dir)) !== false)
        {
            if (!is_dir($file)) @unlink($file);
        }
        closedir($dir);
    }
}
?>
