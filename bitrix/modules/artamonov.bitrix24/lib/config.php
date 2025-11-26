<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

namespace Artamonov\Bitrix24;


use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

class Config
{
    private static $_instance;
    private $parameters;
    private $data;

    public function form($id)
    {
        if ($_POST) {
            $_POST['form'] = $id;
            if (isset($_POST['save'])) {
                $this->save();
            } else if (isset($_POST['restore'])) {
                $this->restore();
            }
        }
    }

    public function get($code)
    {
        if (!is_array($this->parameters)) {
            $result = Application::getConnection()->query('SELECT NAME, VALUE FROM b_option WHERE MODULE_ID="' . Settings::getInstance()->get('module')['id'] . '"');
            while ($parameter = $result->fetch()) {
                $this->parameters[$parameter['NAME']] = $parameter['VALUE'];
            }
        }
        return $this->parameters[$code];
    }

    public function save()
    {
        $this->prepare();
        foreach ($this->data as $code => $value) {
            Option::set(Settings::getInstance()->get('module')['id'], $code, $value, false);
            $this->parameters[$code] = $value;
        }
        $this->checkAgent(__FUNCTION__);
        Helper::getInstance()->successfulMessage(Loc::getMessage('ArtamonovBitrix24Saved'));
    }

    public function restore()
    {
        $this->prepare();
        foreach ($this->data as $code => $value) {
            Option::delete(Settings::getInstance()->get('module')['id'], ['name' => $code]);
            unset($this->parameters[$code]);
        }
        $this->checkAgent(__FUNCTION__);
        Helper::getInstance()->successfulMessage(Loc::getMessage('ArtamonovBitrix24Restored'));
    }

    private function prepare()
    {
        switch ($_POST['form']) {
            case 'bitrix24-extension-widget':
                // Checkboxes
                if (!isset($_POST[Settings::getInstance()->get('config')['prefix'] . 'useCustomizationWidget'])) {
                    $_POST[Settings::getInstance()->get('config')['prefix'] . 'useCustomizationWidget'] = false;
                }
                if (!isset($_POST[Settings::getInstance()->get('config')['prefix'] . 'useChannelWhatsApp'])) {
                    $_POST[Settings::getInstance()->get('config')['prefix'] . 'useChannelWhatsApp'] = false;
                }
                break;
            case 'bitrix24-extension-config':
                if (isset($_POST[Settings::getInstance()->get('config')['prefix'] . 'portalAddress'])) {
                    $_POST[Settings::getInstance()->get('config')['prefix'] . 'portalAddress'] = trim($_POST[Settings::getInstance()->get('config')['prefix'] . 'portalAddress'], '/');
                }
                break;
            case 'bitrix24-extension-export-users':
                // Checkboxes
                if (!isset($_POST[Settings::getInstance()->get('config')['prefix'] . 'useExportRegisteredUsers'])) {
                    $_POST[Settings::getInstance()->get('config')['prefix'] . 'useExportRegisteredUsers'] = false;
                }
                if (!isset($_POST[Settings::getInstance()->get('config')['prefix'] . 'exportRegisteredUsersExport'])) {
                    $_POST[Settings::getInstance()->get('config')['prefix'] . 'exportRegisteredUsersExport'] = false;
                }
                if (!isset($_POST[Settings::getInstance()->get('config')['prefix'] . 'exportRegisteredUsersOpened'])) {
                    $_POST[Settings::getInstance()->get('config')['prefix'] . 'exportRegisteredUsersOpened'] = false;
                }

                if (
                    $_POST[Settings::getInstance()->get('config')['prefix'] . 'usersExportPeriod'] > 0 &&
                    $_POST[Settings::getInstance()->get('config')['prefix'] . 'usersExportPeriod'] != Config::getInstance()->get('usersExportPeriod')
                ) {
                    $this->removeAgent('UsersExport');
                }
                break;
        }
        foreach ($_POST as $key => $value) {
            if (stripos($key, Settings::getInstance()->get('config')['prefix']) !== false) {
                $code = str_replace(Settings::getInstance()->get('config')['prefix'], '', $key);
                $this->data[$code] = trim($value);
            }
        }
    }

    private function checkAgent($type)
    {
        if ($_POST['form'] === 'bitrix24-extension-export-users') {
            if ($type === 'restore') {
                $this->removeAgent('UsersExport');
                return;
            }
            $_POST[Settings::getInstance()->get('config')['prefix'] . 'usersExportPeriod'] > 0 ? $this->addAgent('UsersExport') : $this->removeAgent('UsersExport');
        }
    }

    private function addAgent($agent)
    {
        $agent = '\\Artamonov\\Bitrix24\\Agent\\' . $agent;
        $agent = new $agent();
        $agent->add();
    }

    private function removeAgent($agent)
    {
        $agent = '\\Artamonov\\Bitrix24\\Agent\\' . $agent;
        $agent = new $agent();
        $agent->remove();
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
