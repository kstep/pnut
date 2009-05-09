<?php
function report_exception (Exception $e)
{
	function build_arg_desc($item)
	{
		$type = is_object($item)? get_class($item): gettype($item);
		if (is_string($item)) {
			if (strlen($var = $item) > 20) {
				$title .= $var;
				$var = substr($var, 0, 20)."&hellip;";
			}
			$var = "&quot;$var&quot;";
		} elseif (is_array($item)) {
			$var = "(";
			$num = 0;
			foreach ($item as $key => $value)
			{
				$var .= $key . " => " . build_arg_desc($value) . ", ";
				if (++$num > 3) { $var .= "&hellip;"; break; }
			}
			$var = trim($var, ", ").")";
		} else {
			$var = (string)$item;
		}
		return "<var title=\"$title\"><ins>$type</ins> $var</var>";
	}
?>
<html>
<head>
	<style type="text/css">
		#exception { font-size: 12px; font-family: "Lucida Sans Unicode", Verdana, Tahoma, Arial, sans-serif; background: pink; color: black; border: 1px solid red; padding: 0; margin: 0; }
		#exception h1 { background: red; color: white; font-size: 1em; margin: 0; padding: 2px 5px; }	
		#exception p { padding: 1em 5px 0 5px; margin: 0; }
		#exception pre, #exception ol { border: 1px dashed red; margin: 1em 2em; padding: 5px; background: white; }
		#exception ol { list-style-position: inside; color: black; }
		#exception ol li strong { color: #ff8000; }
		#exception ol li em { color: #0000bb; font-weight: bold; font-style: normal; }
		#exception ol li var { color: #dd0000; }
		#exception ol li var ins { color: #007000; text-decoration: none; font-style: normal; }
	</style>
</head>
<body>
	<div id="exception">
	<h1><?=str_replace("_", " ", get_class($e))?> #<?=$e->getCode()?></h1>
	<p><?=$e->getMessage()?></p>
	<p>Happend at <strong><?=$e->getFile()?>:<?=$e->getLine()?></strong></p>
	<p>Backtrace:
		<ol start="0">
		<? foreach ($e->getTrace() as $item) { ?>
		<li><strong><?=$item['file']?>:<?=$item['line']?></strong>: <em><? if (isset($item['class'])) echo $item['class'].$item['type'] ?><?=$item['function']?></em>(<? if (isset($item['args'])) echo implode(", ", array_map('build_arg_desc', $item['args'])) ?>)</li>
		<? } ?>
		</ol>
	</p>
	</div>
</body>
</html>
<?
}


$start_time = microtime(true);
session_start();

//error_reporting(E_ALL);
require_once("compat.php");
require_once("loader.php");

$config_cache = Cache::getInstance("cached_config");
$route_config = "configs/routes.xml";
$store_config = "configs/db.xml";
$min_time = max(filemtime($route_config), filemtime($store_config));

if ($config = $config_cache->get($min_time))
{
    $routes = $config->routes;
}
else
{
    $config = Config::getInstance();
    $config->loadXML($store_config, "storage");
    $routes = $config->loadXML($route_config, "routes");
    $config_cache->put($config);
}

//$dispatcher = new Dispatcher_Cached($routes["site"]);
$dispatcher = new Dispatcher($routes['site']);
try
{
	$dispatcher->run();
} catch (Exception $e) {
	report_exception($e);
}

$end_time = microtime(true);
//include("debug.php");
?>
