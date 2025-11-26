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
use \Artamonov\Bitrix24\Settings;
use \Artamonov\Bitrix24\Config;
use \Artamonov\Bitrix24\Helper;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

Loader::IncludeModule('artamonov.bitrix24');

$GLOBALS['APPLICATION']->SetTitle(Loc::getMessage('ArtamonovBitrix24PageTitleWidget'));

$pageId = basename(__FILE__, '.php');

$tabControl = new CAdminTabControl('tabControl', [
    ['DIV' => 'tab-1', 'TAB' => Loc::getMessage('ArtamonovBitrix24TabSettingsTitle'), 'TITLE' => Loc::getMessage('ArtamonovBitrix24TabSettingsDescription')],
    ['DIV' => 'tab-2', 'TAB' => Loc::getMessage('ArtamonovBitrix24TabWhatsAppTitle'), 'TITLE' => Loc::getMessage('ArtamonovBitrix24TabWhatsAppDescription')],
]);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

Config::getInstance()->form($pageId);

$tabControl->Begin()
?>
    <form method="POST" name="<?= $pageId ?>" action="<?= $APPLICATION->GetCurUri() ?>">
        <?= bitrix_sessid_post() ?>
        <? $tabControl->BeginNextTab() ?>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24UseCustomizationWidget') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('checkbox', Settings::getInstance()->get('config')['prefix'] . 'useCustomizationWidget', true, Config::getInstance()->get('useCustomizationWidget')) ?>
                <? Helper::getInstance()->hint(Loc::getMessage('ArtamonovBitrix24UseCustomizationWidgetHint')) ?>
            <td>
        </tr>

        <? $tabControl->BeginNextTab() ?>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24UseChannelWhatsApp') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('checkbox', Settings::getInstance()->get('config')['prefix'] . 'useChannelWhatsApp', true, Config::getInstance()->get('useChannelWhatsApp')) ?>
                <? Helper::getInstance()->hint(Loc::getMessage('ArtamonovBitrix24UseChannelWhatsAppHint')) ?>
            <td>
        </tr>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24WhatsAppAccount') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('text', Settings::getInstance()->get('config')['prefix'] . 'whatsAppAccount', Config::getInstance()->get('whatsAppAccount'), false, false, false, 'placeholder="71112223344"') ?>
            <td>
        </tr>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24WhatsAppTitle') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('text', Settings::getInstance()->get('config')['prefix'] . 'whatsAppTitle', Config::getInstance()->get('whatsAppTitle'), false) ?>
                <? Helper::getInstance()->hint(Loc::getMessage('ArtamonovBitrix24WhatsAppTitleHint')) ?>
            <td>
        </tr>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24WhatsAppSort') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('text', Settings::getInstance()->get('config')['prefix'] . 'whatsAppSort', Config::getInstance()->get('whatsAppSort'), false) ?>
                <? Helper::getInstance()->hint(Loc::getMessage('ArtamonovBitrix24WhatsAppSortHint')) ?>
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
