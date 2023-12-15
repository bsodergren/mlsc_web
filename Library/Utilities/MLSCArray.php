<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Utilities;

class MLSCArray
{
    public static function diff($array, $compare, $diff = 'key')
    {
        $return_array = [];
        if ('key' == $diff)
        {
            foreach ($array as $key => $value)
            {
                if (!\array_key_exists($key, $compare))
                {
                    $return_array[$key] = $value;
                }
            }
        }

        return $return_array;
    }

    public static function settingToJson($array)
    {
        $jsonArray = [];

        foreach ($array as $key => $value)
        {
            if ($key == 'effect')
            {
                $jsonArray[] = '"effect": "' . $value . '"';
                continue;
            }

            [$effect_setting, $value_type] = explode(":", $key);


            switch ($value_type)
            {
                case 'boolean':
                    if ($value == "off")
                    {
                        $jsonArray['settings'][] = '"' . $effect_setting . '": false';
                    }
                    if ($value == "on")
                    {
                        $jsonArray['settings'][] = '"' . $effect_setting . '": true';
                    }
                    break;
                case 'number':
                    $jsonArray['settings'][] = '"' . $effect_setting . '": ' . $value;
                    break;
                case 'string':
                    $jsonArray['settings'][] = '"' . $effect_setting . '": "' . $value . '"';
                    break;
                case 'rgb':
                    preg_match('/([\d]+), ([\d]+), ([\d]+)/', $value, $output_array);
                    $jsonArray['settings'][] = '"' . $effect_setting . '": [' . $output_array[1] . ',' . $output_array[2] . ',' . $output_array[3] . ']';
                    break;
            }
        }
        return $jsonArray;
    }


    public static function arrays_are_equal($array1, $array2)
    {
        array_multisort($array1);
        array_multisort($array2);
        return (serialize($array1) === serialize($array2));
    }
}
