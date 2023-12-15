<?php

namespace MLSC\Core;

use MLSC\Core\MLSCApi;

class MLSCDevice
{

    public static function getAllDevices()
    {
      $get_data = MLSCApi::get( DEV_SYSTEM_URL, "");
      $response = json_decode($get_data, true);
      return $response;
    }

    
    public static function getAllDeviceIds()
    {
    
      $device_array=array();
      $result_array=self::getAllDevices();
      
         
      if ($result_array == true ) {
        foreach ($result_array as $dev_key => $dev_value)
        {
          $device_array[]=$dev_value["id"];
        }
    
        return $device_array;
      } else {
        return false;
      }
    }
    
    public static function deviceId(&$string)
    {
        if (!preg_match('(device)', $string)) {
            $string = 'device_' . $string;
        }
        return $string;
    }



    public static function deviceList($device_id = 'all')
    {
        if (!isset($device_id)) {
            $device_id = 'all';
        }

        if ('all' == $device_id) {
            $devices = self::getAllDeviceIds();
        } elseif (strpos($device_id, ',')) {
            $devices = explode(',', $device_id);
        } else {
            $devices[] = $device_id;
        }

        array_walk($devices, 'deviceId');

        return $devices;
    }


}
