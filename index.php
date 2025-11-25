<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Description");
$APPLICATION->SetPageProperty("keywords", "keywords");
$APPLICATION->SetTitle("DUQAMETALL.RU");
include(__DIR__."/index_blocks.php");
?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>