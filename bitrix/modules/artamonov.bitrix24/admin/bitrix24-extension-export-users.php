<?php
/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

/**
 * @var CMain $APPLICATION
 */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\EventManager;
use \Artamonov\Bitrix24\Settings;
use \Artamonov\Bitrix24\Config;
use \Artamonov\Bitrix24\Helper;
use \Artamonov\Bitrix24\Integration;
use \Artamonov\Bitrix24\Request;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

Loader::IncludeModule('artamonov.bitrix24');

$GLOBALS['APPLICATION']->SetTitle(Loc::getMessage('ArtamonovBitrix24PageTitleUsers'));

$eventManager = EventManager::getInstance();
$pageId = basename(__FILE__, '.php');

$tabControl = new CAdminTabControl('tabControl', [
    ['DIV' => 'tab-1', 'TAB' => Loc::getMessage('ArtamonovBitrix24TabExportUsersTitleContacts'), 'TITLE' => Loc::getMessage('ArtamonovBitrix24TabExportUsersDescription')],
]);

$eventManager->unRegisterEventHandler('main', 'OnAfterUserRegister', 'artamonov.bitrix24', '\\Artamonov\\Bitrix24\\Event\\Main', 'OnAfterUserRegister');
$eventManager->registerEventHandler('main', 'OnAfterUserAdd', 'artamonov.bitrix24', '\\Artamonov\\Bitrix24\\Event\\Main', 'OnAfterUserAdd');

$data = [];

if (Config::getInstance()->get('useExportRegisteredUsers') || $_POST[Settings::getInstance()->get('config')['prefix'] . 'useExportRegisteredUsers']) {
    $data = Request::getInstance()->send()->post('batch', [
        'cmd' => [
            'users' => 'user.get?ACTIVE=1&sort=LAST_NAME&order=ASC',
            'typeContact' => 'crm.status.list?filter[ENTITY_ID]=CONTACT_TYPE',
            'source' => 'crm.status.list?filter[ENTITY_ID]=SOURCE'
        ]
    ]);
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

Config::getInstance()->form($pageId);

if (!in_array('curl', get_loaded_extensions())) {
    Helper::getInstance()->errorMessage(Loc::getMessage('ArtamonovBitrix24CurlNotInstalled'));
}

if (!Integration::getInstance()->configured()) {
    Helper::getInstance()->message(Loc::getMessage('ArtamonovBitrix24NotIntegrated'));
}

if ($data['result_error']) {
    Helper::getInstance()->errorMessage(Loc::getMessage('ArtamonovBitrix24NeedAccessToPortal'));
}

$tabControl->Begin()
?>
    <form method="POST" name="<?= $pageId ?>" action="<?= $APPLICATION->GetCurUri() ?>">
        <?= bitrix_sessid_post() ?>
        <? $tabControl->BeginNextTab() ?>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24UseExportRegisteredUsers') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('checkbox', Settings::getInstance()->get('config')['prefix'] . 'useExportRegisteredUsers', true, Config::getInstance()->get('useExportRegisteredUsers'), false, false, Integration::getInstance()->configured() ? '' : 'disabled') ?>
                <? Helper::getInstance()->hint(Loc::getMessage('ArtamonovBitrix24UseExportRegisteredUsersHint')) ?>
            <td>
        </tr>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24UsersExportType') ?>
            <td>
            <td width="55%" valign="middle">
                <select name="<?= Settings::getInstance()->get('config')['prefix'] ?>usersExportType" <?= Config::getInstance()->get('useExportRegisteredUsers') ? '' : 'disabled' ?>>
                    <option value="">...</option>
                    <option value="contact" <? if (Config::getInstance()->get('usersExportType') === 'contact') echo 'selected' ?>>
                        <?= Loc::getMessage('ArtamonovBitrix24Contacts') ?>
                    </option>
                    <option value="lead" <? if (Config::getInstance()->get('usersExportType') === 'lead') echo 'selected' ?>>
                        <?= Loc::getMessage('ArtamonovBitrix24Leads') ?>
                    </option>
                </select>
                <? Helper::getInstance()->hint(Loc::getMessage('ArtamonovBitrix24UsersExportTypeHint')) ?>
            <td>
        </tr>

        <tr>
            <td content="4">&nbsp;</td>
        </tr>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24ExportRegisteredUsersResponsible') ?>
            <td>
            <td width="55%" valign="middle">
                <select name="<?= Settings::getInstance()->get('config')['prefix'] ?>exportRegisteredUsersResponsibleId" <?= $data['result']['users'] && Config::getInstance()->get('useExportRegisteredUsers') && Config::getInstance()->get('usersExportType') ? '' : 'disabled' ?>>
                    <option value="">...</option>
                    <? foreach ($data['result']['users'] as $item): ?>
                        <? if ($item['LAST_NAME'] && $item['NAME']): ?>
                            <option value="<?= $item['ID'] ?>" <? if (Config::getInstance()->get('exportRegisteredUsersResponsibleId') === $item['ID']) echo 'selected' ?>><?= trim($item['LAST_NAME'] . ' ' . $item['NAME'] . ' ' . $item['SECOND_NAME']) ?><? if ($item['WORK_POSITION']) echo ': ' . $item['WORK_POSITION'] ?></option>
                        <? endif ?>
                    <? endforeach ?>
                </select>
            <td>
        </tr>

        <? if (Config::getInstance()->get('usersExportType') === 'contact'): ?>
            <tr>
                <td width="45%"
                    valign="middle"><?= Loc::getMessage('ArtamonovBitrix24ExportRegisteredUsersTypeContact') ?>
                <td>
                <td width="55%" valign="middle">
                    <select name="<?= Settings::getInstance()->get('config')['prefix'] ?>exportRegisteredUsersTypeContactId" <?= $data['result']['typeContact'] && Config::getInstance()->get('useExportRegisteredUsers') && Config::getInstance()->get('usersExportType') ? '' : 'disabled' ?>>
                        <option value="">...</option>
                        <? foreach ($data['result']['typeContact'] as $item): ?>
                            <option value="<?= $item['STATUS_ID'] ?>" <? if (Config::getInstance()->get('exportRegisteredUsersTypeContactId') === $item['STATUS_ID']) echo 'selected' ?>><?= trim($item['NAME']) ?></option>
                        <? endforeach ?>
                    </select>
                <td>
            </tr>
        <? endif ?>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24ExportRegisteredUsersSource') ?>
            <td>
            <td width="55%" valign="middle">
                <select name="<?= Settings::getInstance()->get('config')['prefix'] ?>exportRegisteredUsersSourceId" <?= $data['result']['source'] && Config::getInstance()->get('useExportRegisteredUsers') && Config::getInstance()->get('usersExportType') ? '' : 'disabled' ?>>
                    <option value="">...</option>
                    <? foreach ($data['result']['source'] as $item): ?>
                        <option value="<?= $item['STATUS_ID'] ?>" <? if (Config::getInstance()->get('exportRegisteredUsersSourceId') === $item['STATUS_ID']) echo 'selected' ?>><?= trim($item['NAME']) ?></option>
                    <? endforeach ?>
                </select>
            <td>
        </tr>

        <? if (Config::getInstance()->get('usersExportType') === 'contact'): ?>
            <tr>
                <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24ExportRegisteredUsersExport') ?>
                <td>
                <td width="55%" valign="middle">
                    <?= InputType('checkbox', Settings::getInstance()->get('config')['prefix'] . 'exportRegisteredUsersExport', true, Config::getInstance()->get('exportRegisteredUsersExport'), false, false, Integration::getInstance()->configured() && Config::getInstance()->get('useExportRegisteredUsers') && Config::getInstance()->get('usersExportType') ? '' : 'disabled') ?>
                <td>
            </tr>
        <? endif ?>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24ExportRegisteredUsersOpened') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('checkbox', Settings::getInstance()->get('config')['prefix'] . 'exportRegisteredUsersOpened', true, Config::getInstance()->get('exportRegisteredUsersOpened'), false, false, Integration::getInstance()->configured() && Config::getInstance()->get('useExportRegisteredUsers') && Config::getInstance()->get('usersExportType') ? '' : 'disabled') ?>
            <td>
        </tr>

        <tr>
            <td content="4">&nbsp;</td>
        </tr>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24UsersExportPeriod') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('text', Settings::getInstance()->get('config')['prefix'] . 'usersExportPeriod', Config::getInstance()->get('usersExportPeriod'), false, '', '', Config::getInstance()->get('useExportRegisteredUsers') ? '' : 'disabled') ?>
                <? Helper::getInstance()->hint(Loc::getMessage('ArtamonovBitrix24UsersExportPeriodHint')) ?>
            <td>
        </tr>

        <tr>
            <td colspan="4" valign="middle">
                <? $tabControl->Buttons() ?>
                <?= InputType('submit', 'save', Loc::getMessage('ArtamonovBitrix24ButtonSave'), false, false, false, 'class="adm-btn-save"') ?>
                <?= InputType('submit', 'restore', Loc::getMessage('ArtamonovBitrix24ButtonRestore'), false) ?>
            </td>
        </tr>
    </form>

<?php
$tabControl->End();
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
