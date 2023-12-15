<?php
/**
 * CWP Media tool
 */

namespace MLSC\Traits\Device;

use MLSC\Modules\HTTP\HTTP;

trait Set
{
    public $brightness_json = [
        'device'   => '',
        'settings' => [
            'led_brightness' => '',
        ],
    ];

    private function set($action, $data)
    {
        $method = 'Set'.$action;
        $r      = HTTP::$method(['json' => $data]);
    }

    public function setBrightness($brightness, $device_id = '')
    {
        foreach ($this->devices as $device)
        {
            $this->brightness_json['device']                     = $device['id'];
            $this->brightness_json['settings']['led_brightness'] = $brightness;
            $this->set('settings', $this->brightness_json);
            echo 'setting brightness for '.$device['id'].\PHP_EOL;
        }
    }

    public function setDeviceActive($device_id, $effect_name)
    {
        deviceId($device_id);
        $data_json = '{  "device": "'.$device_id.'",  "effect": "'.$effect_name.'"}';
        logger('data_json '.$data_json);
        $get_data  = MLSCApi::post( DEV_ACTIVE_EFFECT, $data_json);

        return $get_data;
    }
}
