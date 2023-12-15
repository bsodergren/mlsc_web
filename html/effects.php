<?php
/**
 *
 * MLS Script
 *
 */

define('__HTTP_DOCROOT__', $_SERVER['DOCUMENT_ROOT']);
require_once __HTTP_DOCROOT__.\DIRECTORY_SEPARATOR.'.config.php';

// $Class = "MLSC\\Pages\\" . ucfirst(__SCRIPT_NAME__);
// $Class::$TemplateRoot = 'pages/'.__SCRIPT_NAME__;

$Class::run();
