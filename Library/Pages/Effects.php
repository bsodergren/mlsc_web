<?php

namespace MLSC\Pages;

use MLSC\Pages\Pages;
use MLSC\Bundle\Template\Template;
use MLSC\Core\Effects as CoreEffects;

class Effects extends Pages
{
    public static function run()
    {

        $effect = '';
        if (array_key_exists('effect', $_REQUEST)) {
            $effect = $_REQUEST['effect'];
        }

        $effect_settings = \MLSC\Core\Effects::getDeviceEffect(1, $effect);
        $params = $effect_settings['settings'];
        $params['PROCESS_URL'] = $_SERVER['PHP_SELF'];
        $params['PROCESS_ACTION'] = 'effect';
        $params['RETURN_URL'] = $_SERVER['REQUEST_URI'];

        //dd($_SERVER);

        // $effect_html = HTMLUtils::effectSettings($effect_settings);//(1, $effect);

        $effect_html = Template::GetHTML(self::$TemplateRoot ."/mlsc/" . $effect, $params);
        self::Render(['BODY' => $effect_html]);

    }
}
