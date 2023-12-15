<?php

namespace MLSC\Bundle\Template;

use MLSC\Utilities\Strings;
use MLSC\Bundle\Template\HTMLForms;
use MLSC\Bundle\Template\HTMLDocument;
use MLSC\Bundle\Template\Utils\Utilities;
use MLSC\Bundle\Template\Utils\HTMLDevice;

class HTMLUtils extends HTMLDocument
{
    public static $select_templates = 'forms/select';

    public static function LedBrightness($array)
    {
        $params = [
            'LABEL' => 'Led Brightness',
            'NAME' => 'led_brightness',
            'VALUE' => $array['setting_value'],
        ];

        return HTMLForms::rangeSlider($params);

    }

    public static function colorSelectBox( $setting_value='')
    {
        $select_box = self::$select_templates . '/select';
        $params = HTMLForms::cgSelectBox('colors', $setting_value);
        return  Template::GetHTML($select_box, $params);
    }

    public static function gradientsSelectBox( $setting_value ='')
    {
        $select_box = self::$select_templates . '/select';
        $params = HTMLForms::cgSelectBox('gradients', $setting_value);

        return  Template::GetHTML($select_box, $params);
    }

    public static function customColorBox()
    {
        $params = [];
        return  Template::GetHTML('forms/rgb_picker', $params);
    }




    public static function effectSettings($effect_settings)
    {
        $html = '';
        $settings = $effect_settings['settings'];
        foreach($settings as $name => $value) {
            $html .= self::getFormType($name, $value);
        }
        return $html ;


    }

    public static function varexport($expression, $return = false)
    {
        $export   = var_export($expression, true);
        $patterns = [
            '/array \\(/'                           => '[',
            '/^([ ]*)\\)(,?)$/m'                    => '$1]$2',
            "/=>[ ]?\n[ ]+\\[/"                     => '=> [',
            "/([ ]*)(\\'[^\\']+\\') => ([\\[\\'])/" => '$1$2 => $3',
        ];
        $export   = preg_replace(array_keys($patterns), array_values($patterns), $export);
        $export = nl2br($export);
        $export = str_replace("  ", "&nbsp;&nbsp;&nbsp;&nbsp;", $export);
        if ((bool) $return) {
            return $export;
        }  echo $export;
    }



    private static function getFormType($setting_name, $setting_value)
    {
        $setting_label = ucwords(str_replace("_"," ",$setting_name));
        switch($setting_name) {
            case 'mirror':
            case 'use_gradient':
            case 'reverse_roll':
            case 'manually_resize_bars':
            case 'change_color':
            case 'reverse':
            case 'use_custom_color':
                case 'swap_side':
                    case 'use_color_variation':

                return HTMLForms::getCheckbox(['NAME' => $setting_name,'LABEL' =>$setting_label,'VALUE' => $setting_value]);
                break;

            case 'custom_color':
                return self::customColorBox();
                break;
            case 'color':
                return self::colorSelectBox( $setting_value);
                break;
            case 'gradient':
                return self::gradientsSelectBox( $setting_value);
                break;
            case 'speed':
                case 'bubble_length':
                    case 'bubble_repeat':
                case 'blur':
                case 'star_ascending_speed':
                case 'star_descending_speed':
                case 'star_rising_speed':
                case 'stars_count':
                case 'stars_length':
                    case 'color_variation':
                    case 'firebase_area_maxlength':
                    case 'firebase_area_minlength':
                    case 'firebase_flicker_speed':
                    case 'sparks_area_maxlength':
                    case 'sparks_area_minlength':
                    case 'sparks_flicker_speed':
                    case 'sparks_fly_speed':
                    case 'sparks_max_length':
                    case 'sparks_maxappear_distance':
                    case 'sparks_min_length':
                    case 'sparks_minappear_distance':
                    case 'mask_blur':

                return HTMLForms::rangeSlider(['NAME' => $setting_name,'LABEL' => $setting_label,'VALUE' => $setting_value]);
                break;

                case 'firebase_maincolor':
                    return self::colorSelectBox( $setting_value);
                    // HTMLForms::rangeSlider(['NAME' => $setting_name,'LABEL' => 'Fire Base Color','VALUE' => $setting_value]);
                    break;
    
                    case 'sparks_maincolor':
                        return self::colorSelectBox( $setting_value);
                        //HTMLForms::rangeSlider(['NAME' => $setting_name,'LABEL' => 'Sparks Base Color','VALUE' => $setting_value]);
                        break;
        

            case 'pendulum_length':
                return HTMLForms::rangeSlider(['NAME' => $setting_name,'LABEL' => 'Length','VALUE' => $setting_value]);
                break;

                case 'rods_distance':
                    return HTMLForms::rangeSlider(['NAME' => $setting_name,'LABEL' => 'Rod Distance','VALUE' => $setting_value]);
                    break;
                    case 'rods_length':
                        return HTMLForms::rangeSlider(['NAME' => $setting_name,'LABEL' => 'Rod Length','VALUE' => $setting_value]);
                        break;
                        case str_starts_with($setting_name,"segment"):
                            return HTMLForms::segmentSetting($setting_name,$setting_label,$setting_value);
                           // return '<span class="text-info">' . $setting_name . '</span><br>';
                            break;
            default:
                return '<span class="text-danger">' . $setting_name . '</span><br>';
                break;


        }




    }







}
