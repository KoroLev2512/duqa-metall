<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

namespace Artamonov\Bitrix24\Agent;


use Bitrix\Main\Application;
use Artamonov\Bitrix24\Config;
use Artamonov\Bitrix24\Crm;

class UsersExport extends Base
{
    public function __construct()
    {
        $this->function = '(new \Artamonov\Bitrix24\Agent\UsersExport())->run();';
        $this->interval = Config::getInstance()->get('usersExportPeriod');
    }

    public function run()
    {
        if (!Config::getInstance()->get('useExportRegisteredUsers') || Config::getInstance()->get('usersExportPeriod') < 1) {
            return $this->function;
        }
        $log = [];
        $lastRun = \CAgent::GetList(['ID' => 'DESC'], ['NAME' => $this->function])->fetch()['LAST_EXEC'];
        $lastRun = $lastRun ? strtotime($lastRun) : time() - $this->interval;
        $lastRun -= 30; // Увеличим интервал еще на 30 секунд, так возможны некоторые задержки в работе платформы
        $sql = 'select * from b_user where UNIX_TIMESTAMP(DATE_REGISTER)>"' . $lastRun . '"';
        $users = Application::getConnection()->query($sql);
        while ($user = $users->fetch()) {
            $user['RESULT'] = true; // Необходимо для отработки функции
            $id = Crm::getInstance()->addContact($user);
            $log[] = 'ID на сайте: ' . $user['ID'] . ' -> ID в Б24: ' . $id;
        }
        if (is_array($log) && count($log) > 0) {
            $log = implode('<br>', $log);
        } else {
            $log = 'Пользователей для экспорта не обнаружено';
        }
        $this->log('Export', 'UsersExport', $log);
        return $this->function;
    }
}
