<?php

namespace MLSC\Traits;

use Spatie\Color\Rgb;
use MLSC\Bundle\Template\Template;
use MLSC\Bundle\Template\HTMLForms;
use MLSC\Bundle\Template\Theme\Navbar;

trait TemplateCallbacks
{
    private $hidden = '';
    private function arrayToString($array, $value, $end)
    {
        array_shift($array);
        array_pop($array);
        $array[] = $value;
        $array[] = $end;
        return $this->hidden . implode("", $array);
    }



    private function matchSetting($array, $key, $returnValue = true)
    {
        $setting = $this->params;
        $res = '';
        if (array_key_exists($array[$key], $setting)) {
            if($array[$key] == "custom_color") {
                list($red, $green, $blue) = $setting[$array[$key]];
                $res = ' value="rgb(' . $red . ', ' . $green . ', ' . $blue . ')"';
            } else {

                $value = $setting[$array[$key]];

                if($returnValue === true) {
                    $res = ' value="' . $value . '"';
                } else {
                    $res = $value;
                }

                if ($value === true) {
                    $this->hidden = "<input type='hidden' name='" . $array[$key] . ":boolean' value='off'>";
                    $res =  " value='on' CHECKED";
                }
                if ($value === false) {
                    $res =  " ";
                }
            }
        }

        return $res;
    }

    private function varReplace($key)
    {
        if(array_key_exists($key, $this->params)) {
            return $this->params[$key];
        } else {
            return '';
        }

    }

    public function callback_replace($matches)
    {
        return $this->varReplace($matches[1]);
    }

    public function callback_colorpicker_html($matches)
    {

        $setting = $this->params;
        if (array_key_exists('custom_color', $setting)) {

            list($red, $green, $blue) = $setting['custom_color'];

            $color = 'rgb(' . $red . ', ' . $green . ', ' . $blue . ')';
            $rgb = Rgb::fromString($color);
            $hex = $rgb->toHex();
            $color_value = "#" . $hex->red() . $hex->green() . $hex->blue();
            return Template::GetHTML('forms/rgb_picker', ['HEX_VALUE' => $color_value]);
        }


    }


    public function callback_span_html($matches)
    {
        if (is_array($matches)) {
            if (array_key_exists(2, $matches)) {
                $value = $this->matchSetting($matches, 2, false);
                return $this->arrayToString($matches, $value, "</span>");
            }
        }
    }


    public function callback_input_html($matches)
    {
        $setting = $this->params;
        $res = '';
        if (is_array($matches)) {
            if (array_key_exists(2, $matches)) {
                $value = $this->matchSetting($matches, 2);
                return $this->arrayToString($matches, $value, ">");
            }
        }
    }


    public function callback_selectbox_html($matches)
    {

        $select_options = '';

        $setting = $this->params;

        if (array_key_exists(2, $matches)) {
            $value = '';

            $value = $this->matchSetting($matches, 2, false);

            if (str_contains($matches[4], "colors")) {
                $select_options = HTMLForms::getSelectOptions("colors", $value);
            }
            if (str_contains($matches[4], "gradients")) {
                $select_options = HTMLForms::getSelectOptions("gradients", $value);
            }
        }

        return $this->arrayToString($matches, $select_options, "</select>");
    }
    public function callback_getNavLinks_html($matches){
        $html = '';
        $NavItems = [
            ['URL' => 'index.php',
            'TEXT' => 'Dashboard',
            'ICON' => 'icon-home'],

            ['URL' => 'brightness.php',
            'TEXT' => 'LED Brightnes',
            'ICON' => 'icon-settings'],

            ['URL' => 'activate.php',
            'TEXT' => 'Active Effect',
            'ICON' => 'icon-settings'],
    ];
    foreach($NavItems as $link) {
        
        $html .= Template::GetHTML('base/navbar/menu_link', $link);
    }
    return $html;
    }

    public function callback_getEffects_html($matches)
    {
        $current_effect = '';

        $effect_html = '';


        if (array_key_exists('effect', $_REQUEST)) {
            $current_effect = $_REQUEST['effect'];
        }



        if (array_key_exists(1, $matches)) {
            $effect_html = Navbar::getEffectList('effects.php', $current_effect);
        }


        return $effect_html;
    }

   

    public function callback_include_html($matches)
    {

        $params = [];

        if (array_key_exists(3, $matches)) {
            if (array_key_exists(4, $matches)) {
                if ($matches[4] == '') {
                    return '';
                }
                $params[$matches[3]] = $matches[4];
            }
        }

        $template_file = $matches[1];

        $html = $this->return($template_file, $params);
        return $html;
    }
}
