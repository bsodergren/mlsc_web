<?php

use MLSC\Bundle\Template\Template;
use MLSC\Bundle\Template\Utils\HTMLDevice;
use MLSC\Controller\Processor;

define('__HOME__', '/home/bjorn');
define('__ROOT_DIRECTORY__', __HOME__ . '/www/mlsc_web');
define('__PROJECT_ROOT__', __ROOT_DIRECTORY__);
define('__COMPOSER_LIB__', __ROOT_DIRECTORY__ . '/vendor');
define('__CONFIG_LIB__', __ROOT_DIRECTORY__ . '/config');
define('__ASSETS_DIR__', __HTTP_DOCROOT__ . DIRECTORY_SEPARATOR . "assets");
define('__TEMPLATE_DIR__', __ASSETS_DIR__ . DIRECTORY_SEPARATOR . "template");
define('__DEFAULT_EFFECT_DIR__',__CONFIG_LIB__ . '/effects');
define('MLSC_URL', "http://lights.local");

require_once __COMPOSER_LIB__ . '/autoload.php';
require_once __CONFIG_LIB__ . '/constants.php';
DEFINE("PHP_SELF", basename($_SERVER['PHP_SELF']));

DEFINE("__SCRIPT_NAME__", basename($_SERVER['PHP_SELF'], ".php"));


if (!defined('ERROR_LOG_FILE')) {
    DEFINE('ERROR_LOG_FILE', __HOME__ . "/logs/" . __SCRIPT_NAME__ . ".log");
}

$Process = new Processor($_REQUEST);

HTMLDevice::$template_dir = __TEMPLATE_DIR__;
$template = new Template(__TEMPLATE_DIR__);


$Class = "MLSC\\Pages\\" . ucfirst(__SCRIPT_NAME__);
if(class_exists($Class)) {
    $Class::$TemplateRoot = 'pages/' . __SCRIPT_NAME__;
}
