<?php


use MLSC\Utilities\MLSCArray;
use MLSC\Bundle\Template\Template;

define('__HTTP_DOCROOT__', $_SERVER['DOCUMENT_ROOT']);
require_once __HTTP_DOCROOT__ . DIRECTORY_SEPARATOR . ".config.php";

$devices = deviceList();
$msg= false;
switch($_REQUEST['action']) {
    case 'effect':
        $array = MLSCArray::settingToJson($_REQUEST);
        $effect_string = implode(",", $array['settings']);
        $data_json = '{' . $array[0] . ', "settings": {' . $effect_string . '}}';
        $get_data  = MLSCApi::post( DEV_EFFECT_URL, $data_json);
        $response_array  = json_decode($get_data, true);
        $msg = 'Effect settings updated <br>';

        break;

    case 'brightness':
        $led_brightness = $_REQUEST['led_brightness:number'];
        $result_array = setDeviceBrightness($led_brightness);
        $response_array = json_encode($result_array, JSON_PRETTY_PRINT);
        $msg = 'Brightness set to ' . $led_brightness. ' <br>';
        break;
    case 'activate':

        foreach($devices as $device_id) {
            $response[] = setDeviceActive($device_id, $_REQUEST['effect']);
        }
        $response_array = json_encode($response, JSON_PRETTY_PRINT);
        $msg = 'Active Effect changed to ' . $_REQUEST['effect']. ' <br>';

        break;
    default:
        $_REQUEST['return_url'] = 'index.php';
        break;

}

if(array_key_exists('return_url',$_REQUEST )){
    Template::javaRefresh($_REQUEST['return_url'], 0,$msg);
} else {
    echo $response_array;
}
