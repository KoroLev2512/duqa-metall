<?
/**
 * Bitrix Framework
 *
 * @package    bitrix
 * @subpackage main
 * @copyright  2001-2014 Bitrix
 */

/**
 * Bitrix vars
 *
 * @param array                    $arParams
 * @param array                    $arResult
 * @param CBitrixComponentTemplate $this
 *
 * @global CUser                   $USER
 * @global CMain                   $APPLICATION
 */

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

?>

<? if ($USER->IsAuthorized()): ?>
    <? LocalRedirect("/personal/"); ?>
<? else: ?>
    <?
    if (count($arResult["ERRORS"]) > 0):
        foreach ($arResult["ERRORS"] as $key => $error) {
            if (intval($key) == 0 && $key !== 0) {
                $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#",
                    "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;",
                    $error);
            }
        }

        ShowError(implode("<br />", $arResult["ERRORS"]));

    elseif ($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
        ?>
        <p><? echo Loc::getMessage("REGISTER_EMAIL_WILL_BE_SENT") ?></p>
    <? endif ?>
    <form method="post" class="data__fields"
          action="<?= POST_FORM_ACTION_URI ?>" name="regform"
          enctype="multipart/form-data">
        <? foreach ($arResult["SHOW_FIELDS"] as $FIELD): ?>
            <div class="data__field">
                <div class="data__label"><?= Loc::getMessage("REGISTER_FIELD_"
                        .$FIELD) ?><? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD]
                        == 'Y'
                    ): ?><span class="data__label_r">*</span><? endif; ?></div>
                <div class="data__row">
                    <div class="data__left">
                        <?
                        switch ($FIELD) {
                            case "PASSWORD":
                                ?><input
                                class="data__input input" <?= $arResult["REQUIRED_FIELDS_FLAGS"][$FIELD]
                            == 'Y' ? "required" : "" ?> type="password"
                                name="REGISTER[<?= $FIELD ?>]" value=""
                                placeholder="" autocomplete="off"
                                class="bx-auth-input" /><?
                                break;
                            case "CONFIRM_PASSWORD":
                                ?><input
                                class="data__input input" <?= $arResult["REQUIRED_FIELDS_FLAGS"][$FIELD]
                            == 'Y' ? "required" : "" ?> type="password"
                                name="REGISTER[<?= $FIELD ?>]" value=""
                                placeholder="" autocomplete="off" ><?
                                break;
                            default:
                                ?>
                                <input class="data__input input" <?= $arResult["REQUIRED_FIELDS_FLAGS"][$FIELD]
                                == 'Y' ? "required" : "" ?> type="text"
                                       name="REGISTER[<?= $FIELD ?>]"
                                       value="<?= $arResult["VALUES"][$FIELD] ?>"
                                       placeholder="">
                            <? } ?>
                    </div>
                </div>
            </div>
        <? endforeach ?>
        <?
        /* CAPTCHA */
        if ($arResult["USE_CAPTCHA"] == "Y") {
            ?>
            <div class="data__field">
                <div class="data__label"><?= Loc::getMessage("REGISTER_CAPTCHA_PROMT") ?>
                    <span class="data__label_r">*</span></div>
                <br>
                <input type="hidden" name="captcha_sid"
                       value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>"
                     width="180" height="40" alt="CAPTCHA"/>
                <div class="data__row">
                    <div class="data__left">
                        <input class="data__input input" required type="text"
                               name="captcha_word"
                               maxlength="50"
                               value=""
                               autocomplete="off"
                               placeholder="">
                    </div>
                </div>
            </div>
            <?
        }
        /* !CAPTCHA */
        ?>
        <input type="submit" class="data__btn btn" name="register_submit_button"
               value="<?= Loc::getMessage("AUTH_REGISTER") ?>"/>
    </form>
<? endif ?>