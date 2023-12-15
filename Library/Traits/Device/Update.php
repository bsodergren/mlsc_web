<?php
/**
 * CWP Media tool
 */

namespace MLSC\Traits\Device;

use MLSC\Modules\HTTP\HTTP;

trait Update
{
    public $device_settings = [
        "device"   => "",

        "settings" => array(
            "device_name"    => "",
            "device_groups"  => array("Bedroom"),
            "fps"            => 60,
            "led_brightness" => 25,
            "led_strip"      => "ws2812_strip",
            "output_type"    => "output_udp"
        )
        ];


    private function update($data)
    {
        $r = HTTP::SetSettings(['json' => $data]);
    }



    public function updateDeviceIP($device_id, $ip_address)
    {
        global $device_udp_settings;
        $udp_json     = json_encode($device_udp_settings);
        $tmp_udp_json = [];

        $tmp_udp_json = str_replace('%%DEVICE_ID%%', 'device_'.$device_id, $udp_json);
        $tmp_udp_json = str_replace('%%DEVICE_IP%%', $ip_address, $tmp_udp_json);
        logger('tmp_udp_json '.$tmp_udp_json);

        $get_data     = MLSCApi::post( DEV_UDP_URL, $tmp_udp_json);

        return json_decode($get_data, true);
    }

    public function updateSettings($device)
    {
        $this->device_settings["device"]                  = $device[0];
        $this->device_settings['settings']["device_name"] = $device[1];
        $this->update($this->device_settings);
    }

    public function updateDeviceLED($device_id, $led_count, $led_mid)
    {
        $led_json        = '{ "device": "%%DEVICE_ID%%", "settings": {"led_count": '.$led_count.',"led_mid": '.$led_mid.'}}';

        $tmp_device_json = str_replace('%%DEVICE_ID%%', 'device_'.$device_id, $led_json);

        $get_data        = MLSCApi::post( DEV_SETTINGS_URL, $tmp_device_json);

        json_decode($get_data);
    }

    public function updateDeviceEffects($device_id)
    {
        deviceId($device_id);
        $effect_array = getEffects();

        foreach ($effect_array['order'] as $name => $__)
        {
            if ('effect_off' == $name || 'effect_random_music' == $name || 'effect_random_non_music' == $name)
            {
                continue;
            }

            unset($custom_settings_arr);
            unset($default_settings_arr);
            unset($custom_settings);
            unset($default_settings);

            $custom_settings_arr  = getDeviceEffect('device_0', $name);
            $default_settings_arr = getDeviceEffect($device_id, $name);

            $custom_settings      = $custom_settings_arr['settings'];
            $default_settings     = $default_settings_arr['settings'];

            if (!arrays_are_equal($custom_settings, $default_settings))
            {
                $settings_json   = json_encode($custom_settings);
                $tmp_device_json = '{ "device": "'.$device_id.'",  "effect": "'.$name.'",  "settings": '.$settings_json.' }';
                logger('tmp_device_json '.$tmp_device_json);
                $get_data        = MLSCApi::post( DEV_EFFECT_URL, $tmp_device_json);
                $return_array[]  = json_decode($get_data);
            }
        }

        return $return_array;
    }
}
