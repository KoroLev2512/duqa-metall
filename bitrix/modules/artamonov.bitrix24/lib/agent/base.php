<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

namespace Artamonov\Bitrix24\Agent;


use Artamonov\Bitrix24\Settings;

class Base
{
    private static $_instance;
    protected $function;
    protected $interval = 60;
    protected $period = 'N';

    public function add()
    {
        \CAgent::AddAgent($this->function, Settings::getInstance()->get('module')['id'], $this->period, $this->interval, FormatDate('FULL', time() + $this->interval), 'Y', FormatDate('FULL', time() + $this->interval));
    }

    public function remove()
    {
        \CAgent::RemoveAgent($this->function, Settings::getInstance()->get('module')['id']);
    }

    protected function log($type, $item, $description)
    {
        \CEventLog::add(['AUDIT_TYPE_ID' => $type, 'MODULE_ID' => Settings::getInstance()->get('module')['id'], 'ITEM_ID' => $item, 'DESCRIPTION' => $description]);
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
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
