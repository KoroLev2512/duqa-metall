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

#<editor-fold desc="EmailTemplates">

$arMess = [];
$arFilter = [];
$rsMess = CEventMessage::GetList($by="id", $order="asc", $arFilter);

while($arRes = $rsMess->Fetch()) {
    $arMess[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["EVENT_NAME"];
}

unset($arRes);
#EmailTemplates </editor-fold>

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

#<editor-fold desc="arComponentParameters">
    $arComponentParameters = [
        "GROUPS"     => [
        ],
        "PARAMETERS" => [

            "EMAIL_EVENT_ID"     => [
                "PARENT"            => "BASE",
                "NAME"              => Loc::getMessage("EMAIL_TEMPLATE_LIST_ID"),
                "TYPE"              => "LIST",
                "VALUES"            => $arMess,
                "DEFAULT"           => '',
                "ADDITIONAL_VALUES" => "Y"
            ],
            "CACHE_TIME"    => ["DEFAULT" => 0],
        ],
    ];
#arComponentParameters </editor-fold>;