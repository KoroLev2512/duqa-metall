<?php
require($_SERVER["DOCUMENT_ROOT"]
    ."/bitrix/modules/main/include/prolog_before.php");

if ( ! isset($GLOBALS["WEBCOMP"]["IBLOCKS"])) {
    \Webcomp\Market\Constants::getAllIblocks();
}
global $USER;
if ( ! $USER->IsAdmin()) {
    die();
}
$id = \Bitrix\Main\Context::getCurrent()->getRequest()->getPost("id");
if ((int)$id > 0) {
    $res = $APPLICATION->IncludeComponent("webcomp:element.getList", "", [
        "CACHE_FILTER"          => "N",
        // Кешировать при установленном фильтре
        "CACHE_TIME"            => "36000000",
        // Время кеширования (сек.)
        "CACHE_TYPE"            => "A",
        // Тип кеширования
        "ELEMENTS_COUNT"        => "1",
        // Максимальное количество элементов
        "FIELD_CODE"            => [    // Поля
                                        0 => "ID",
                                        1 => "IBLOCK_ID",
                                        2 => "NAME",
                                        3 => "PREVIEW_PICTURE",
                                        4 => "DETAIL_PICTURE",
        ],
        "FILTER"                => ["ID" => $id, "ACTIVE" => ["N", "Y"]],
        "IBLOCK_ID"             => $GLOBALS["WEBCOMP"]["IBLOCKS"]['catalog']["catalog_webcomp"],
        // Код информационного блока
        "IBLOCK_TYPE"           => "catalog",
        // Тип информационного блока
        "PROPERTY_CODE"         => [    // Свойства
                                        0 => "ARTICLE",
                                        1 => "AVAILABLE",
                                        2 => "MAIN_PRODUCT",
                                        3 => "PRICE",
        ],
        "SHOW_ONLY_ACTIVE"      => "Y",
        // Показывать только активные элементы
        "SORT_BY1"              => "ACTIVE_FROM",
        // Поле для первой сортировки
        "SORT_BY2"              => "SORT",
        // Поле для второй сортировки
        "SORT_ORDER1"           => "DESC",
        // Направление для первой сортировки
        "SORT_ORDER2"           => "ASC",
        // Направление для второй сортировки
        "COMPONENT_TEMPLATE"    => "",
        "USE_FILTER"            => "Y",
        // Использовать фильтр
        "DONT_INCLUDE_TEMPLATE" => "Y",
        // Не подключать шаблон компонента
    ],
        false
    )['ITEMS'][0];
    $res['PRICE_VAL']
        = CMarketCatalog::getPrice($res['PROPERTIES']['PRICE']['VALUE']);
    echo json_encode($res);
}