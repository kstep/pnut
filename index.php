<?php
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

//$dispatcher = new Dispatcher_Cached($routes["route"], $routes["prefix"]);
$dispatcher = new Dispatcher($routes["route"], $routes["prefix"]);
$dispatcher->run();

$end_time = microtime(true);
//include("debug.php");
?>
