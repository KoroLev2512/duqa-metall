<?php

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}

global $arRes;
if ($arRes['status'] != 'ok') {
    echo $arRes['mess'];
    return;
}
?>

<?=CAdminMessage::ShowNote(Loc::getMessage('FX_BI_INSTALLED'))?>

<a href="/bitrix/admin/partner_modules.php?lang=<?=LANGUAGE_ID?>" class="adm-btn">
    <?=Loc::getMessage('FX_BI_BACK')?>
</a>