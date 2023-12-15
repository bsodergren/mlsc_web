<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Bundle\Template\Theme;

use MLSC\Bundle\Template\Template;
use MLSC\Bundle\Template\HTMLDocument;

class Footer extends HTMLDocument
{
    // public $html;

    public static function display($template = '', $params = [])
    {
        echo Template::GetHTML('base/footer/footer', $params);
    }
}
