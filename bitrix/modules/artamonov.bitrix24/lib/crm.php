<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

namespace Artamonov\Bitrix24;


class Crm
{
    private static $_instance;

    public function addContact($fields)
    {
        if (!$fields['RESULT'] || !Config::getInstance()->get('useExportRegisteredUsers') || !Config::getInstance()->get('exportRegisteredUsersResponsibleId')) {
            return 'экспорт не удался';
        }

        $type = Config::getInstance()->get('usersExportType');

        $exist = Request::getInstance()->send()->post('crm.duplicate.findbycomm', [
            'entity_type' => 'CONTACT|LEAD',
            'type' => 'EMAIL',
            'values' => [
                $fields['EMAIL']
            ]
        ]);

        if ($exist) {
            return $exist['CONTACT'] ? 'контакт существует' : 'лид существует';
        }

        $path = $type === 'contact' ? 'crm.contact.add' : 'crm.lead.add';

        $request = [
            'fields' => [
                'TITLE' => 'Зарегистрирован новый пользователь: ' . trim($fields['NAME'] . ' ' . $fields['LAST_NAME']),
                'NAME' => $fields['NAME'],
                'SECOND_NAME' => $fields['SECOND_NAME'],
                'LAST_NAME' => $fields['LAST_NAME'],
                'ASSIGNED_BY_ID' => Config::getInstance()->get('exportRegisteredUsersResponsibleId'),
                'PHONE' => [['VALUE' => $fields['PERSONAL_PHONE'], 'VALUE_TYPE' => 'WORK']],
                'EMAIL' => [['VALUE' => $fields['EMAIL'], 'VALUE_TYPE' => 'WORK']],
                'OPENED' => Config::getInstance()->get('exportRegisteredUsersOpened') ? 'Y' : 'N',
                'EXPORT' => Config::getInstance()->get('exportRegisteredUsersExport') ? 'Y' : 'N',
                'TYPE_ID' => Config::getInstance()->get('exportRegisteredUsersTypeContactId'),
                'SOURCE_ID' => Config::getInstance()->get('exportRegisteredUsersSourceId')
            ],
            'params' => ['REGISTER_SONET_EVENT' => 'Y']
        ];

        $id = Request::getInstance()->send()->post($path, $request);

        return ($type === 'contact' ? 'Контакт' : 'Лид') . ': ' . $id;
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
