<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc;
global $WEBCOMP;
$arSettings = $WEBCOMP["SETTINGS"];

/**
 * isset vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CUser $WEBCOMP - globals settings in template
 */

?>

<form class="footer__feed ffeed" method="POST"
      action="#WIZARD_SITE_DIR#ajax/form/">
    <div class="container">
        <div class="row ffeed__row">
            <div class="ffeed__left">
                <? if (!empty($arResult["IBLOCK"]["DESCRIPTION"])): ?>
      <div class="ffeed__title"><?=$arResult["IBLOCK"]["DESCRIPTION"]?></div>
    <? endif ?>
    </div>
    <div class="ffeed__right">
      <div class="row">

        <? foreach ($arResult["FIELDS"] as $field): ?>

            <?
                $isRequired = $field["IS_REQUIRED"] == "Y";
                $isDisabled = $field["IS_DISABLED"] == "Y";
                $isHidden   = $field["IS_HIDDEN"] == "Y";
            ?>

            <? if ($isHidden): ?>

                <? if(isset($field["ELEMENTS"])):?>
                    <? foreach ($field["ELEMENTS"] as $element): ?>
                        <input type="hidden" name="<?=$field["CODE"]?>[]" value="<?=$element["ID"]?>">
                    <? endforeach ?>
                <? else: ?>
                    <input type="hidden" name="<?=$field["CODE"]?>" value="">
                <? endif ?>

                <? if(!$isDisabled) continue ?>

            <? endif ?>

            <? if ($field["PROPERTY_TYPE"] == "STRING") : ?>
                <div class="ffeed__field">
                    <input class="ffeed__input"
                        type="text"
                        name="<?=$field["CODE"]?>"
                        data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                        data-msg-required="<?=$field["ERROR_MSG"]?>"
                        placeholder="<?=$field["NAME"]?><?=($isRequired) ? "*" : ""?>"
                        <?=($isDisabled) ? "disabled" : ""?>
                    >
                </div>
            <? endif ?>

            <? if ($field["PROPERTY_TYPE"] == "ADDRESS") : ?>
                <div class="ffeed__field">
                    <input class="ffeed__input"
                           type="text"
                           name="<?=$field["CODE"]?>"
                           data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                           data-msg-required="<?=$field["ERROR_MSG"]?>"
                           placeholder="<?=$field["NAME"]?><?=($isRequired) ? "*" : ""?>"
                        <?=($isDisabled) ? "disabled" : ""?>
                    >
                </div>
            <? endif ?>

            <? if ($field["PROPERTY_TYPE"] == "PHONE") : ?>
                <div class="ffeed__field">
                    <input class="ffeed__input" 
                        type="tel" 
                        name="<?=$field["CODE"]?>"
                        data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                        data-msg-tel="<?=$field["ERROR_MSG"]?>" 
                        data-msg-required="<?=$field["ERROR_MSG"]?>"
                        data-mask="<?=$arSettings["WEBCOMP_STRING_PHONE_MASK"]?>"
                        placeholder="<?=$field["NAME"]?><?=($isRequired) ? "*" : ""?>"
                        <?=($isDisabled) ? "disabled" : ""?>
                    >
                </div>
            <? endif ?>

            <? if ($field["PROPERTY_TYPE"] == "EMAIL") : ?>
                <div class="ffeed__field">
                    <input class="ffeed__input" 
                        type="email" 
                        name="<?=$field["CODE"]?>" 
                        data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                        data-msg-email="<?=$field["ERROR_MSG"]?>" 
                        data-msg-required="<?=$field["ERROR_MSG"]?>"
                        placeholder="<?=$field["NAME"]?><?=($isRequired) ? "*" : ""?>"
                        <?=($isDisabled) ? "disabled" : ""?>
                    >
                </div>
            <? endif ?>

        <? endforeach?>

        <div class="ffeed__field">
          <button class="btn2 btn2_w ffeed__submit" type="submit"><?=Loc::getMessage("WEBCOMP_FORM_FOOTER_BTN_SUBMIT")?></button>
        </div>

        <? if ($arSettings["WEBCOMP_CHECKBOX_USE_POLICY"] == "Y"): ?>
            <div class="ffeed__policy">

                <div class="popup__policy_left">
                    <input type="checkbox" data-msg-required="Согласитесь с условиями" name="policy_check" value="1" required <?=$arSettings["WEBCOMP_CHECKBOX_DEFAULT_CHECK"] == "Y" ? "checked" : ""?>>
                </div>

                <div class="popup__policy_right">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/include/".$arSettings["WEBCOMP_EDITOR_FORM_POLICY_TEXT"],
                        )
                    );?>
                </div>
            </div>
        <? endif ?>

          <input type="hidden" name="IBLOCK_ID" value="<?=$arResult["IBLOCK"]["ID"]?>">
          <input type="hidden" name="EMAIL_EVENT_ID" value="<?=$arResult["EMAIL_EVENT_ID"]?>">
          <input type="hidden" name="FORM_NAME" value="<?=$arResult["FORM_NAME"]?>">
          <input type="hidden" name="TOKEN">
          <?=bitrix_sessid_post()?>
          <input type="hidden" name="EVENT" value="sendForm">

      </div>
    </div>
  </div>
</div>
</form>


