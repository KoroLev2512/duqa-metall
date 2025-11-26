<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

namespace Artamonov\Bitrix24;


class Helper
{
    private static $_instance;
    private $sentHeaders = null;

    public function _print($data)
    {
        if (is_array($data) || is_object($data)) {
            echo '<pre style="background-color:#fff; padding:15px">' . print_r($data, true) . '</pre>';
        } else {
            echo $data . '<br>';
        }
    }

    public function isAdminSection()
    {
        return defined('ADMIN_SECTION');
    }

    public function getSentHeaders($code = '')
    {
        if ($this->sentHeaders === null) {
            $headers = headers_list();
            if (count($headers) > 0) {
                foreach ($headers as $header) {
                    $header = explode(':', $header);
                    $name =& $header[0];
                    $name = mb_strtolower($name);
                    $value = trim($header[1]);
                    $this->sentHeaders[$name] = $value;
                }
            } else {
                $this->sentHeaders = [];
            }
        }
        return empty($code) ? $this->sentHeaders : $this->sentHeaders[$code];
    }

    public function getSentContentType()
    {
        return $this->getSentHeaders('content-type');
    }

    public function contentTypeJson()
    {
        return 'application/json';
    }

    public function phpToJs($array)
    {
        return \CUtil::PhpToJSObject($array);
    }

    public function cgi()
    {
        return mb_strpos(php_sapi_name(), 'cgi') !== false;
    }

    public function message($message)
    {
        echo BeginNote() . $message . EndNote();
    }

    public function successfulMessage($message)
    {
        \CAdminMessage::ShowNote($message);
    }

    public function errorMessage($message)
    {
        \CAdminMessage::ShowMessage($message);
    }

    public function hint($message)
    {
        ShowJSHint($message);
    }

    public function REGISTRATION()
    {
        return 'registration';
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
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
