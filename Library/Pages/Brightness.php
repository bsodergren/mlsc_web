<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Pages;

use MLSC\Core\MLSC;
use MLSC\Bundle\Template\HTMLUtils;

class Brightness extends Pages
{
    public static function run()
    {
        $index_html = HTMLUtils::LedBrightness(MLSC::getDeviceBrightness());
        self::Render(['LED_RANGE_HTML' => $index_html, 'RETURN_URL' => 'brightness.php', 'PROCESS_ACTION' => 'brightness']);
    }
}
