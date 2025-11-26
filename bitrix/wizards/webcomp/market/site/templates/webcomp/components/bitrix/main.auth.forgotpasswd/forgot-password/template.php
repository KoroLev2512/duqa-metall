<?php
if ( ! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if ($arResult['AUTHORIZED']) {
    LocalRedirect("/personal/");

}
?>


<? if ($arResult['ERRORS']): ?>
    <div class="alert alert-danger">
        <? foreach ($arResult['ERRORS'] as $error) {
            echo $error;
        }
        ?>
    </div>
<? elseif ($arResult['SUCCESS']): ?>
    <div class="alert alert-success">
        <?= $arResult['SUCCESS']; ?>
        <? return; ?>
    </div>
<? endif; ?>
<div>
    <p class="bx-authform-content-container"><?= Loc::getMessage('MAIN_AUTH_PWD_NOTE'); ?></p>
</div>
<br>
<form name="bform" method="post" class="data__fields" target="_top"
      action="<?= POST_FORM_ACTION_URI; ?>">
    <div class="data__field">
        <div class="data__label"><?= Loc::getMessage('MAIN_AUTH_PWD_FIELD_EMAIL'); ?>
            <span class="data__label_r">*</span></div>
        <div class="data__row">
            <div class="data__left">
                <input class="data__input input" required="" type="text"
                       name="<?= $arResult['FIELDS']['email']; ?>"
                       value="<?= \htmlspecialcharsbx($arResult['LAST_LOGIN']); ?>"
                       placeholder="">
            </div>
        </div>
    </div>

    <? if ($arResult['CAPTCHA_CODE']): ?>
        <input type="hidden" name="captcha_sid"
               value="<?= \htmlspecialcharsbx($arResult['CAPTCHA_CODE']); ?>"/>
        <div class="bx-authform-formgroup-container dbg_captha">
            <div class="bx-authform-label-container">
                <?= Loc::getMessage('MAIN_AUTH_PWD_FIELD_CAPTCHA'); ?>
            </div>
            <div class="bx-captcha"><img
                        src="/bitrix/tools/captcha.php?captcha_sid=<?= \htmlspecialcharsbx($arResult['CAPTCHA_CODE']); ?>"
                        width="180" height="40" alt="CAPTCHA"/></div>
            <div class="bx-authform-input-container">
                <input type="text" name="captcha_word" maxlength="50" value=""
                       autocomplete="off"/>
            </div>
        </div>
    <? endif; ?>

    <div class="bx-authform-formgroup-container">
        <input type="submit" class="btn btn-primary"
               name="<?= $arResult['FIELDS']['action']; ?>"
               value="<?= Loc::getMessage('MAIN_AUTH_PWD_FIELD_SUBMIT'); ?>"/>
    </div>

    <? if ($arResult['AUTH_AUTH_URL'] || $arResult['AUTH_REGISTER_URL']): ?>
        <hr class="bxe-light">
        <noindex>
            <? if ($arResult['AUTH_AUTH_URL']): ?>
                <div class="bx-authform-link-container">
                    <a href="<?= $arResult['AUTH_AUTH_URL']; ?>" rel="nofollow">
                        <?= Loc::getMessage('MAIN_AUTH_PWD_URL_AUTH_URL'); ?>
                    </a>
                </div>
            <? endif; ?>
            <? if ($arResult['AUTH_REGISTER_URL']): ?>
                <div class="bx-authform-link-container">
                    <a href="<?= $arResult['AUTH_REGISTER_URL']; ?>"
                       rel="nofollow">
                        <?= Loc::getMessage('MAIN_AUTH_PWD_URL_REGISTER_URL'); ?>
                    </a>
                </div>
            <? endif; ?>
        </noindex>
    <? endif; ?>

</form>

<script type="text/javascript">
    document.bform.<?= $arResult['FIELDS']['login'];?>.focus();
</script>
