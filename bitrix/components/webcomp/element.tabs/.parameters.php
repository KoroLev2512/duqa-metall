<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues  */

#<editor-fold desc="Namespaces">
    use Bitrix\Main\Loader;
    use Bitrix\Main\Localization\Loc;
    use Bitrix\Main\Diag;
#Namespaces </editor-fold>

if(!Loader::includeModule("iblock"))
    return;

#<editor-fold desc="IBlockTypes">
$IBlockTypes=[];
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

#<editor-fold desc="PropertiesOnSelectIBlock">
    $arProperty = [];
    $rsProperty = \Bitrix\Iblock\PropertyTable::getList(array(
        'order' => ['SORT' => 'ASC','NAME'=>'ASC'],
        'filter' => array(
            'IBLOCK_ID' => isset($arCurrentValues["IBLOCK_ID"])
                ? $arCurrentValues["IBLOCK_ID"] : $arCurrentValues["ID"],
            'ACTIVE'    => 'Y',
            "PROPERTY_TYPE" => "L"
        ),
    ));
    while ($arr = $rsProperty->Fetch()) {
        $arPropertyID[$arr["ID"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
    }
#PropertiesOnSelectIBlock </editor-fold>

#<editor-fold desc="PropertiesOnSelectIBlock">
$arProperty = [];
$rsProperty = \Bitrix\Iblock\PropertyTable::getList(array(
    'order' => ['SORT' => 'ASC','NAME'=>'ASC'],
    'filter' => array(
        'IBLOCK_ID' => isset($arCurrentValues["IBLOCK_ID"])
            ? $arCurrentValues["IBLOCK_ID"] : $arCurrentValues["ID"],
        'ACTIVE'    => 'Y',
    ),
));
while ($arr = $rsProperty->Fetch()) {
    $arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
}
#PropertiesOnSelectIBlock </editor-fold>

$arPropertyEnum = (isset($arCurrentValues["PROPERTY_ID"]))
    ? CMarketTools::getEnumFields($arCurrentValues["PROPERTY_ID"])
    : [];

$arPropertyEnumValue = [];

if(!empty($arPropertyEnum)) {
    foreach ($arPropertyEnum as $enumProp) {
        $arPropertyEnumValue[$enumProp["ID"]] =  "[".$enumProp["ID"]."] ".$enumProp["NAME"];
    }
}

#<editor-fold desc="SortVariables">
$arSorts = [
    "ASC"  => Loc::getMessage("IBLOCK_DESC_ASC"),
    "DESC" => Loc::getMessage("IBLOCK_DESC_DESC")
];

$arSortFields = [
    "ID"          => Loc::getMessage("IBLOCK_DESC_FID"),
    "NAME"        => Loc::getMessage("IBLOCK_DESC_FNAME"),
    "ACTIVE_FROM" => Loc::getMessage("IBLOCK_DESC_FACT"),
    "SORT"        => Loc::getMessage("IBLOCK_DESC_FSORT"),
    "TIMESTAMP_X" => Loc::getMessage("IBLOCK_DESC_FTSAMP"),
];
#sortVariables </editor-fold>

#<editor-fold desc="IBlockFieldList">
$arFieldCodes = [];
foreach (\Bitrix\Iblock\ElementTable::getEntity()->getFields() as $field) {
    $arFieldCodes[$field->getName()] = $field->getTitle();
}
#IBlockFieldList </editor-fold>

#<editor-fold desc="arComponentParameters">
    $arComponentParameters = [
        "GROUPS"     => [
        ],
        "PARAMETERS" => [
            "IBLOCK_TYPE"   => [
                "PARENT"  => "BASE",
                "NAME"    => Loc::getMessage("IBLOCK_DESC_LIST_TYPE"),
                "TYPE"    => "LIST",
                "VALUES"  => $IBlockTypes,
                "DEFAULT" => "",
                "REFRESH" => "Y",
            ],
            "IBLOCK_ID"     => [
                "PARENT"            => "BASE",
                "NAME"              => Loc::getMessage("IBLOCK_DESC_LIST_ID"),
                "TYPE"              => "LIST",
                "VALUES"            => $arIBlocks,
                "DEFAULT"           => '={$_REQUEST["ID"]}',
                "ADDITIONAL_VALUES" => "Y",
                "REFRESH"           => "Y",
            ],
            "ELEMENTS_COUNT"    => [
                "PARENT"  => "BASE",
                "NAME"    => Loc::getMessage("IBLOCK_DESC_LIST_CONT"),
                "TYPE"    => "STRING",
                "DEFAULT" => "20",
            ],
            "PROPERTY_ID"         => [
                "PARENT"            => "BASE",
                "NAME"              => Loc::getMessage("IBLOCK_PROPERTY_ID"),
                "TYPE"              => "LIST",
                "VALUES"            => $arPropertyID,
                "REFRESH"           => "Y",
            ],
            "PROPERTY_ENUM"         => [
                "PARENT"            => "BASE",
                "NAME"              => Loc::getMessage("IBLOCK_PROPERTY_ENUM"),
                "TYPE"              => "LIST",
                "MULTIPLE"          => "Y",
                "VALUES"            => $arPropertyEnumValue,
            ],
            "TITLE"         => [
                "PARENT"            => "BASE",
                "NAME"              => Loc::getMessage("BLOCK_TITLE"),
                "TYPE"              => "STRING",
                "DEFAULT"           => "Стоит приглядеться",
                "VALUES"            => "",
            ],
            "LINK"         => [
                "PARENT"            => "BASE",
                "NAME"              => Loc::getMessage("BLOCK_LINK"),
                "TYPE"              => "STRING",
                "DEFAULT"           => "/catalog/",
                "VALUES"            => "",
            ],
            "SORT_BY1"      => [
                "PARENT"            => "DATA_SOURCE",
                "NAME"              => Loc::getMessage("IBLOCK_DESC_IBORD1"),
                "TYPE"              => "LIST",
                "DEFAULT"           => "ACTIVE_FROM",
                "VALUES"            => $arSortFields,
                "ADDITIONAL_VALUES" => "Y",
            ],
            "SORT_ORDER1"   => [
                "PARENT"            => "DATA_SOURCE",
                "NAME"              => Loc::getMessage("IBLOCK_DESC_IBBY1"),
                "TYPE"              => "LIST",
                "DEFAULT"           => "DESC",
                "VALUES"            => $arSorts,
                "ADDITIONAL_VALUES" => "Y",
            ],
            "SORT_BY2"              => [
                "PARENT"            => "DATA_SOURCE",
                "NAME"              => Loc::getMessage("IBLOCK_DESC_IBORD2"),
                "TYPE"              => "LIST",
                "DEFAULT"           => "SORT",
                "VALUES"            => $arSortFields,
                "ADDITIONAL_VALUES" => "Y",
            ],
            "SORT_ORDER2"           => [
                "PARENT"            => "DATA_SOURCE",
                "NAME"              => Loc::getMessage("IBLOCK_DESC_IBBY2"),
                "TYPE"              => "LIST",
                "DEFAULT"           => "ASC",
                "VALUES"            => $arSorts,
                "ADDITIONAL_VALUES" => "Y",
            ],
            "PROPERTY_CODE"         => [
                "PARENT"            => "DATA_SOURCE",
                "NAME"              => Loc::getMessage("IBLOCK_PROPERTY"),
                "TYPE"              => "LIST",
                "VALUES"            => $arProperty,
                "MULTIPLE"          => "Y",
            ],
            "FILTER_NAME"           => [
                "PARENT"  => "DATA_SOURCE",
                "NAME"    => Loc::getMessage("FILTER_NAME"),
                "TYPE"    => "STRING",
                "DEFAULT" => "",
            ],
            "DONT_INCLUDE_TEMPLATE" => [
                "PARENT"  => "DATA_SOURCE",
                "NAME"    => Loc::getMessage("DONT_INCLUDE_TEMPLATE"),
                "TYPE"    => "CHECKBOX",
                "DEFAULT" => "N",
            ],
            "USE_FILTER"            => [
                "PARENT"  => "DATA_SOURCE",
                "NAME"    => Loc::getMessage("USE_FILTER"),
                "TYPE"    => "CHECKBOX",
                "DEFAULT" => "N",
            ],
            "SHOW_ONLY_ACTIVE" => [
                "PARENT"  => "DATA_SOURCE",
                "NAME"    => Loc::getMessage("IBLOCK_DESC_CHECK_DATES"),
                "TYPE"    => "CHECKBOX",
                "DEFAULT" => "Y",
            ],
            "CACHE_TIME"    => ["DEFAULT" => 36000000],
            "CACHE_FILTER"  => [
                "PARENT"  => "CACHE_SETTINGS",
                "NAME"    => Loc::getMessage("IBLOCK_CACHE_FILTER"),
                "TYPE"    => "CHECKBOX",
                "DEFAULT" => "N",
            ],
        ],
    ];
#arComponentParameters </editor-fold>;