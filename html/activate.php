<?php

use MLSC\Pages\Activate;
use MLSC\Bundle\Template\Template;
use MLSC\Bundle\Template\HTMLUtils;
use MLSC\Bundle\Template\Theme\Navbar;
use MLSC\Bundle\Template\Utils\HTMLDevice;

define('__HTTP_DOCROOT__', $_SERVER['DOCUMENT_ROOT']);
require_once __HTTP_DOCROOT__ . DIRECTORY_SEPARATOR . ".config.php";


//Activate::$TemplateRoot = 'pages/active';

$index_html = $Class::getEffectListing();

 $Class::Render(['BODY' => $index_html]);



//echo "stuff";
