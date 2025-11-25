<?php

use Bitrix\Main\Localization\Loc;

if(!check_bitrix_sessid())
	return;

?>
<form action="<?= $APPLICATION->getCurPage(); ?>">
		<?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>">
    <input type="hidden" name="id" value="webcomp.market">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <?=CAdminMessage::showMessage(Loc::getMessage("WEBCOMP_MARKET_MODULE_UNINSTALL_WARN"));?>
    <p><?=Loc::getMessage("WEBCOMP_MARKET_MODULE_UNINSTALL_SAVE")?></p>
    <p>
    	<input type="checkbox" name="savedata" id="savedata" value="Y" checked>
    	<label for="savedata"><?=Loc::getMessage("WEBCOMP_MARKET_MODULE_UNINSTALL_SAVE_TABLES")?></label>
    </p>

    <input type="submit" value="<?=Loc::getMessage("WEBCOMP_MARKET_MODULE_DEL")?>">
</form>
