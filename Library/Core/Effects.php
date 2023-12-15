<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Core;

use MLSC\Utilities\MLSCArray;

class Effects extends MLSC
{
    public static function getActiveEffect()
    {
        $data        = self::getDeviceEffect('device_0');

        return $data['effect'];
    }

    // get all effects
    public static function getDeviceEffect($device_id, $effect_name = '')
    {
        Device::deviceId($device_id);

        if ('' == $effect_name)
        {
            $getURL = DEV_ACTIVE_EFFECT.'?device='.$device_id;
        } else
        {
            $getURL = DEV_EFFECT_URL.'?device='.$device_id.'&effect='.$effect_name;
        }

        $get_data = MLSCApi::get($getURL, '');

        return json_decode($get_data, true);
    }

    public static function getEffects()
    {
        $get_data = MLSCApi::get(DEV_EFFECTS_LIST, '');

        return json_decode($get_data, true);
    }

    public static function updateDeviceEffects($device_id)
    {
        Device::deviceId($device_id);
        $effect_array = self::getEffects();

        foreach ($effect_array['order'] as $name => $__)
        {
            if ('effect_off' == $name || 'effect_random_music' == $name || 'effect_random_non_music' == $name)
            {
                continue;
            }

            unset($custom_settings_arr, $default_settings_arr, $custom_settings, $default_settings);

            $custom_settings_arr  =  self::getDeviceEffect('device_0', $name);
            $default_settings_arr = self::getDeviceEffect($device_id, $name);

            $custom_settings      = $custom_settings_arr['settings'];
            $default_settings     = $default_settings_arr['settings'];

            if (!MLSCArray::arrays_are_equal($custom_settings, $default_settings))
            {
                $settings_json   = json_encode($custom_settings);
                $tmp_device_json = '{ "device": "'.$device_id.'",  "effect": "'.$name.'",  "settings": '.$settings_json.' }';
                $get_data        = MLSCApi::post(DEV_EFFECT_URL, $tmp_device_json);
                $return_array[]  = json_decode($get_data);
            }
        }

        return $return_array;
    }

    public static function setDeviceActive($device_id, $effect_name)
    {
        $data_json = '{   "effect": "'.$effect_name.'"}';

        return MLSCApi::post(DEV_ACTIVE_EFFECT, $data_json);
    }

    public static function showEffectsSettings($effect)
    {
        $effect_array = json_decode($effect, \JSON_OBJECT_AS_ARRAY);

        return $effect_array['settings'];
    }

    public static function setEffectSettings($effect_name, $settings, $device_id = 'all')
    {
        $device_array = Device::deviceList($device_id);

        foreach ($device_array as $dev_id)
        {
            $data_json       = '{"device": "'.$dev_id.'","effect": "'.$effect_name.'","settings": {"'.$settings['name'].'": "'.$settings['value'].'"}}';
            $get_data        = MLSCApi::post(DEV_ACTIVE_EFFECT, $data_json);
            $response_array  = json_decode($get_data, true);
        }
    }
}
