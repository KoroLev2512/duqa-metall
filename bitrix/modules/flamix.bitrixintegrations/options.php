<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Flamix\BitrixIntegrations\Option;
use Flamix\BitrixIntegrations\Order\Status;

/**
 * @global $APPLICATION
 * @global $USER
 */

Loc::loadMessages(__FILE__);
Loader::includeModule('flamix.bitrixintegrations');

if (!$USER->IsAdmin()) {
    LocalRedirect('/bitrix/admin/');
}

$arStatuses = Status::getList();

$request = Application::getInstance()->getContext()->getRequest();
if ($request->getPost('saveModule') == 'Y') {
    $arFields = [
        'bitrix24_domain',
        'bitrix24_api_key',
        'notifications_email',
        'form_send_enabled',
        'order_send_enabled',
        'order_product_find_by',
        'order_status_payment',
        'order_status_delivery',
        'match_statuses_b24',
        'order_status_cancel',
    ];

    foreach ($arFields as $sCode) {
        if($sCode === 'bitrix24_domain')
            $val = Option::parseDomain($request->getPost($sCode));
        else
            $val = $request->getPost($sCode);

        Option::set($sCode, $val);
    }

    unset($val);
}

$aTabs = [
    [
        'DIV' => 'general',
        'TAB' => Loc::getMessage('FX_BI_GENERAL_TAB'),
        'TITLE' => Loc::getMessage('FX_BI_GENERAL_TTITLE')
    ],
];
if (Option::isModule('form')) {
    $aTabs[] = [
        'DIV' => 'form',
        'TAB' => Loc::getMessage('FX_BI_FORM_TAB'),
        'TITLE' => Loc::getMessage('FX_BI_FORM_TTITLE')
    ];
}
if (Option::isModule('sale')) {
    $aTabs[] = [
        'DIV' => 'order',
        'TAB' => Loc::getMessage('FX_BI_ORDER_TAB'),
        'TITLE' => Loc::getMessage('FX_BI_ORDER_TTITLE')
    ];
}
$tabControl = new CAdminTabControl('tabControl', $aTabs, true, true);
?>
<form method="post">
    <input type="hidden" name="saveModule" value="Y">
    <?php
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    ?>
    <tr>
        <td width="40%" class="adm-detail-content-cell-l">
            <?php ShowJSHint(Loc::getMessage('FX_BI_DOMAIN_HINT')); ?>
            <?=Loc::getMessage('FX_BI_DOMAIN')?>
        </td>
        <td width="60%" class="adm-detail-content-cell-r">
            <input type="text" value="<?=Option::get('bitrix24_domain')?>" name="bitrix24_domain">
        </td>
    </tr>
    <tr>
        <td width="40%" class="adm-detail-content-cell-l">
            <?php ShowJSHint(Loc::getMessage('FX_BI_API_KEY_HINT')); ?>
            <?=Loc::getMessage('FX_BI_API_KEY')?>
        </td>
        <td width="60%" class="adm-detail-content-cell-r">
            <input type="text" value="<?=Option::get('bitrix24_api_key')?>" name="bitrix24_api_key">
        </td>
    </tr>

    <tr class="heading">
        <td colspan="2"><b><?=Loc::getMessage('FX_BI_CHECK_REQUIRES')?></b></td>
    </tr>
    <?php
    $arChecks = [
        [
            'name' => Loc::getMessage('FX_BI_MODULE_SETTINGS'),
            'result' => Option::getStatus(),
        ],
        [
            'name' => Loc::getMessage('FX_BI_CURL_LIBRARY'),
            'result' => Option::getCurlStatus(),
        ],
        [
            'name' => Loc::getMessage('FX_BI_PHP_VERSION'),
            'result' => Option::getPhpStatus(),
        ],
    ];
    foreach ($arChecks as $arCheck) {
    ?>
        <tr>
            <td width="40%" class="adm-detail-content-cell-l">
                <?=$arCheck['name']?>
            </td>
            <td width="60%" class="adm-detail-content-cell-r">
                <?php
                $sColor = ($arCheck['result']['status'] == 'ok' ? '#46b450' : '#dc3232');
                $sText = $arCheck['result']['mess'];
                ?>
                <span style="color: <?=$sColor?>;"><?=$sText?></span>
            </td>
        </tr>
    <?php } ?>

    <tr class="heading">
        <td colspan="2"><b><?=Loc::getMessage('FX_BI_NOTIFICATIONS')?></b></td>
    </tr>
    <tr>
        <td width="40%" class="adm-detail-content-cell-l">
            <?php ShowJSHint(Loc::getMessage('FX_BI_EMAIL_HINT')); ?>
            <?=Loc::getMessage('FX_BI_EMAIL')?>
        </td>
        <td width="60%" class="adm-detail-content-cell-r">
            <input type="text" value="<?=Option::get('notifications_email')?>" name="notifications_email">
        </td>
    </tr>

    <?php if (Option::isModule('form')) { ?>
        <?php $tabControl->BeginNextTab(); ?>
            <tr>
                <td width="40%" class="adm-detail-content-cell-l">
                    <?php ShowJSHint(Loc::getMessage('FX_BI_WEBFORM_ENABLED_HINT')); ?>
                    <?=Loc::getMessage('FX_BI_WEBFORM_ENABLED')?>
                </td>
                <td width="60%" class="adm-detail-content-cell-r">
                    <input type="hidden" name="form_send_enabled" value="N">
                    <input type="checkbox" name="form_send_enabled" value="Y" id="form_send_enabled" class="adm-designed-checkbox"<?=(Option::get('form_send_enabled') == 'Y' ? ' checked' : '')?>>
                    <label class="adm-designed-checkbox-label" for="form_send_enabled"></label>
                </td>
            </tr>
    <?php } ?>

    <?php if (Option::isModule('sale')) { ?>
        <?php $tabControl->BeginNextTab(); ?>
        <tr>
            <td width="40%" class="adm-detail-content-cell-l">
                <?php ShowJSHint(Loc::getMessage('FX_BI_ORDER_ENABLED_HINT')); ?>
                <?=Loc::getMessage('FX_BI_ORDER_ENABLED')?>
            </td>
            <td width="60%" class="adm-detail-content-cell-r">
                <input type="hidden" name="order_send_enabled" value="N">
                <input type="checkbox" name="order_send_enabled" value="Y" id="order_send_enabled" class="adm-designed-checkbox"<?=(Option::get('order_send_enabled') == 'Y' ? ' checked' : '')?>>
                <label class="adm-designed-checkbox-label" for="order_send_enabled"></label>
            </td>
        </tr>
        <tr>
            <td width="40%" class="adm-detail-content-cell-l">
                <?php ShowJSHint(Loc::getMessage('FX_BI_PRODUCTS_SEARCH_HINT')); ?>
                <?=Loc::getMessage('FX_BI_PRODUCTS_SEARCH')?>
            </td>
            <td width="60%" class="adm-detail-content-cell-r">
                <?php
                $sFindBy = Option::get('order_product_find_by');
                if (!$sFindBy) {
                    $sFindBy = 'DISABLE';
                }
                
                $arFields = [
                    'DISABLE' => Loc::getMessage('FX_BI_FIND_BY_DISABLE'),
                    'ID' => 'ID',
                    'CATALOG_ID' => Loc::getMessage('FX_BI_FIND_BY_CATALOG_ID'),
                    'PRICE' => Loc::getMessage('FX_BI_FIND_BY_PRICE'),
                    'CURRENCY_ID' => Loc::getMessage('FX_BI_FIND_BY_CURRENCY_ID'),
                    'NAME' => Loc::getMessage('FX_BI_FIND_BY_NAME'),
                    'CODE' => Loc::getMessage('FX_BI_FIND_BY_CODE'),
                    'DESCRIPTION' => Loc::getMessage('FX_BI_FIND_BY_DESCRIPTION'),
                    'SECTION_ID' => Loc::getMessage('FX_BI_FIND_BY_SECTION_ID'),
                    'MEASURE' => Loc::getMessage('FX_BI_FIND_BY_MEASURE'),
                    'XML_ID' => Loc::getMessage('FX_BI_FIND_BY_XML_ID'),
                ];
                ?>
                <select name="order_product_find_by" class="typeselect">
                    <?php foreach ($arFields as $sCode => $sName) { ?>
                        <option value="<?=$sCode?>"<?=($sCode == $sFindBy ? ' selected' : '')?>><?=$sName?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <?php if ($arStatuses) { ?>
            <tr>
                <td width="50%" class="adm-detail-content-cell-l">
                    <?=Loc::getMessage('FX_BI_STATUS_FOR_PAYMENT')?>
                </td>
                <td width="50%" class="adm-detail-content-cell-r">
                    <select name="order_status_payment[]" class="typeselect" multiple>
                        <?php foreach ($arStatuses as $keyPayment => $arStatus):
                            $selectedPayment = Status::isPay($arStatus["STATUS_ID"]) ? 'selected' : '';
                        ?>
                            <option value="<?= $arStatus["STATUS_ID"]; ?>" <?= $selectedPayment; ?>><?= $arStatus["NAME"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="50%" class="adm-detail-content-cell-l">
                    <?=Loc::getMessage('FX_BI_STATUS_FOR_DELIVERY')?>
                </td>
                <td width="50%" class="adm-detail-content-cell-r">
                    <select name="order_status_delivery[]" class="typeselect" multiple>
                        <?php foreach ($arStatuses as $keyDelivery => $arStatus):
                            $selectedDelivery = Status::isDelivery($arStatus["STATUS_ID"]) ? 'selected' : '';
                        ?>
                            <option value="<?= $arStatus["STATUS_ID"]; ?>" <?= $selectedDelivery; ?>><?= $arStatus["NAME"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="50%" class="adm-detail-content-cell-l">
                    <?=Loc::getMessage('FX_BI_STATUS_FOR_CANCEL')?>
                </td>
                <td width="50%" class="adm-detail-content-cell-r">
                    <select name="order_status_cancel[]" class="typeselect" multiple>
                        <?php foreach ($arStatuses as $keyDelivery => $arStatus):
                            $selectedCancel = Status::isCancel($arStatus["STATUS_ID"]) ? 'selected' : '';
                        ?>
                            <option value="<?= $arStatus["STATUS_ID"]; ?>" <?= $selectedCancel; ?>><?= $arStatus["NAME"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        <?php } ?>
    <?php } ?>

    <?php
    $tabControl->Buttons([
        'btnSave' => true,
        'btnApply' => true,
        'btnCancel' => true,
        'back_url' => 'cancel-link'
    ]);
    $tabControl->End();
    ?>
</form>