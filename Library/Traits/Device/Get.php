<?php

namespace MLSC\Traits\Device;

use MLSC\Modules\HTTP\HTTP;

trait Get
{
    // Returns array of device_id
    public function getAllDevices()
    {
        if (0 == count($this->devices))
        {
            $this->devices = HTTP::getSystem();

            foreach ($this->devices as $k => $device)
            {
                $settings              = $this->getDeviceInfo($device['id']);
                $devices[$k]['id']     = $this->devices[$k]['id'];
                $devices[$k]['device'] = $settings['device'];
                $devices[$k]['name']   = $this->devices[$k]['name'];
                $devices[$k]['ip']     = $settings['setting_value']['output_udp']['udp_client_ip'];
            }
            $this->devices = $devices;
        }
    }

    public function getDeviceInfo($device_id)
    {
        self::clean($device_id);
        $data = [
            'device'      => $device_id,
            'setting_key' => 'output',
        ];

        return HTTP::getSettings($data);
    }

    public function getDeviceEffect($device_id, $effect_name = "")
    {
        deviceId($device_id);

        if ($effect_name == "")
        {
            $getURL = DEV_ACTIVE_EFFECT."?device=".$device_id;
        } else
        {
            $getURL = DEV_EFFECT_URL."?device=".$device_id."&effect=".$effect_name;
        }

        $get_data = MLSCApi::get( $getURL, "");
        return json_decode($get_data, true);
    }


    public function getEffects()
    {
        $get_data = MLSCApi::get( DEV_EFFECTS_LIST, "");
        return json_decode($get_data, true);
    }
}
