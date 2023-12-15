<?php
/**
 * Command like Metatag writer for video files.
 */

namespace MLSC\Core;

use MLSC\Core\MLSCApi;
use MLSC\Core\MLSCDevice;

class MLSC
{
    public function __construct() {

    }



    public function deviceExistsIp($ip)
    {
        foreach (MLSCDevice::deviceList('all') as $dev_id) {
            $getURL   = DEV_UDP_URL . '?device=' . $dev_id . '&output_type_key=output_udp&setting_key=udp_client_ip';
            $get_data = MLSCApi::get( $getURL, '');
            $return   = json_decode($get_data, true);

            if ($return['setting_value'] == $ip) {
                return $return['device'];

                break;
            }
        }
    }

    public function setDeviceBrightness($brightness, $device_id = 'all')
    {
        global $brightness_json;

        $device_json  = json_encode($brightness_json);

        logger('device_json ' . $device_json);

        $curr         = 0;

        $device_array = MLSCDevice::deviceList($device_id);
        foreach ($device_array as $dev_id) {
            unset($tmp_device_json);

            $tmp_device_json = '{"device":"' . $dev_id . '","settings":{"led_brightness":' . $brightness . '}}';
            //$tmp_device_json = str_replace('%%DEVICE_ID%%', $dev_id, $device_json);
            //$tmp_device_json = str_replace("'%%BRIGHTNESS%%'", $brightness, $tmp_device_json);

            $get_data        = MLSCApi::post( DEV_SETTINGS_URL, $tmp_device_json);
            $response_array = json_decode($get_data, true);
            output('Device ID ' . $response_array['device'] . ' changed to ' . $response_array['settings']['led_brightness'], 'cyan', 1);
            //++$curr;
        }

        return $response_array;
    }

    public function deleteDevice($device_id, $force = 0)
    {
        foreach (MLSCDevice::deviceList($device_id) as $dev_id) {
            preg_match('/device_([0-9]+)/', $dev_id, $match);

            if ($match[1] > 4 || 1 == $force) {
                $device_json = ['device' => $dev_id];

                $get_data    = MLSCApi::delete( DEV_SYSTEM_URL, json_encode($device_json));
                $response    = json_decode($get_data, true);
                if (array_key_exists('device_id', $response)) {
                    output('Device ID ' . $response['device_id'] . ' deleted', 'cyan', 1);
                }
            }
        }
    }

    public function deleteStaleDevices()
    {
        foreach (MLSCDevice::deviceList('all') as $dev_id) {
            $getURL   = DEV_UDP_URL . '?device=' . $dev_id . '&output_type_key=output_udp&setting_key=udp_client_ip';
            $get_data = MLSCApi::get( $getURL, '');
            $return   = json_decode($get_data, true);

            $ip       = $return['setting_value'];

            exec('ping -c 1  -W1 ' . $ip, $output, $result);

            if (1 == $result) {
                output("{$dev_id} at {$ip} is Unreachable", 'light_red', 1);
                deleteDevice($dev_id);
            } else {
                output("{$dev_id} at {$ip} is reachable", 'light_green', 1);
            }
        }
    }

    public function addNewDevice()
    {
        $get_data   = MLSCApi::post( DEV_SYSTEM_URL, ' ');
        $response   = json_decode($get_data, true);
        $device_key = $response['device_id'];
        output("device key {$device_key} ", 'light_red');

        return $device_key;
    }

    public function updateDeviceIP($device_id, $ip_address)
    {
        global $device_udp_settings;
        $udp_json     = json_encode($device_udp_settings);
        $tmp_udp_json = [];

        $tmp_udp_json = str_replace('%%DEVICE_ID%%', $device_id, $udp_json);
        $tmp_udp_json = str_replace('%%DEVICE_IP%%', $ip_address, $tmp_udp_json);
        output("device key {$tmp_udp_json} ", 'light_red');

        logger('tmp_udp_json ' . $tmp_udp_json);

        $get_data     = MLSCApi::post( DEV_UDP_URL, $tmp_udp_json);

        return json_decode($get_data, true);
    }

    public function updateDeviceSettiings($device_id, $device_name)
    {
        global $device_settings;

        $device_json     = json_encode($device_settings);

        $tmp_device_json = [];

        $tmp_device_json = str_replace('%%DEVICE_ID%%', $device_id, $device_json);
        $tmp_device_json = str_replace('%%DEVICE_NAME%%', $device_name, $tmp_device_json);

        logger('tmp_device_json ' . $tmp_device_json);

        $get_data        = MLSCApi::post( DEV_SETTINGS_URL, $tmp_device_json);

        return json_decode($get_data, true);
    }

    public function updateDeviceLED($device_id, $led_count, $led_mid)
    {
        $led_json        = '{ "device": "%%DEVICE_ID%%", "settings": {"led_count": ' . $led_count . ',"led_mid": ' . $led_mid . '}}';

        $tmp_device_json = str_replace('%%DEVICE_ID%%', $device_id, $led_json);

        $get_data        = MLSCApi::post( DEV_SETTINGS_URL, $tmp_device_json);

        json_decode($get_data);
    }



    public function getDeviceBrightness($device_id = "device_0")
    {

        MLSCDevice::deviceId($device_id);

        $getURL = DEV_SETTINGS_URL . '?device=' . $device_id . '&setting_key=led_brightness';

        $get_data = MLSCApi::get( $getURL, '');

        return json_decode($get_data, true);
    }


    // get all effects
    public function getDeviceEffect($device_id, $effect_name = '')
    {
        MLSCDevice::deviceId($device_id);

        if ('' == $effect_name) {
            $getURL = DEV_ACTIVE_EFFECT . '?device=' . $device_id;
        } else {
            $getURL = DEV_EFFECT_URL . '?device=' . $device_id . '&effect=' . $effect_name;
        }

        $get_data = MLSCApi::get( $getURL, '');

        return json_decode($get_data, true);
    }

    public function getColors()
    {
        $get_data = MLSCApi::get( DEV_RESOURCE_COLORS, '');

        return json_decode($get_data, true);
    }

    public function getGradients()
    {
        $get_data = MLSCApi::get( DEV_RESOURCE_GRADIENTS, '');

        return json_decode($get_data, true);
    }




    public function getEffects()
    {
        $get_data = MLSCApi::get( DEV_EFFECTS_LIST, '');

        return json_decode($get_data, true);
    }

    public function updateDeviceEffects($device_id)
    {
        MLSCDevice::deviceId($device_id);
        $effect_array = self::getEffects();

        foreach ($effect_array['order'] as $name => $__) {
            if ('effect_off' == $name || 'effect_random_music' == $name || 'effect_random_non_music' == $name) {
                continue;
            }

            unset($custom_settings_arr, $default_settings_arr, $custom_settings, $default_settings);

            $custom_settings_arr  =  self::getDeviceEffect('device_0', $name);
            $default_settings_arr = self::getDeviceEffect($device_id, $name);

            $custom_settings      = $custom_settings_arr['settings'];
            $default_settings     = $default_settings_arr['settings'];

            if (!arrays_are_equal($custom_settings, $default_settings)) {
                $settings_json   = json_encode($custom_settings);
                $tmp_device_json = '{ "device": "' . $device_id . '",  "effect": "' . $name . '",  "settings": ' . $settings_json . ' }';
                logger('tmp_device_json ' . $tmp_device_json);
                $get_data        = MLSCApi::post( DEV_EFFECT_URL, $tmp_device_json);
                $return_array[]  = json_decode($get_data);
            }
        }

        return $return_array;
    }

    /* Get Active Effect
    curl -X GET "http://lights.local/api/effect/active?device=device_0" -H "accept: application/json"


    */

    /* Set active effect
    // all devices
    curl -X POST "http://lights.local/api/effect/active" -H "accept: application/json" -H "Content-Type: application/json" -d "{\"effect\":\"effect_off\"}"
    some devices
    curl -X POST "http://lights.local/api/effect/active" -H "accept: application/json" -H "Content-Type: application/json" -d "{\"devices\":[{\"device\":\"device_0\",\"effect\":\"effect_off\"}]}"
    {
      "devices": [
        {
          "device": "device_0",
          "effect": "effect_off"
        }
      ]
    }

    curl -X POST "http://lights.local/api/effect/active" -H "accept: application/json" -H "Content-Type: application/json" -d "{\"device\":\"device_0\",\"effect\":\"effect_off\"}"
    {
      "device": "device_0",
      "effect": "effect_off"
    }

    */

    public function setDeviceActive($device_id, $effect_name)
    {

        $data_json = '{   "effect": "' . $effect_name . '"}';
        logger('data_json ' . $data_json);
        return MLSCApi::post( DEV_ACTIVE_EFFECT, $data_json);
    }




    public function showEffectsSettings($effect)
    {

        $effect_array = json_decode($effect, JSON_OBJECT_AS_ARRAY);
        return $effect_array['settings'];
    }



    public function setEffectSettings($effect_name, $settings, $device_id = 'all')
    {

        $device_array = MLSCDevice::deviceList($device_id);

        foreach ($device_array as $dev_id) {
            $data_json = '{"device": "' . $dev_id . '","effect": "' . $effect_name . '","settings": {"' . $settings['name'] . '": "' . $settings['value'] . '"}}';

            logger('data_json ' . $data_json);
            $get_data  = MLSCApi::post( DEV_ACTIVE_EFFECT, $data_json);
            $response_array  = json_decode($get_data, true);
            output($data_json);
            output('Device ID ' . var_export($response_array, 1) . ' changed to ', 'cyan', 1);
        }




    }
}
