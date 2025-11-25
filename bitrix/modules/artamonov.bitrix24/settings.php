<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

use Bitrix\Main\Localization\Loc;

return [
    'module' => [
        'id' => Loc::getMessage('ArtamonovBitrix24ModuleId'),
        'name' => Loc::getMessage('ArtamonovBitrix24ModuleName'),
        'description' => Loc::getMessage('ArtamonovBitrix24ModuleDescription')
    ],
    'message' => [
        'install' => Loc::getMessage('ArtamonovBitrix24MessageInstall'),
        'uninstall' => Loc::getMessage('ArtamonovBitrix24MessageUninstall')
    ],
    'config' => [
        'prefix' => 'parameter:'
    ]
];
