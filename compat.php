<?php
if (!function_exists('mime_content_type'))
{
	if (function_exists('finfo_open'))
	{
		function mime_content_type($filename)
		{
			$finfo = finfo_open(FILEINFO_MIME);
			$mime  = finfo_file($finfo, $filename);
			finfo_close($finfo);
			return $mime;
		}
	}
	else
	{
		function mime_content_type($filename)
		{
			$info = getimagesize($filename);
			if ($info)
				return $info['mime'];
			else
			{
				$fh = fopen($filename);
				if ($fh)
				{
					$buffer = fread($fh, 512);
					fclose($fh);
					$buffer = strtolower($buffer);
					if (strpos($buffer, "<html") !== false)
						return 'text/html';
					elseif (strpos($buffer, "<?xml") !== false)
						return 'text/xml';
					elseif (substr($buffer, 6, 4) == "jfif")
						return 'image/jpeg';
					elseif (strpos($buffer, 1, 3) == "png")
						return 'image/png';
					elseif (substr($buffer, 0, 5) == "gif89")
						return 'image/gif';
					elseif (substr($buffer, 0, 3) == "flv")
						return 'video/x-flv';
				}
				return 'text/plain';
			}
		}
	}
}
?>
