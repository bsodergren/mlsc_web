<?php
/**
 *
 * MLS Script
 *
 */

namespace MLSC\Core;

class MLSCApi
{
    public $conn;

    public function __construct()
    {
        $this->conn = curl_init();
    }

    private function setOptions($url)
    {
        curl_setopt($this->conn, \CURLOPT_URL, $url);
        curl_setopt($this->conn, \CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
        ]);
        curl_setopt($this->conn, \CURLOPT_RETURNTRANSFER, 1);
    }

    private function execute($url)
    {
        $this->setOptions($url);
        $result = curl_exec($this->conn);
        if (!$result)
        {
            exit('Connection Failure');
        }
        curl_close($this->conn);

        return $result;
    }

    public static function post($url, $data)
    {
        $curl = new self();

        curl_setopt($curl->conn, \CURLOPT_POST, 1);
        if ($data)
        {
            curl_setopt($curl->conn, \CURLOPT_POSTFIELDS, $data);
        }

        return $curl->execute($url);
    }

    public static function put($url, $data)
    {
        $curl = new self();
        curl_setopt($curl->conn, \CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data)
        {
            curl_setopt($curl->conn, \CURLOPT_POSTFIELDS, $data);
        }

        return $curl->execute($url);
    }

    public static function get($url, $data)
    {
        $curl = new self();
        if ($data)
        {
            $url = sprintf('%s?%s', $url, http_build_query($data));
        }

        return $curl->execute($url);
    }

    public static function delete($url, $data)
    {
        $curl = new self();
        curl_setopt($curl->conn, \CURLOPT_CUSTOMREQUEST, 'DELETE');
        if ($data)
        {
            curl_setopt($curl->conn, \CURLOPT_POSTFIELDS, $data);
        }

        return $curl->execute($url);
    }
}
