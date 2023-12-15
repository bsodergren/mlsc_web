<?php
/**
 * Command like Metatag writer for video files.
 */

namespace MLSC\Core;

use MLSC\Core\MLSCApi;
use MLSC\Core\Device;
use MLSC\Utilities\MLSCArray;

class MLSC
{
    public function __construct() {}

    public  static function setDeviceBrightness($brightness, $device_id = 'all')
    {
        global $brightness_json;

        $device_json  = json_encode($brightness_json);
        $curr         = 0;
        $device_array = Device::deviceList($device_id);
        foreach ($device_array as $dev_id) {
            unset($tmp_device_json);

            $tmp_device_json = '{"device":"' . $dev_id . '","settings":{"led_brightness":' . $brightness . '}}';
            //$tmp_device_json = str_replace('%%DEVICE_ID%%', $dev_id, $device_json);
            //$tmp_device_json = str_replace("'%%BRIGHTNESS%%'", $brightness, $tmp_device_json);

            $get_data        = MLSCApi::post(DEV_SETTINGS_URL, $tmp_device_json);
            $response_array = json_decode($get_data, true);
            //++$curr;
        }

        return $response_array;
    }

    public static  function getDeviceBrightness($device_id = "device_0")
    {

        Device::deviceId($device_id);

        $getURL = DEV_SETTINGS_URL . '?device=' . $device_id . '&setting_key=led_brightness';

        $get_data = MLSCApi::get($getURL, '');

        return json_decode($get_data, true);
    }



    public static function getColors()
    {
        $get_data = MLSCApi::get(DEV_RESOURCE_COLORS, '');

        return json_decode($get_data, true);
    }

    public static function getGradients()
    {
        $get_data = MLSCApi::get(DEV_RESOURCE_GRADIENTS, '');

        return json_decode($get_data, true);
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

}
