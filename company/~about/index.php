<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О компании");
?>
<? $APPLICATION->IncludeComponent(
    "webcomp:element.getList",
    "about",
    [
        "CACHE_FILTER"     => "N",
        "CACHE_TIME"       => "36000000",
        "CACHE_TYPE"       => "A",
        "ELEMENTS_COUNT"   => "1",
        "FIELD_CODE"       => [
            0 => "ID",
            1 => "NAME",
            2 => "PREVIEW_PICTURE",
            3 => "PREVIEW_TEXT",
            4 => "DETAIL_PICTURE",
            5 => "DETAIL_TEXT",
            6 => "CODE",
        ],
        "FILTER_NAME"      => "",
        "IBLOCK_ID"        => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_about'],
        "IBLOCK_TYPE"      => "content",
        "PROPERTY_CODE"    => [0 => "subtitle", 1 => "",],
        "SHOW_ONLY_ACTIVE" => "Y",
        "SORT_BY1"         => "ACTIVE_FROM",
        "SORT_BY2"         => "SORT",
        "SORT_ORDER1"      => "DESC",
        "SORT_ORDER2"      => "ASC",
    ]
); ?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>