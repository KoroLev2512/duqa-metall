<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Сменить пароль"); ?><?php $APPLICATION->SetTitle("Сменить пароль"); ?>
<? $APPLICATION->IncludeComponent(
    "bitrix:main.profile",
    "change_password",
    [
        "CHECK_RIGHTS"       => "N",
        "COMPONENT_TEMPLATE" => "change_password",
        "SEND_INFO"          => "N",
        "SET_TITLE"          => "N",
        "USER_PROPERTY"      => [],
        "USER_PROPERTY_NAME" => "",
    ]
); ?><? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>