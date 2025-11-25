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


<form class="ccall" method="POST" action="/ajax/form/">
    <div class="ccall__title"><?=$arResult["IBLOCK"]["NAME"]?></div>
    <? if (!empty($arResult["IBLOCK"]["DESCRIPTION"])): ?>
        <div class="ccall__subtitle"><?=$arResult["IBLOCK"]["DESCRIPTION"]?></div>
    <? endif ?>
    <div class="ccall__row row ccall__container">

        <div class="ccall__col">

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
                    <div class="ccall__field">
                        <input class="ccall__input"
                           type="text"
                           name="<?=$field["CODE"]?>"
                           placeholder="<?=$field["NAME"]?><?=($isRequired) ? "*" : ""?>"
                           data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                           data-msg-required="<?=$field["ERROR_MSG"]?>"
                            <?=($isDisabled) ? "disabled" : ""?>
                        >
                    </div>
                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "ADDRESS") : ?>
                    <div class="ccall__field">
                        <input class="ccall__input"
                               type="text"
                               name="<?=$field["CODE"]?>"
                               placeholder="<?=$field["NAME"]?><?=($isRequired) ? "*" : ""?>"
                               data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                               data-msg-required="<?=$field["ERROR_MSG"]?>"
                            <?=($isDisabled) ? "disabled" : ""?>
                        >
                    </div>
                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "PHONE") : ?>
                    <div class="ccall__field">
                        <input class="ccall__input"
                           type="tel"
                           name="<?=$field["CODE"]?>"
                           placeholder="<?=$field["NAME"]?><?=($isRequired) ? "*" : ""?>"
                           data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                           data-msg-required="<?=$field["ERROR_MSG"]?>"
                           data-msg-tel="<?=$field["ERROR_MSG"]?>"
                           data-mask="<?=$arSettings["WEBCOMP_STRING_PHONE_MASK"]?>"
                            <?=($isDisabled) ? "disabled" : ""?>
                        >
                    </div>
                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "EMAIL") : ?>
                    <div class="ccall__field">
                        <input class="ccall__input"
                           type="email"
                           name="<?=$field["CODE"]?>"
                           placeholder="<?=$field["NAME"]?><?=($isRequired) ? "*" : ""?>"
                           data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                           data-msg-required="<?=$field["ERROR_MSG"]?>"
                           data-msg-email="<?=$field["ERROR_MSG"]?>"
                            <?=($isDisabled) ? "disabled" : ""?>
                        >
                    </div>
                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "TEXT") : ?>
                    </div><div class="ccall__col">
                    <div class="ccall__field">

                        <textarea class="ccall__input ccall__input_area"
                            name="<?=$field["CODE"]?>"
                            placeholder="<?=$field["NAME"]?><?=($isRequired) ? "*" : ""?>"
                            data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                            data-msg-required="<?=$field["ERROR_MSG"]?>"
                            <?=($isDisabled) ? "disabled" : ""?>></textarea>

                    </div>
                <? endif ?>

            <? endforeach ?>

        </div>

        <div class="ccall__col ccall__col_small">
            <? if ($arSettings["WEBCOMP_CHECKBOX_USE_POLICY"] == "Y"): ?>
            <div class="ccall__col_row">
                <div class="popup__policy_left">
                    <input type="checkbox" data-msg-required="Согласитесь с условиями" name="policy_check" value="1" required <?=$arSettings["WEBCOMP_CHECKBOX_DEFAULT_CHECK"] == "N" ? "checked" : ""?>>
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
            <button class="ccall__submit btn" type="submit">ОТПРАВИТЬ ЗАПРОС</button>

            <input type="hidden" name="IBLOCK_ID" value="<?=$arResult["IBLOCK"]["ID"]?>">
            <input type="hidden" name="EMAIL_EVENT_ID" value="<?=$arResult["EMAIL_EVENT_ID"]?>">
            <input type="hidden" name="FORM_NAME" value="<?=$arResult["FORM_NAME"]?>">
            <input type="hidden" name="TOKEN">
            <?=bitrix_sessid_post()?>
            <input type="hidden" name="EVENT" value="sendForm">
        </div>


    </div>
</form>


