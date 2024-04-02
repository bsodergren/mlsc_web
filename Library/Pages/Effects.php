<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Pages;

use MLSC\Core\Device;
use MLSC\Bundle\Template\Template;

class Effects extends Pages
{
    private static $CustomEffect = ['effect_advanced_scroll'];

    public static function run()
    {
        $effect                   = '';
        if (\array_key_exists('effect', $_REQUEST))
        {
            $effect = $_REQUEST['effect'];
        }

        // if (\in_array($effect, self::$CustomEffect))
        // {
        //     $devices              = Device::deviceList();

        //     foreach ($devices as $device)
        //     {
        //     }
        // } else
        // {
        $effect_settings          = \MLSC\Core\Effects::getDeviceEffect(1, $effect);
        $params                   = $effect_settings['settings'];
        $params['PROCESS_URL']    = $_SERVER['PHP_SELF'];
        $params['PROCESS_ACTION'] = 'effect';
        $params['RETURN_URL']     = $_SERVER['REQUEST_URI'];

        // dd($_SERVER);

        // $effect_html = HTMLUtils::effectSettings($effect_settings);//(1, $effect);

        $effect_html              = Template::GetHTML(self::$TemplateRoot.'/mlsc/'.$effect, $params);
        self::Render(['BODY' => $effect_html]);
        // }
        // }
    }
}
