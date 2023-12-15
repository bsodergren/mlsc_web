<?php

namespace MLSC\Controller;

use MLSC\Core\MLSC;
use MLSC\Core\Device;
use MLSC\Core\Effects;
use MLSC\Core\MLSCApi;
use MLSC\Utilities\MLSCArray;

class Processor extends MLSC
{
    public $msg = false;
    public $devices = false;

    public function __construct()
    {
        if(array_key_exists('action', $_REQUEST)) {
            $this->devices = Device::deviceList();
            $this->process($_REQUEST);
            $GLOBALS['_REQUEST']['msg'] = $this->msg;
        }
    }

    private function process($array)
    {
        $method = $array['action'];
        $this->$method($array);
    }

    private function effect($request)
    {

        if(array_key_exists('reset', $_REQUEST)) {
           $data_json = include __DEFAULT_EFFECT_DIR__ . DIRECTORY_SEPARATOR . $_REQUEST['effect'] . '.php';
           $this->msg = 'Default settings loaded';
        } else {
            $array = MLSCArray::settingToJson($request);
            $effect_string = implode(",", $array['settings']);
            $data_json = '{' . $array[0] . ', "settings": {' . $effect_string . '}}';
            $this->msg = 'Effect settings updated <br>';
        }
        $get_data  = MLSCApi::post(DEV_EFFECT_URL, $data_json);
        $response_array  = json_decode($get_data, true);

    }

    private function brightness($request)
    {
        $led_brightness = $request['led_brightness:number'];
        $result_array = MLSC::setDeviceBrightness($led_brightness);
        $response_array = json_encode($result_array, JSON_PRETTY_PRINT);
        $this->msg = 'Brightness set to ' . $led_brightness . ' <br>';
    }
    private function activate($request)
    {

        foreach($this->devices as $device_id) {
            $response[] = Effects::setDeviceActive($device_id, $request['effect']);
        }
        $response_array = json_encode($response, JSON_PRETTY_PRINT);
        $this->msg = 'Active Effect changed to ' . $request['effect'] . ' <br>';
    }


}
