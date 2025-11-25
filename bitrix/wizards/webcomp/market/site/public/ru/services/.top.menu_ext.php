<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

$_temp = $APPLICATION->IncludeComponent(
    "webcomp:element.getList",
    ".default",
    array(
        "CACHE_FILTER" => "N",
        "CACHE_TIME" => "0",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => ".default",
        "ELEMENTS_COUNT" => "100",
        "FIELD_CODE" => array(
            0 => "ID",
            1 => "NAME",
            2 => "CODE",
        ),
        "FILTER_NAME" => "",
        "IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_services'],
        "IBLOCK_TYPE" => "content",
        "PROPERTY_CODE" => [],
        "SHOW_ONLY_ACTIVE" => "Y",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "ID",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "DESC",
        "TITLE" => "",
        "USE_FILTER" => "N",
        "DONT_INCLUDE_TEMPLATE" => "Y"
    ),
    false
)["ITEMS"];

if(!empty($_temp)) {
    foreach ($_temp as $item) {
        $aMenuLinksExt[] =
            [
                $item["NAME"],
                $item["DETAIL_PAGE_URL"],
                [],
                [],
                ""
            ];
    }
}


$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>
