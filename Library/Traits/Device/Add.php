<?php

namespace MLSC\Traits\Device;

use MLSC\Modules\HTTP\HTTP;

trait Add
{
    public function addDevice()
    {
        $result      = HTTP::setSystem();
        $device_key  = str_replace("device_", '', $result['device_id']);
        $box_id      = (int)$device_key - 4;
        $device_name = "Box ".(string)$box_id;

        return [$result['device_id'],$device_name];
    }
}
