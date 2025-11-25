<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Восстановление пароля"); ?>
<?php
if ( ! empty(\Bitrix\Main\Context::getCurrent()->getRequest()
	->get("USER_CHECKWORD"))
):?>
	<? $APPLICATION->IncludeComponent("bitrix:main.auth.changepasswd",
		"change-password", [

		],
		false
	); ?>
<? else:?>
	<? $APPLICATION->IncludeComponent("bitrix:main.auth.forgotpasswd",
		"forgot-password", [
			"AUTH_AUTH_URL"     => "",    // Страница для авторизации
			"AUTH_REGISTER_URL" => "",    // Страница для регистрации
		],
		false
	); ?>
<? endif; ?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>

