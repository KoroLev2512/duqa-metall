<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

$MESS = [
    'ArtamonovBitrix24ModuleId' => 'artamonov.bitrix24',
    'ArtamonovBitrix24ModuleName' => 'Bitrix24 Extension',
    'ArtamonovBitrix24ModuleDescription' => 'Модуль расширяет функционал интеграции с Битрикс24',
    'ArtamonovBitrix24TablePrefix' => 'artamonov_bitrix24_',
    'ArtamonovBitrix24AuthorName' => 'Компания Webco',
    'ArtamonovBitrix24MessageInstall' => 'Модуль установлен',
    'ArtamonovBitrix24MessageUninstall' => 'Модуль удален',

    'ArtamonovBitrix24ButtonSave' => 'Сохранить',
    'ArtamonovBitrix24ButtonRestore' => 'Сбросить',

    'ArtamonovBitrix24Saved' => 'Настройки сохранены',
    'ArtamonovBitrix24Restored' => 'Настройки сброшены',
    'ArtamonovBitrix24CurlNotInstalled' => 'На сервере не установлен cURL',

    'ArtamonovBitrix24Contacts' => 'Контакты',
    'ArtamonovBitrix24Leads' => 'Лиды',

    // Menu
    'ArtamonovBitrix24MenuItemUser' => 'Экспорт пользователей',
    'ArtamonovBitrix24MenuItemWidget' => 'Кастомизация виджета',
    'ArtamonovBitrix24MenuItemSettings' => 'Настройки модуля',

    // Config
    'ArtamonovBitrix24PageTitleConfig' => 'Настройки модуля',
    'ArtamonovBitrix24TabIntegrationTitle' => 'Интеграция',
    'ArtamonovBitrix24TabIntegrationDescription' => 'Настройки интеграции',

    'ArtamonovBitrix24PortalAddress' => 'Адрес портала',
    'ArtamonovBitrix24IntegrationCode' => 'Код интеграции',
    'ArtamonovBitrix24IntegrationCodeHint' => 'Секретный код для взаимодействия с порталом',
    'ArtamonovBitrix24IntegrationUserId' => 'ID пользователя',
    'ArtamonovBitrix24IntegrationUserIdHint' => 'Идентификатор пользователя, создавшего вебхук. Под правами этого пользователя будет работать вебхук.',

    'ArtamonovBitrix24NoConnection' => 'Нет связи с порталом. <a href="javascript://" onclick="location.reload(); return false;">Проверить</a>',
    'ArtamonovBitrix24User' => 'Пользователь: ',
    'ArtamonovBitrix24License' => 'Лицензия: ',
    'ArtamonovBitrix24License-ru_nfr' => 'NFR-лицензия',
    'ArtamonovBitrix24License-ru_project' => 'тариф Проект',
    'ArtamonovBitrix24License-ru_tf' => 'тариф Проект+',
    'ArtamonovBitrix24License-ru_team' => 'тариф Команда',
    'ArtamonovBitrix24License-ru_company' => 'тариф Компания',
    'ArtamonovBitrix24License-ru_demo' => 'демо-режим',

    'ArtamonovBitrix24Scope' => 'Права доступа:',

    'ArtamonovBitrix24Need' => 'Интеграция с порталом работает на основе Вебхуков. <a href="https://helpdesk.bitrix24.ru/open/5408147/" target="_blank">Как добавить входящий Вебхук?</a>',

    // Export Users
    'ArtamonovBitrix24PageTitleUsers' => 'Экспорт пользователей',

    'ArtamonovBitrix24TabExportUsersTitleContacts' => 'Настройки',
    'ArtamonovBitrix24TabExportUsersDescription' => 'Настройка экспорта зарегистрированных пользователей',

    'ArtamonovBitrix24NotIntegrated' => 'Не настроена интеграция с порталом. <a href="/bitrix/admin/bitrix24-extension-config.php?lang=ru">Настройки</a>',
    'ArtamonovBitrix24NeedAccessToPortal' => 'Не удалось получить данные с портала.<br>Необходимые права доступа: users, crm.',

    'ArtamonovBitrix24UseExportRegisteredUsers' => 'Экспорт в CRM',
    'ArtamonovBitrix24UseExportRegisteredUsersHint' => 'Если параметр активен, тогда после регистрации нового пользователя информация о нём будет отправлена в CRM.',

    'ArtamonovBitrix24UsersExportType' => 'Тип экспорта',
    'ArtamonovBitrix24UsersExportTypeHint' => 'Сущность в которую будут выгружены данные пользователя.<br><br>Если пользователь уже имеется в Контактах CRM, тогда он не будет выгружен. Проверка производится по e-mail.',

    'ArtamonovBitrix24ExportRegisteredUsersResponsible' => 'Ответственный',
    'ArtamonovBitrix24ExportRegisteredUsersTypeContact' => 'Тип контакта',
    'ArtamonovBitrix24ExportRegisteredUsersSource' => 'Источники',
    'ArtamonovBitrix24ExportRegisteredUsersExport' => 'Участвует в экспорте',
    'ArtamonovBitrix24ExportRegisteredUsersOpened' => 'Доступен для всех',

    'ArtamonovBitrix24UsersExportPeriod' => 'Периодичность экспорта',
    'ArtamonovBitrix24UsersExportPeriodHint' => 'Указывается в секундах. Например:<br>- 1 час = 3600 секунд<br>- 1 сутки = 86400 секунд<br>- и другие значения<br><br>Если указан 0 или пусто, тогда экспорт будет производиться сразу же после добавления пользователя. В ином случае, будет добавлен Агент, который будет экспортировать пользователей в CRM с заданной периодичностью.<br><br>Журнал работы Агента: <a href="/bitrix/admin/event_log.php?PAGEN_1=1&SIZEN_1=20&lang=' . LANG . '&set_filter=Y&adm_filter_applied=0&find_type=audit_type_id&find_item_id=UsersExport">перейти</a>',

    // Widget
    'ArtamonovBitrix24PageTitleWidget' => 'Кастомизация виджета',
    'ArtamonovBitrix24TabSettingsTitle' => 'Настройки',
    'ArtamonovBitrix24TabSettingsDescription' => 'Настройки виджета',
    'ArtamonovBitrix24TabWhatsAppTitle' => 'WhatsApp',
    'ArtamonovBitrix24TabWhatsAppDescription' => 'Настройки канала WhatsApp',

    'ArtamonovBitrix24UseCustomizationWidget' => 'Активировать кастомизацию',
    'ArtamonovBitrix24UseCustomizationWidgetHint' => 'Активировать режим изменения виджета Битрикс24',
    'ArtamonovBitrix24UseChannelWhatsApp' => 'Активировать канал',
    'ArtamonovBitrix24UseChannelWhatsAppHint' => 'Добавить WhatsApp-канал в виджет.<br>Внимание! Данный канал не подключен к Открытой линии, то есть общение через данный канал не будет зафиксировано в CRM Битрикс24.',
    'ArtamonovBitrix24WhatsAppAccount' => 'Номер телефона',
    'ArtamonovBitrix24WhatsAppTitle' => 'Заголовок канала',
    'ArtamonovBitrix24WhatsAppTitleHint' => 'Отображается при наведении на иконку канала',
    'ArtamonovBitrix24WhatsAppSort' => 'Сортировка',
    'ArtamonovBitrix24WhatsAppSortHint' => 'Влияет на положение в списке каналов',
];
