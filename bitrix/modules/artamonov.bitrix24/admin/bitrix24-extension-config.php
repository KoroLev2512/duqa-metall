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
use \Artamonov\Bitrix24\Integration;
use \Artamonov\Bitrix24\Request;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

Loader::IncludeModule('artamonov.bitrix24');

$GLOBALS['APPLICATION']->SetTitle(Loc::getMessage('ArtamonovBitrix24PageTitleConfig'));

$pageId = basename(__FILE__, '.php');

$tabControl = new CAdminTabControl('tabControl', [
    ['DIV' => 'tab-1', 'TAB' => Loc::getMessage('ArtamonovBitrix24TabIntegrationTitle'), 'TITLE' => Loc::getMessage('ArtamonovBitrix24TabIntegrationDescription')],
]);

$portal = Request::getInstance()->send()->get('app.info');
$user = Request::getInstance()->send()->get('profile');

$connected = Integration::getInstance()->configured() && $portal && $user ? true : false;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

Config::getInstance()->form($pageId);

if (!in_array('curl', get_loaded_extensions())) {
    Helper::getInstance()->errorMessage(Loc::getMessage('ArtamonovBitrix24CurlNotInstalled'));
}

if (!$connected) {
    Helper::getInstance()->message(Loc::getMessage('ArtamonovBitrix24Need'));
}

$tabControl->Begin()
?>
    <form method="POST" name="<?= $pageId ?>" action="<?= $APPLICATION->GetCurUri() ?>">
        <?= bitrix_sessid_post() ?>
        <? $tabControl->BeginNextTab() ?>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24PortalAddress') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('text', Settings::getInstance()->get('config')['prefix'] . 'portalAddress', Config::getInstance()->get('portalAddress'), false, false, false, 'size="30"') ?>
            <td>
        </tr>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24IntegrationCode') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('text', Settings::getInstance()->get('config')['prefix'] . 'integrationCode', Config::getInstance()->get('integrationCode'), false, false, false, 'size="30"') ?>
                <? Helper::getInstance()->hint(Loc::getMessage('ArtamonovBitrix24IntegrationCodeHint')) ?>
            <td>
        </tr>

        <tr>
            <td width="45%" valign="middle"><?= Loc::getMessage('ArtamonovBitrix24IntegrationUserId') ?>
            <td>
            <td width="55%" valign="middle">
                <?= InputType('text', Settings::getInstance()->get('config')['prefix'] . 'integrationUserId', Config::getInstance()->get('integrationUserId'), false, false, false, 'size="30"') ?>
                <? Helper::getInstance()->hint(Loc::getMessage('ArtamonovBitrix24IntegrationUserIdHint')) ?>
            <td>
        </tr>

        <tr>
            <td colspan="4" class="block-note">
                <?= BeginNote() ?>
                <? if ($connected): ?>
                    <ul class="info">
                        <li><?= Loc::getMessage('ArtamonovBitrix24License') . Loc::getMessage('ArtamonovBitrix24License-' . $portal['LICENSE']) ?></li>
                        <li><?= trim(Loc::getMessage('ArtamonovBitrix24User') . $user['LAST_NAME'] . ' ' . $user['NAME']) ?></li>
                    </ul>
                    <? if ($portal['SCOPE']): ?>
                        <ul class="scope">
                            <li><?= Loc::getMessage('ArtamonovBitrix24Scope') ?></li>
                            <? foreach ($portal['SCOPE'] as $scope): ?>
                                <li><?= $scope ?></li>
                            <? endforeach ?>
                        </ul>
                    <? endif ?>
                <? else: ?>
                    <?= Loc::getMessage('ArtamonovBitrix24NoConnection') ?>
                <? endif ?>
                <?= EndNote() ?>
            </td>
        </tr>

        <tr>
            <td colspan="4" valign="middle">
                <? $tabControl->Buttons() ?>
                <?= InputType('submit', 'save', Loc::getMessage('ArtamonovBitrix24ButtonSave'), false, false, false, 'class="adm-btn-save"') ?>
                <?= InputType('submit', 'restore', Loc::getMessage('ArtamonovBitrix24ButtonRestore'), false) ?>
            </td>
        </tr>
    </form>

    <style>
        .block-note {
            text-align: center;
            padding-top: 10px;
        }

        .block-note ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .block-note .adm-info-message {
            text-align: left;
        }

        .block-note .adm-info-message .info {
            border-bottom: 1px solid rgba(205, 192, 146, 0.37);
            padding-bottom: 12px;
            margin-bottom: 12px;
        }

        .block-note .adm-info-message .scope li {
            margin-bottom: 2px;
        }

        .block-note .adm-info-message .scope li:first-child {
            margin-bottom: 10px;
        }

        .block-note .adm-info-message .scope li:last-child {
            margin-bottom: 0;
        }

        form[name="bitrix24-extension-config"] .block-note .adm-info-message {
            min-width: 350px;
        }
    </style>
<?php
$tabControl->End();
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
