<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Pages;

use MLSC\Core\Effects;
use MLSC\Bundle\Template\Template;

class Activate extends Pages
{
    public static $EffectIcons = ['effect_single' => 'fa-droplet',
'effect_gradient'                                 => 'fa-rainbow',
'effect_fade'                                     => 'fa-braille',
'effect_sync_fade'                                => 'fa-rotate',
'effect_slide'                                    => 'fa-sliders',
'effect_bubble'                                   => 'fa-circle',
'effect_twinkle'                                  => 'fa-star',
'effect_pendulum'                                 => 'fa-arrows-left-right',
'effect_rods'                                     => 'fa-grip',
'effect_segment_color'                            => 'fa-ruler-combined',
'effect_fireplace'                                => 'fa-fire',
'effect_strobe'                                   => 'fa-bolt',
'effect_scroll'                                   => 'fa-angle-right',
'effect_advanced_scroll'                          => 'fa-angles-right',
'effect_energy'                                   => 'fa-bolt-lightning',
'effect_wavelength'                               => 'fa-chart-area',
'effect_bars'                                     => 'fa-bars-staggered',
'effect_power'                                    => 'fa-rocket',
'effect_beat'                                     => 'fa-headphones',
'effect_beat_twinkle'                             => 'fa-star-half-stroke',
'effect_beat_slide'                               => 'fa-heart-pulse',
'effect_wave'                                     => 'fa-rss',
'effect_wiggle'                                   => 'fa-wave-square',
'effect_vu_meter'                                 => 'fa-volume-high',
'effect_spectrum_analyzer'                        => 'fa-chart-bar',
'effect_direction_changer'                        => 'fa-signs-post',
'effect_border'                                   => 'fa-border-top-left'];

    public static function getEffectListing()
    {
        $current          = Effects::getActiveEffect();

        $effect_array     = Effects::getEffects();
        $effect_list_html =  '';

        krsort($effect_array);
        foreach ($effect_array as $group => $array)
        {
            $item_list = '';
            $skip      = true;
            if ('non_music' == $group)
            {
                $group_name = 'Non Music Effects';
                $group_id   = 'dashboard-list-none-music';
                $skip       = false;
            }
            if ('music' == $group)
            {
                $group_name = 'Music Effects';
                $group_id   = 'dashboard-list-music';
                $skip       = false;
            }
            if (false === $skip)
            {
                //                ksort($array);
                foreach ($array as $effect_id => $effect_name)
                {
                    $isActive = '';
                    if ($effect_id == $current)
                    {
                        $isActive = 'dashboard_effect_active';
                    }
                    $item_list .= Template::GetHTML(self::$TemplateRoot.'/effects/button', ['EFFECT_NAME' => $effect_name,
                    'EFFECT_ID'                                                                           => $effect_id, 'EFFECT_ICON' => self::$EffectIcons[$effect_id], 'ACTIVE' => $isActive]);
                }

                $effect_list_html .= Template::GetHTML(self::$TemplateRoot.'/effects/group', ['GROUP_NAME' =>  $group_name, 'GROUP_ID' =>  $group_id,  'EFFECT_BUTTONS' => $item_list]);
            }
        }
        // $effect_html = Template::GetHTML(self::$TemplateRoot . '/effect_menu_item', [
        //     'EFFECT_ACTIVE_STYLE' =>  $effect_active_style,
        //     'EFFECT_ACTIVE_CLASS' =>  $effect_active_class,
        //     'EFFECT_HTML_LIST' => $effect_list_html
        // ]);

        return $effect_list_html;
    }
}
