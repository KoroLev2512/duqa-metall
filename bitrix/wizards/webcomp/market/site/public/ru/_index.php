<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "#SITE_DESCRIPTION#");
$APPLICATION->SetPageProperty("keywords", "#SITE_KEYWORDS#");
$APPLICATION->SetTitle("#COMPANY_NAME#");
include(__DIR__."/index_blocks.php");
?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>