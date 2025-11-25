<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();// D7
use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    "NAME"        => Loc::getMessage("WC_COMPONENT_FOOTER_NAME"),
    "DESCRIPTION" => Loc::getMessage("WC_COMPONENT_FOOTER_DESC"),
    "SORT"        => 10,
    "CACHE_PATH"  => "Y",
    "PATH"        => [
        "ID"    => "WebComp",
        "CHILD" => [
            "ID"   => "webcomp_footer",
            "NAME" => Loc::getMessage("WC_COMPONENT_FOOTER_NAME_PATH_NAME"),
            "SORT" => 10,
        ],
    ],
];

