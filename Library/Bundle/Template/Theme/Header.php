<?php
/**
 * Bsodergren\utm Media tool for load flags
 */

namespace MLSC\Bundle\Template\Theme;

use MLSC\Bundle\Template\Template;
use MLSC\Bundle\Template\HTMLDocument;

class Header extends HTMLDocument
{
    public static function display($template = '', $params = [])
    {
        $params['__MSG__']                             = self::displayMsg();

        echo Template::GetHTML('base/header/header', $params);
    }
}
