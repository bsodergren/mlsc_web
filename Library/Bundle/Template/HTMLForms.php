<?php

namespace MLSC\Bundle\Template;

use MLSC\Core\MLSC;
use MLSC\Bundle\Template\Template;

class HTMLForms
{
    public static $formsdir = 'forms';

    public static function getCheckbox($param = [])
    {
        $name = $param['NAME'];
        $label = $param['LABEL'];
        $value = $param['VALUE'];

        return Template::GetHTML(self::$formsdir . "/checkbox", ['CHECKBOX_NAME' => $name, 'CHECKBOX_LABEL' => $label, 'CHECKBOX_VALUE' => $value]);
    }


    public static $select_templates = 'forms/select';

    public static function getSelectOptions($type, $selected = '')
    {
        $option_html = '';
        $option_box = self::$select_templates . '/option';

        if($type == 'colors') {
            $array =  MLSC::getColors();
        }

        if($type == 'gradients') {
            $array =  MLSC::getGradients();

        }

        foreach($array as $name) {
            $select = '';
            if($selected == $name) {
                $select = ' SELECTED';
            }
            $option_html .= Template::GetHTML($option_box, ['OPTION_VALUE' => $name, 'OPTION_NAME' => ucwords($name),'SELECTED' => $select]);
        }
        return $option_html;
    }

    public static function cgSelectBox($type, $selected = '')
    {

        $select_id = strtolower($type);
        $name =  $select_id . ":string";
        $select_class = "form-control " . $select_id . " setting_input";

        $params = [
            'SELECT_LABEL' => ucfirst($type),
            'SELECT_NAME' => $name,
            'SELECT_ID' => $select_id,
            'SELECT_CLASS' => $select_class
        ];

        $params['SELECT_OPTIONS'] = self::getSelectOptions($type, $selected);
        return $params;
    }

    public static function rangeSlider($params = [])
    {
        $range_box = 'forms/range';
        return  Template::GetHTML($range_box, $params);
    }

    public static function textBox($params = [])
    {
        $text_box  = 'forms/textbox';
        return  Template::GetHTML($text_box, $params);
    }

    public static function segmentSetting($setting_name, $setting_label, $setting_value)
    {
        [$name,$id,$action] = explode("_", $setting_name);

        switch($action) {
            case "color":
                return HTMLUtils::colorSelectBox($setting_value);
                break;
            case "start":
            case "end":
                return self::textBox(['NAME' => $setting_name,'LABEL' => $setting_label,'VALUE' => $setting_value]);
                break;



        }



    }

}
