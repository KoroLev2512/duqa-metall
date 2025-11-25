<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\HttpApplication;

use Webcomp\Market\Settings;
use Webcomp\Market\Tools;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $GLOBALS['APPLICATION']->SetTitle(Loc::getMessage('WEBCOMP_CONTROL_SETTINGS_TITLE'));
}

$module_id = "webcomp.market";

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");
Loc::loadMessages(__FILE__);

Loader::IncludeModule($module_id);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$superAdmin = isset($request["superAdmin"]) && $request["superAdmin"] === "Y";

//Формируем массив вкладок, каждый элемент массива это новая вкладка
$arTabs = include_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/webcomp.market/include/arSettings.php";

// Сохранение в базу
if ($request->isPost() && $request["update"] && check_bitrix_sessid()) {

    foreach ($arTabs as $arTab) {
        foreach ($arTab["TABS"] as $tab) {
            foreach ($tab["OPTIONS"] as $arOption) {

                // Если не массив то пропускаем
                if (!is_array($arOption))
                    continue;

                // Типы, которые не надо обрабатывать
                if (in_array($arOption["TYPE"], ["HEADING", "INFO"]))
                    continue;

                $optionName = $arOption["NAME"];
                $optionValue = $request->getPost($optionName) ?? "N";


                // Множественные типы удаляем пустые значения
                if (in_array($arOption["TYPE"], ["MULTIPLE_STRING", "MULTIPLE_SELECT"])) {
                    $optionValue = array_diff($optionValue, ['']);
                }

                if ($arOption["TYPE"] == "FILE") {
                    $optionValue = Settings::SaveFileField($optionName);
                }

                Option::set($module_id, $optionName, is_array($optionValue) ? serialize($optionValue) : $optionValue);
            }
        }
    }
}

// Визуальный вывод табов
$tabControl = new CAdminTabControl("tabControl", $arTabs);
?>

<? $tabControl->Begin() ?>

<form class="webcomp-settings__form"
      enctype="multipart/form-data"
      method="post"
      action="<?= $APPLICATION->GetCurPage() ?>"
      name="webcomp_market_settings">

    <? $tabControl->BeginNextTab() ?>

    <? foreach ($arTabs as $arTab): ?>
        <div class="webcomp-settings__wrap">

            <? if($arTab["TABS"]): ?>
                <? Settings::DrawAdminOptions($arTab["TABS"]); ?>
            <? endif ?>

            <? $tabControl->Buttons() ?>

            <input type="submit" name="update" value="<?=Loc::getMessage("WEBCOMP_MARKET_SAVE_BTN")?>">
            <input type="reset" name="reset" value="<?=Loc::getMessage("WEBCOMP_MARKET_RESET_BTN")?>">
            <?= bitrix_sessid_post() ?>

        </div>
    <? endforeach ?>



</form>
<? $tabControl->End() ?>
    <? /*
        <form class="webcomp-settings__form" enctype="multipart/form-data" method="post" action="<?= $APPLICATION->GetCurPage() ?>"
              name="webcomp_market_settings">

            <? foreach ($arTabs as $arTab): ?>
                <? if ($arTab["OPTIONS"]): ?>
                    <? $tabControl->BeginNextTab() ?>
                    <?
                    Settings::DrawAdminOptions($arTab["OPTIONS"]);
                    ?>

                <? endif ?>
            <? endforeach ?>

            <? $tabControl->BeginNextTab() ?>

            <? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php") ?>

            <? $tabControl->Buttons() ?>

            <input type="submit" name="update" value="<?=Loc::getMessage("WEBCOMP_MARKET_SAVE_BTN")?>">
            <input type="reset" name="reset" value="<?=Loc::getMessage("WEBCOMP_MARKET_RESET_BTN")?>">
            <?= bitrix_sessid_post() ?>

        </form>

    */?>


<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
?>
