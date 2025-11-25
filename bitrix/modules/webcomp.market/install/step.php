<?php

use Bitrix\Main\Localization\Loc;

if(!check_bitrix_sessid())
	return;

if($errorException = $APPLICATION->getException()){
	echo CAdminMessage::showMessage([
		"TYPE" => "ERROR",
		"MESSAGE" => Loc::getMessage("WEBCOMP_MARKET_MODULE_INSTALL_ERROR"),
		"DETAILS" => $errorException->GetString(),
		"HTML" => true,
	]);
}else{
    echo CAdminMessage::showNote(Loc::getMessage("WEBCOMP_MARKET_MODULE_INSTALL_SUCCESS"));
}
?>

<form action="<?= $APPLICATION->getCurPage(); ?>"> <!-- Кнопка возврата к списку модулей -->
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>" />
    <input type="submit" value="<?=Loc::getMessage("WEBCOMP_MARKET_BACK_MODULES")?>">
</form>
