<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация"); ?>
<?$APPLICATION->IncludeComponent(
    "bitrix:main.register",
    "register",
    [
        "AUTH"               => "Y",
        "REQUIRED_FIELDS"    => [
            0 => "NAME",
        ],
        "SET_TITLE"          => "N",
        "SHOW_FIELDS"        => [
            0 => "NAME",
        ],
        "SUCCESS_PAGE"       => "",
        "USER_PROPERTY"      => [
        ],
        "USER_PROPERTY_NAME" => "",
        "USE_BACKURL"        => "Y",
        "COMPONENT_TEMPLATE" => "register",
    ],
    false
); ?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>