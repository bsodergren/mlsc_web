<?php
/**
 *
 * MLS Script
 *
 */

use MLSC\Pages\Brightness;

define('__HTTP_DOCROOT__', $_SERVER['DOCUMENT_ROOT']);
require_once __HTTP_DOCROOT__.\DIRECTORY_SEPARATOR.'.config.php';
Brightness::$TemplateRoot = 'pages/brightness';

Brightness::run();

// echo "stuff";
