<?php

use MLSC\Pages\Brightness;
use MLSC\Bundle\Template\Template;
use MLSC\Bundle\Template\HTMLUtils;
use MLSC\Bundle\Template\Utils\HTMLDevice;

define('__HTTP_DOCROOT__', $_SERVER['DOCUMENT_ROOT']);
require_once __HTTP_DOCROOT__ . DIRECTORY_SEPARATOR . ".config.php";
Brightness::$TemplateRoot = 'pages/brightness';

Brightness::run();

//echo "stuff";
