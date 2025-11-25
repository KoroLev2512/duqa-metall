<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

namespace Artamonov\Bitrix24;


class Integration
{
    private static $_instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function configured()
    {
        return Config::getInstance()->get('portalAddress') && Config::getInstance()->get('integrationCode') && Config::getInstance()->get('integrationUserId') ? true : false;
    }

    public function portal()
    {
        return Config::getInstance()->get('portalAddress');
    }

    public function secretKey()
    {
        return Config::getInstance()->get('integrationCode');
    }

    public function userId()
    {
        return Config::getInstance()->get('integrationUserId');
    }

    public function __call($name, $arguments)
    {
        die('Method \'' . $name . '\' is not defined');
    }

    private function __clone()
    {
    }
}
