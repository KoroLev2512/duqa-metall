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

#Namespaces </editor-fold>

if ( ! Loader::includeModule("iblock")) {
    return;
}

#<editor-fold desc="IBlockTypes">
$IBlockTypes = [];
$arTypesEx = Bitrix\Iblock\TypeTable::getList(
    [
        'select' =>
            ['*', 'NAME' => 'LANG_MESSAGE.NAME'],
        'filter' =>
            ['=LANG_MESSAGE.LANGUAGE_ID' => 'ru']
    ]
);
while($arRes = $arTypesEx->Fetch()) {
    $IBlockTypes[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];
}
unset($arRes);
#IBlockTypes </editor-fold>

#<editor-fold desc="IBlockList">
    $arIBlocks = [];
    $dbItems = \Bitrix\Iblock\IblockTable::getList([
        'order' => ['SORT' => 'ASC'],
        'select' => ['ID', 'NAME', 'CODE', 'SORT'],
        'runtime' => [],
        'filter' => ['IBLOCK_TYPE_ID'=>$arCurrentValues["IBLOCK_TYPE"], "LID"=>$_REQUEST["site"]],
        'data_doubling' => false,
        'cache' => [
            'ttl' => 3600,
            'cache_joins' => true
        ],
    ]);
    while($arRes = $dbItems->Fetch()) {
        $arIBlocks[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];
    }
    unset($arRes);
#IBlockList </editor-fold>

#<editor-fold desc="SortVariables">
$arSorts = [
    "ASC"  => Loc::getMessage("IBLOCK_DESC_ASC"),
    "DESC" => Loc::getMessage("IBLOCK_DESC_DESC")
];

$arSortFields = [
    "ID"          => Loc::getMessage("IBLOCK_DESC_FID"),
    "NAME"        => Loc::getMessage("IBLOCK_DESC_FNAME"),
    "SORT"        => Loc::getMessage("IBLOCK_DESC_FSORT"),
];
#sortVariables </editor-fold>

#<editor-fold desc="IBlockFieldList">
$arFieldCodes = [];
foreach (\Bitrix\Iblock\SectionTable::getEntity()->getFields() as $field) {
    $arFieldCodes[$field->getName()] = $field->getTitle();
}

#IBlockFieldList </editor-fold>

$arMenu = GetMenuTypes($site);

$pathStart = [
    '/'        => 'От корня',
    'THIS_DIR' => 'Текущая директория (по цепочке вверх)',
];

#<editor-fold desc="arComponentParameters">
$arComponentParameters = [
    "GROUPS"     => [
    ],
    "PARAMETERS" => [
        "IBLOCK_TYPE"                     => [
            "PARENT"  => "BASE",
            "NAME"    => Loc::getMessage("IBLOCK_TYPE"),
            "TYPE"    => "LIST",
            "VALUES"  => $IBlockTypes,
            "DEFAULT" => "",
            "REFRESH" => "Y",
        ],
        "IBLOCK_ID"                       => [
            "PARENT"            => "BASE",
            "NAME"              => Loc::getMessage("IBLOCK_ID"),
            "TYPE"              => "LIST",
            "VALUES"            => $arIBlocks,
            "DEFAULT"           => '={$_REQUEST["ID"]}',
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH"           => "Y",
        ],
        'TYPE_MENU'                       => [
            "NAME"              => Loc::getMessage("TYPE_MENU"),
            "TYPE"              => "LIST",
            "VALUES"            => $arMenu,
            "ADDITIONAL_VALUES" => "Y",
            "DEFAULT"           => 'top',
            "PARENT"            => "BASE",
            "COLS"              => 45,
        ],
        "MAX_DEPTH"                       => [
            "PARENT"   => "DATA_SOURCE",
            "NAME"     => Loc::getMessage("MAX_DEPTH"),
            "TYPE"     => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT"  => '1',
        ],
        'USE_CATALOG'                     => [
            "PARENT"   => "DATA_SOURCE",
            "NAME"     => Loc::getMessage("USE_CATALOG"),
            "TYPE"     => "CHECKBOX",
            "MULTIPLE" => "N",
            "DEFAULT"  => 'N',
            "REFRESH"  => "Y",
        ],
        'START_DIRECTORY'                 => [
            "NAME"              => Loc::getMessage("START_DIRECTORY"),
            "TYPE"              => "LIST",
            "VALUES"            => $pathStart,
            "ADDITIONAL_VALUES" => "N",
            "DEFAULT"           => '/',
            "PARENT"            => "BASE",
            "COLS"              => 45,
        ],
        'CATALOG_PATH'                    => [
            "PARENT"   => "DATA_SOURCE",
            "NAME"     => Loc::getMessage("CATALOG_PATH"),
            "TYPE"     => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT"  => '/catalog/',
        ],
        'CATALOG_ONLY'                    => [
            "PARENT"   => "DATA_SOURCE",
            "NAME"     => Loc::getMessage("CATALOG_ONLY"),
            "TYPE"     => "CHECKBOX",
            "MULTIPLE" => "N",
            "DEFAULT"  => 'N',
        ],
        "PARAMS_CATALOG_FIELD_CODE"       => [
            "PARENT"            => "DATA_SOURCE",
            "NAME"              => Loc::getMessage("PARAMS_CATALOG_FIELD_CODE"),
            "TYPE"              => "LIST",
            "MULTIPLE"          => "Y",
            "VALUES"            => $arFieldCodes,
            "DEFAULT"           => ['ID', 'NAME', 'CODE',],
            "ADDITIONAL_VALUES" => "Y",
        ],
        "PARAMS_CATALOG_MAX_DEPTH"        => [
            "PARENT"   => "DATA_SOURCE",
            "NAME"     => Loc::getMessage("PARAMS_CATALOG_MAX_DEPTH"),
            "TYPE"     => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT"  => '1',
        ],
        "PARAMS_CATALOG_SHOW_ONLY_ACTIVE" => [
            "PARENT"   => "DATA_SOURCE",
            "NAME"     => Loc::getMessage("PARAMS_CATALOG_SHOW_ONLY_ACTIVE"),
            "TYPE"     => "CHECKBOX",
            "MULTIPLE" => "N",
            "DEFAULT"  => 'Y',
        ],
        "CACHE_TIME"                      => ["DEFAULT" => 36000000],
        'MENU_TITLE'                    => [
            "PARENT"   => "DATA_SOURCE",
            "NAME"     => Loc::getMessage("MENU_TITLE"),
            "TYPE"     => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT"  => '',
        ],
        "USE_EXT" => [
            "NAME"      => Loc::getMessage("USE_EXT_NAME"),
            "TYPE"      => "CHECKBOX",
            "DEFAULT"   => 'Y',
            "PARENT"    => "DATA_SOURCE",
        ],
    ],
];

if($arCurrentValues['USE_CATALOG']!='Y'){
    unset(
        $arComponentParameters['PARAMETERS']['PARAMS_CATALOG_SHOW_ONLY_ACTIVE'],
        $arComponentParameters['PARAMETERS']['PARAMS_CATALOG_MAX_DEPTH'],
        $arComponentParameters['PARAMETERS']['PARAMS_CATALOG_FIELD_CODE'],
        $arComponentParameters['PARAMETERS']['CATALOG_ONLY'],
        $arComponentParameters['PARAMETERS']['CATALOG_PATH'],
        $arComponentParameters['PARAMETERS']['IBLOCK_ID'],
        $arComponentParameters['PARAMETERS']['IBLOCK_TYPE']
    );
}
#arComponentParameters </editor-fold>;
