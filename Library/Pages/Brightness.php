<?php

namespace MLSC\Pages;

use MLSC\Pages\Pages;
use MLSC\Bundle\Template\Template;
use MLSC\Bundle\Template\HTMLUtils;

class Brightness extends Pages
{


    public static function run()
    {

        $index_html = HTMLUtils::LedBrightness(getDeviceBrightness());
        self::Render(['LED_RANGE_HTML' => $index_html,'RETURN_URL' => "brightness.php", 'PROCESS_ACTION' => 'brightness']);

    }


}
