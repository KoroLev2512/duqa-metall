<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if ($WEBCOMP["SETTINGS"]["WEBCOMP_CHECKBOX_LK"] !== "Y"){
    LocalRedirect("/index.php");
}

$APPLICATION->SetTitle("Личный кабинет"); ?>
<? $APPLICATION->IncludeComponent("bitrix:system.auth.form",
    "lk", [
        "FORGOT_PASSWORD_URL" => "#WIZARD_SITE_DIR#personal/forgot-password/",
        // Страница забытого пароля
        "PROFILE_URL"         => "",
        // Страница профиля
        "REGISTER_URL"        => "#WIZARD_SITE_DIR#personal/register/",
        // Страница регистрации
        "SHOW_ERRORS"         => "Y",
        // Показывать ошибки
    ],
    false
); ?><? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>