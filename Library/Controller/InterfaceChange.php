<?php

namespace MLSC\Controller;

class InterfaceChange
{
    public function __construct()
    {
    }
    public function onHostChange($event, $mac_id = '')
    {
        output("$event --------------------------------------", "light_blue");
        if ($event == "AP-STA-CONNECTED")
        {
            $ip_address = trim(shell_exec('ip neighbor |grep "'.$mac_id.'" | cut -d" " -f1'));
            if (filter_var($ip_address, FILTER_VALIDATE_IP) !== false)
            {
                output("A device connected with an IP of $ip_address", "yellow");
                if (!deviceExistsIp($ip_address))
                {
                    output("Device doesnt Exists", "green");
                    $device_key  = addNewDevice();
                    $box_id      = (int)$device_key - 4;
                    $device_name = "Box ".(string)$box_id;
                    $response1   = updateDeviceSettiings($device_key, $device_name);
                    $response2   = updateDeviceIP($device_key, $ip_address);
                    $response3   = updateDeviceEffects($device_key);

                    updateDeviceLED($device_key, 60, 30);
                    $data        = getDeviceEffect("device_0");

                    $response4   = setDeviceActive($device_key, $data["effect"]);

                    output(print_r($response4, true), "white");
                    output("Add New device at $device_key with name of $device_name", "green");
                } else
                {
                    output("Device Exists", "green");
                }
            }
        } elseif ($event == "AP-STA-DISCONNECTED")
        {
            output("a device has disconnected", "light_red");
            deleteStaleDevices();
        }
    }
}
