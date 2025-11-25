<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Bitrix\Main\Loader::IncludeModule('artamonov.bitrix24');

$settings = require __DIR__ . '/../settings.php';

if (!$settings['module']['id'] || Loader::includeSharewareModule($settings['module']['id']) === Loader::MODULE_DEMO_EXPIRED) {
    return [];
}

return [
    [
        'parent_menu' => 'global_menu_services',
        'text' => $settings['module']['name'],
        'section' => $settings['module']['id'],
        'module_id' => $settings['module']['id'],
        'items_id' => 'menu_' . $settings['module']['id'],
        'icon' => 'landing_menu_icon',
        'page_icon' => 'landing_menu_icon',
        'sort' => 1,
        'items' => [

            [
                'items_id' => 'menu_' . $settings['module']['id'] . '_widget',
                'icon' => 'forum_menu_icon',
                'page_icon' => 'forum_menu_icon',
                'text' => Loc::getMessage('ArtamonovBitrix24MenuItemWidget'),
                'url' => 'bitrix24-extension-widget.php?lang=' . LANG
            ],

            [
                'items_id' => 'menu_' . $settings['module']['id'] . '_users',
                'icon' => 'sonet_menu_icon',
                'page_icon' => 'sonet_menu_icon',
                'text' => Loc::getMessage('ArtamonovBitrix24MenuItemUser'),
                'url' => 'bitrix24-extension-export-users.php?lang=' . LANG
            ],

            [
                'items_id' => 'menu_' . $settings['module']['id'] . '_settings',
                'icon' => 'sys_menu_icon',
                'page_icon' => 'sys_menu_icon',
                'text' => Loc::getMessage('ArtamonovBitrix24MenuItemSettings'),
                'url' => 'bitrix24-extension-config.php?lang=' . LANG
            ]
        ]
    ]
];
