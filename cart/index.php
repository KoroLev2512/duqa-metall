<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");?>

<?$APPLICATION->IncludeComponent(
    "webcomp:order",
    "testing",
    Array(
        "BIND_ELEMENTS" => "",
        "CACHE_FILTER" => "N",
        "CACHE_TIME" => "0",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => ".default",
        "ELEMENTS_COUNT" => "20",
        "EMAIL_EVENT_ID" => "WEBCOMP_ASK_QUESTION",
        "FIELD_CODE" => "",
        "FILTER_NAME" => "",
        "PROPERTY_CODE" => "",
        "SHOW_ONLY_ACTIVE" => "Y",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "NAME",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "ASC"
    )
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>