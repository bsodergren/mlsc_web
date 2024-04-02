<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Bundle\Template\Helper;

use MLSC\Core\MLSC;
use MLSC\Core\Device;
use MLSC\Core\Effects;
use MLSC\Bundle\Template\Template;

class Helper
{
    public function __construct()
    {
    }

    private static function getArgs($array)
    {
        if (\array_key_exists(2, $array))
        {
            $args = $array[2];
            if($args != '')
            {
                switch ($args[0])
                {
                    case "!":
                        //not
                        break;

                }
                //dd($args[0]);
            }
        }

    }
    public function __call($method,$args)
    {
        return Template::GetHTML('error/method', ['METHOD'=>$method]);
    }

    public function breadcrumbs($matches)
    {
        $html                     = '';
        $parts                    = parse_url($_SERVER['REQUEST_URI']);

        $crumbs[]                 = ['URL' => $parts['path'], 'TEXT' => ucfirst(basename($parts['path'], '.php'))];
        if (\array_key_exists('query', $parts))
        {
            [$_,$effect_name] = explode('=', $parts['query']);

            $crumbs[]         = [
                'URL'  => '#',
                'TEXT' => ucfirst(str_replace('effect_', '', $effect_name))];
        }
        $last                     = array_key_last($crumbs);
        $crumbs[$last]['CURRENT'] = 'active';

        foreach ($crumbs as $i => $value)
        {
            $html .= Template::GetHTML('base/breadcrumb/link', $value);
        }

        return $html;
    }

    public function getNavLinks($matches)
    {
        $html     = '';
        $NavItems = MLSC::jsonToArray();
        foreach ($NavItems as $i => $value)
        {
            $html .= Template::GetHTML('base/navbar/menu_link', $value);
        }

        return $html;
    }

    public function getEffects($matches)
    {
        $current_effect = '';

        $effect_html    = '';

        if (\array_key_exists('effect', $_REQUEST))
        {
            $current_effect = $_REQUEST['effect'];
        }

        if (\array_key_exists(1, $matches))
        {
            $effect_html = self::getEffectList($current_effect);
        }

        return $effect_html;
    }

    public static function getEffectList($current_effect)
    {

        $effect_group        = 'base/navbar/effect/group';
        $effect_item         = 'base/navbar/effect/item';
        $effect_menu         = 'base/navbar/effect/menu_item';
        $effect_active_class = '';
        $effect_list_html    = '';
        $effect_active_style = '';

        $effect_array        = Effects::getEffects();
        krsort($effect_array);
        foreach ($effect_array as $group => $array)
        {
            $item_list = '';
            $skip      = true;
            if ('non_music' == $group)
            {
                $style = 'Non Music';
                $skip  = false;
            }
            if ('music' == $group)
            {
                $style = 'Music';
                $skip  = false;
            }
            if (false === $skip)
            {
                // ksort($array);
                foreach ($array as $effect_id => $effect_name)
                {
                    $class = '';
                    if ($current_effect == $effect_id)
                    {
                        $class               = ' class="active" ';
                        $effect_active_style = ' style="display: block;" ';
                        $effect_active_class =  ' pcoded-trigger ';
                    }
                    $url          = "effects.php?effect=".$effect_id ;
                    $item_list .= Template::GetHTML($effect_item, ['EFFECT_URL' => $url, 'EFFECT_NAME' => $effect_name, 'ACTIVE' => $class]);
                }
                $effect_list_html .= Template::GetHTML($effect_group, ['EFFECT_STYLE' =>  $style, 'EFFECT_ITEM_HTML' => $item_list]);
            }
        }
        $effect_html         = Template::GetHTML($effect_menu, [
            'EFFECT_ACTIVE_STYLE' => $effect_active_style,
            'EFFECT_ACTIVE_CLASS' => $effect_active_class,
            'EFFECT_HTML_LIST'    => $effect_list_html,
        ]);

        return $effect_html;
    }

    public function deviceList($matches)
    {
        $opts = self::getArgs($matches);
        $card                   = 'base/devices/card';
        $button                 = 'base/devices/button';

        $devices                = Device::getAllDevices();
        $params['DEVICE_HTML'] = '';
        $params['DEVICE_COUNT'] = \count($devices);
        foreach ($devices as $key)
        {
            $params['DEVICE_HTML'] .= Template::GetHTML($button, ['DEVICE_NAME' => $key['name']]);
        }

        return Template::GetHTML($card, $params);
    }
}
