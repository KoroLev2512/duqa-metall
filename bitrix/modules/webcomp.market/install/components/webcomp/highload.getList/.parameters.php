<?
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arCurrentValues */

#<editor-fold desc="Namespaces">
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;
use Bitrix\Highloadblock as HL;

#Namespaces </editor-fold>

Loader::includeModule("highloadblock");
global $USER_FIELD_MANAGER;
#<editor-fold desc="HLBlock">
/** Выбираем все HL блоки под текущую локализацию*/
$HLBlockTypes = [];
$HLBlockList = HL\HighloadBlockLangTable::getList([
        'filter' => ['=LID' => LANG],
    ]
)->fetchAll();
foreach ($HLBlockList as $HLBlock) {
    $HLBlockTypes[$HLBlock['ID']] = $HLBlock['NAME'];
}
#HLBlock </editor-fold>

$userFieldsSelect = [];
$sortFieldList = [];
if ( ! empty($arCurrentValues['HLBLOCK_ID'])) {
    /** Получаем поля для возможности выборки в компоненте*/
    $userFields = $USER_FIELD_MANAGER->GetUserFields('HLBLOCK_'
        .$arCurrentValues['HLBLOCK_ID'], 0, $GLOBALS["lang"]);
    /** Поля для сортировки*/
    $userFieldsSelect["ID"] = $sortFieldList["ID"] = "ID"; //
    foreach ($userFields as $k => $fieldArr) {
        $userFieldsSelect[$k] = $fieldArr['LIST_COLUMN_LABEL']." ["
            .$fieldArr['FIELD_NAME']."]";
        if (in_array($fieldArr['USER_TYPE_ID'], ['string', 'integer'])) {
            $sortFieldList[$k] = $userFieldsSelect[$k];
        }
    }
}

#<editor-fold desc="SortVariables">
$arSorts = [
    "ASC"  => Loc::getMessage("IBLOCK_DESC_ASC"),
    "DESC" => Loc::getMessage("IBLOCK_DESC_DESC"),
];
#sortVariables </editor-fold>

#<editor-fold desc="arComponentParameters">
$arComponentParameters = [
    "GROUPS"     => [
    ],
    "PARAMETERS" => [
        "HLBLOCK_ID"     => [
            "PARENT"  => "BASE",
            "NAME"    => Loc::getMessage("HLBLOCK_ID"),
            "TYPE"    => "LIST",
            "VALUES"  => $HLBlockTypes,
            "DEFAULT" => "",
            "REFRESH" => "Y",
        ],
        "FIELD_CODE"     => [
            "PARENT"            => "BASE",
            "NAME"              => Loc::getMessage("FIELD_CODE"),
            "TYPE"              => "LIST",
            "MULTIPLE"          => "Y",
            "VALUES"            => $userFieldsSelect,
            "DEFAULT"           => [],
            "ADDITIONAL_VALUES" => "N",
        ],
        "DONT_INCLUDE_TEMPLATE" => [
            "PARENT"  => "DATA_SOURCE",
            "NAME"    => Loc::getMessage("DONT_INCLUDE_TEMPLATE"),
            "TYPE"    => "CHECKBOX",
            "DEFAULT" => "N",
        ],
        "SORT_FILED"     => [
            "PARENT"            => "BASE",
            "NAME"              => Loc::getMessage("SORT_FILED"),
            "TYPE"              => "LIST",
            "MULTIPLE"          => "N",
            "VALUES"            => $sortFieldList,
            "DEFAULT"           => "",
            "ADDITIONAL_VALUES" => "N",
        ],
        "SORT_ORDER"     => [
            "PARENT"            => "BASE",
            "NAME"              => Loc::getMessage("SORT_ORDER"),
            "TYPE"              => "LIST",
            "DEFAULT"           => "DESC",
            "VALUES"            => $arSorts,
            "ADDITIONAL_VALUES" => "N",
        ],
        "ELEMENTS_COUNT" => [
            "PARENT"  => "BASE",
            "NAME"    => Loc::getMessage("ELEMENTS_COUNT"),
            "TYPE"    => "STRING",
            "DEFAULT" => "20",
        ],
        "USE_FILTER"     => [
            "PARENT"  => "BASE",
            "NAME"    => Loc::getMessage("USE_FILTER"),
            "TYPE"    => "CHECKBOX",
            "DEFAULT" => "N",
            "REFRESH" => "Y",
        ],
        "FILTER_NAME"    => [
            "PARENT"  => "BASE",
            "NAME"    => Loc::getMessage("FILTER_NAME"),
            "TYPE"    => "STRING",
            "DEFAULT" => "",
        ],

        "CACHE_TIME" => ["DEFAULT" => 36000000],
    ],
];

if ($arCurrentValues['USE_FILTER'] != 'Y') {
    unset($arComponentParameters['PARAMETERS']['FILTER_NAME']);
}
if ($arCurrentValues['USE_SORT'] != 'Y') {
    unset(
        $arComponentParameters['PARAMETERS']['SORT_FILED'],
        $arComponentParameters['PARAMETERS']['SORT_ORDER'],
    );
}

#arComponentParameters </editor-fold>;