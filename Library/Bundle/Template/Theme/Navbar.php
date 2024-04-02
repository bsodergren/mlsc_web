<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Bundle\Template\Theme;

use MLSC\Core\Effects;
use MLSC\Bundle\Template\Template;
use MLSC\Bundle\Template\HTMLDocument;

class Navbar extends HTMLDocument
{
    // public static

    public static function display($template = '', $params = [])
    {
        $doc = new HTMLDocument();

        return Template::GetHTML('base/navbar/navbar', $params);
    }

}
