<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

namespace Artamonov\Bitrix24;


class RequestType
{
    private static $_instance;
    private static $_url;

    public function get($path)
    {
        $result = json_decode(file_get_contents(self::$_url . '/' . $path), true)['result'];
        return $result;
    }

    public function post($path, $params)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => self::$_url . '/' . $path,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => http_build_query($params)
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, true)['result'];
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
            self::$_url = Integration::getInstance()->portal() . '/rest/' . Integration::getInstance()->userId() . '/' . Integration::getInstance()->secretKey();
        }
        return self::$_instance;
    }

    private function __construct()
    {
    }

    public function __call($name, $arguments)
    {
        die('Method \'' . $name . '\' is not defined');
    }

    private function __clone()
    {
    }
}
