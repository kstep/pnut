<?php
setlocale(LC_ALL, "ru_RU.UTF-8");

//require_once('/usr/share/php/FirePHPCore/fb.php');

define('APP_PATH'        , dirname(__FILE__));
define('CLASSES_PATH'    , APP_PATH . "/classes");
define('CONTROLLERS_PATH', APP_PATH . "/controllers");
//define('VIEWS_PATH'      , APP_PATH . "/views");
define('VIEWS_PATH'      , APP_PATH . "/templates");
define('MODELS_PATH'     , APP_PATH . "/models");
define('CONFIGS_PATH'    , APP_PATH . "/configs");
define('CACHE_PATH'      , APP_PATH . "/cache");
define('ATTACHMENTS_PATH', APP_PATH . "/attachments");
define('LOCALE_PATH'     , APP_PATH . "/locale");

bindtextdomain('pnut', LOCALE_PATH);
textdomain('pnut');

function __autoload($className)
{
    $classPath = "/" . str_replace("_", "/", $className) . ".php";
    if (file_exists(CLASSES_PATH.$classPath))
    {
        require_once(CLASSES_PATH.$classPath);
    }
    else
    {
        switch (strpos($className, "_"))
        {
        case 10: // Controller_
            require_once(CONTROLLERS_PATH.substr($classPath, 11));
            break;
        case 4: // View_
            require_once(VIEWS_PATH.substr($classPath, 5));
            break;
        case 5: // Model_
            require_once(MODELS_PATH.substr($classPath, 6));
            break;
        }
    }
}
?>
