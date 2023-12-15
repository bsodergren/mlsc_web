<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Pages;

use MLSC\Bundle\Template\Template;
use MLSC\Bundle\Template\Utils\HTMLDevice;

class Pages
{
    public static $TemplateRoot = '';

    public static function Render($params = [])
    {
        HTMLDevice::getHeader();
        $index_html = Template::GetHTML(self::$TemplateRoot.'/body', $params);
        Template::echo('base/main', ['PAGE_HTML' => $index_html]);
        HTMLDevice::getFooter();
    }
}
