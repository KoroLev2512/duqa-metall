<?
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arServices = [
    "main" => [
        "NAME"   => GetMessage("SERVICE_MAIN_SETTINGS"),
        "STAGES" => [
            "files.php", // Copy bitrix files
            "template.php", // Install template
            "theme.php", // Install theme
            "menu.php", // Install menu
            "settings.php",
            "mailTypes.php",
            "mailTemplates.php",
        ],
    ],

    "iblock" => [
        "NAME"   => GetMessage("SERVICE_IBLOCK"),
        "STAGES" => [
            "types.php", //IBlock types
            "catalog.php",
            "forms.php",
            "content.php",
            "marketing.php",
            "hightloadBlocks.php",
        ],
    ],

];
?>