<?php

namespace MLSC\Core;

class MLSCApi
{
    public static $Curl;

    private static function init()
    {
        self::$Curl = curl_init();
    }


    private static function setOptions($url)
    {
        curl_setopt(self::$Curl, CURLOPT_URL, $url);
        curl_setopt(self::$Curl, CURLOPT_HTTPHEADER, array(
          'Accept: application/json',
          'Content-Type: application/json'
        ));
        curl_setopt(self::$Curl, CURLOPT_RETURNTRANSFER, 1);
    }

    private static function execute($url)
    {
        self::setOptions($url);
        $result = curl_exec(self::$Curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close(self::$Curl);
        return $result;
    }
    public static function post($url, $data)
    {
        self::init();
        curl_setopt(self::$Curl, CURLOPT_POST, 1);
        if ($data) {
            curl_setopt(self::$Curl, CURLOPT_POSTFIELDS, $data);
        }

        return self::execute($url);


    }
    public static function put($url, $data)
    {

        curl_setopt(self::$Curl, CURLOPT_CUSTOMREQUEST, "PUT");
        if ($data) {
            curl_setopt(self::$Curl, CURLOPT_POSTFIELDS, $data);
        }

    }
    public static function get($url, $data)
    {
        if ($data) {
            $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        return self::execute($url);
    }

    public static function delete($url, $data)
    {

        curl_setopt(self::$Curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        if ($data) {
            curl_setopt(self::$Curl, CURLOPT_POSTFIELDS, $data);
        }
        return self::execute($url);
    }

}
