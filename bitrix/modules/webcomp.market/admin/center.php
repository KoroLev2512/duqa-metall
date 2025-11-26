<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\HttpApplication;

use Webcomp\Market\Settings;
use Webcomp\Market\Tools;

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $GLOBALS['APPLICATION']->SetTitle(Loc::getMessage('WEBCOMP_CONTROL_CENTER_TITLE'));
}

$module_id = "webcomp.market";
$defaultOptions = Option::getDefaults($module_id);

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
Loc::loadMessages(__FILE__);

Loader::IncludeModule($module_id);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$superAdmin = (isset($request["superAdmin"]) && $request["superAdmin"] === "Y") ? true : false;
?>
    <div id="webcomp_admin_area" style="height: 852px;" class="webcomp-admin-ready">
        <iframe class="webcomp_admin_frame" src="https://web-comp.ru/company/"></iframe>
    </div>

<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');
