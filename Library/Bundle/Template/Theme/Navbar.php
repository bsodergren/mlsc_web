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

    public static function getEffectList($url = 'effects.php', $current_effect = '', $url_options = [['KEY' => 'effect', 'VALUE' => '{{effect_id}}']])
    {
        foreach ($url_options as $option)
        {
            $url_encode[] = $option['KEY'].'='.$option['VALUE'];
        }
        $url_string          = $url.'?'.implode('&', $url_encode);

        // "effects.php?effect="
        $effect_group        = 'base/navbar/effect_group';
        $effect_item         = 'base/navbar/effect_item';
        $effect_menu         = 'base/navbar/effect_menu_item';
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
                ksort($array);
                foreach ($array as $effect_id => $effect_name)
                {
                    $class = '';
                    if ($current_effect == $effect_id)
                    {
                        $class               = ' class="active" ';
                        $effect_active_style = ' style="display: block;" ';
                        $effect_active_class =  ' pcoded-trigger ';
                    }

                    preg_match_all('/{{([a-z_]+)}}/', $url_string, $output_array);
                    foreach ($output_array[1] as $key)
                    {
                        $url = str_replace('{{'.$key.'}}', $$key, $url_string);
                    }
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
}
