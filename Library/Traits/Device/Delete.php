<?php

namespace MLSC\Traits\Device;

trait Delete
{
    public function deleteDevice($device_id, $force = 0)
    {
        foreach (deviceList($device_id) as $dev_id)
        {
            preg_match("/device_([0-9]+)/", $dev_id, $match);

            if ($match[1] > 4 || $force == 1)
            {
                $device_json = array("device" => $dev_id );

                $get_data    = MLSCApi::delete( DEV_SYSTEM_URL, json_encode($device_json));
                $response    = json_decode($get_data, true);
                output("Device ID ". $response['device'] ." deleted", 'cyan', 1);
            }
        }
    }

    public function deleteStaleDevices()
    {
        foreach (deviceList("all") as $dev_id)
        {
            $getURL   = DEV_UDP_URL."?device=".$dev_id."&output_type_key=output_udp&setting_key=udp_client_ip";
            $get_data = MLSCApi::get( $getURL, "");
            $return   = json_decode($get_data, true);

            $ip       = $return['setting_value'];

            exec("ping -c 1  -W1 " . $ip, $output, $result);

            if ($result == 1)
            {
                output("$dev_id at $ip is Unreachable", "light_red", 1);
                deleteDevice($dev_id);
            } else
            {
                output("$dev_id at $ip is reachable", "light_green", 1);
            }
        }
    }
}
