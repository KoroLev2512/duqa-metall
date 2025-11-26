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

<div class="popup__top">
    <div class="popup__title"><?=$arResult["IBLOCK"]["NAME"]?></div>
    <? if (!empty($arResult["IBLOCK"]["DESCRIPTION"])): ?>
        <div class="popup__subtitle"><?=$arResult["IBLOCK"]["DESCRIPTION"]?></div>
    <? endif ?>
    <button class="popup__close jsFormClose" type="button">
        <svg class="popup__close-svg">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/images/icons/sprite.svg#close"></use>
        </svg>
    </button>
</div>
<div class="popup__middle">
    <form class="popup__form" action="#WIZARD_SITE_DIR#ajax/form/" method="POST"
          enctype="multipart/form-data">

        <div class="popup__fields">

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

                        <? if(isset($element["QUANTITY"])): ?>
                            <input type="hidden" name="QUANTITY" value="<?=$element["QUANTITY"]?>">
                        <? endif ?>
                    <? else: ?>
                        <input type="hidden" name="<?=$field["CODE"]?>" value="">
                    <? endif ?>

                    <? if(!$isDisabled) continue ?>

                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "STRING") : ?>
                    <div class="popup__field">
                        <input class="popup__input"
                            type="text"
                            name="<?=$field["CODE"]?>"
                            data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                            data-msg-required="<?=$field["ERROR_MSG"]?>"
                            <?=($isDisabled) ? "disabled" : ""?>
                        >
                        <div class="popup__placeholder"><?=$field["NAME"]?>
                            <? if($isRequired): ?>
                                <i>*</i>
                            <? endif ?>
                        </div>
                    </div>
                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "ADDRESS") : ?>
                    <div class="popup__field">
                        <input class="popup__input"
                               type="text"
                               name="<?=$field["CODE"]?>"
                               data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                               data-msg-required="<?=$field["ERROR_MSG"]?>"
                            <?=($isDisabled) ? "disabled" : ""?>
                        >
                        <div class="popup__placeholder"><?=$field["NAME"]?>
                            <? if($isRequired): ?>
                                <i>*</i>
                            <? endif ?>
                        </div>
                    </div>
                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "PHONE") : ?>
                    <div class="popup__field">
                        <input class="popup__input"
                            type="tel"
                            name="<?=$field["CODE"]?>"
                            data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                            data-msg-tel="<?=$field["ERROR_MSG"]?>"
                            data-msg-required="<?=$field["ERROR_MSG"]?>"
                            data-mask="<?=$arSettings["WEBCOMP_STRING_PHONE_MASK"]?>"
                            <?=($isDisabled) ? "disabled" : ""?>
                        >
                        <div class="popup__placeholder"><?=$field["NAME"]?>
                            <? if($isRequired): ?>
                                <i>*</i>
                            <? endif ?>
                        </div>
                    </div>
                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "EMAIL") : ?>

                    <div class="popup__field">
                        <input class="popup__input"
                            type="email"
                            name="<?=$field["CODE"]?>"
                            data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                            data-msg-email="<?=$field["ERROR_MSG"]?>"
                            data-msg-required="<?=$field["ERROR_MSG"]?>"
                            <?=($isDisabled) ? "disabled" : ""?>
                        >
                        <div class="popup__placeholder"><?=$field["NAME"]?>
                            <? if($isRequired): ?>
                                <i>*</i>
                            <? endif ?>
                        </div>
                    </div>
                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "BIND") : ?>

                    <div class="popup__field">
                        <? if(isset($field["ELEMENTS"])):?>
                            <? foreach ($field["ELEMENTS"] as $element): ?>
                                <input class="popup__input"
                                       type="hidden"
                                       name="<?= $field["CODE"] ?>[]"
                                       value="<?= $element["ID"] ?>">
                                <?
                                if ($field["CODE"] == "ELEMENT") {
                                    $isDisabled = true;
                                }
                                ?>
                                <input class="popup__input"
                                       type="text"
                                    <?= ($isDisabled) ? "disabled" : "" ?>
                                       value="<?= $element["NAME"] ?>">
                            <? endforeach ?>

                        <? else: ?>
                            <input class="popup__input"
                                   type="text"
                                   name="<?= $field["CODE"] ?>"
                                   data-rule-required="<?= ($isRequired)
                                       ? "true" : "false" ?>"
                                   data-msg-required="<?= $field["ERROR_MSG"] ?>"
                                <?= ($isDisabled) ? "disabled" : "" ?>
                            >

                        <? endif ?>
                        <div class="popup__placeholder <?=(isset($field["ELEMENTS"])) ? "active" : ""?>"><?=$field["NAME"]?>
                            <? if($isRequired): ?>
                                <i>*</i>
                            <? endif ?>
                        </div>
                    </div>
                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "FILE") : ?>
                    <div class="popup__field ">
                        <div class="popup__label"><?=$field["NAME"]?>
                            <? if($isRequired): ?>
                                <i>*</i>
                            <? endif ?>
                        </div>
                        <label class="file">
                            <input class="file__input"
                                type="file"
                                name="<?=$field["CODE"]?>"
                                data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                                data-msg-required="<?=$field["ERROR_MSG"]?>"
                                <?=($isDisabled) ? "disabled" : ""?>
                            >
                            <span class="file__fake">
                              <svg class="file__svg">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/images/icons/sprite.svg#staple"></use>
                              </svg>
                              <span class="file__title"><?=Loc::getMessage("WEBCOMP_FORM_FILE_ADD")?></span>
                              <button class="file__del" type="button">
                                <svg class="file__del-svg">
                                  <use xlink:href="<?=SITE_TEMPLATE_PATH?>/images/icons/sprite.svg#close"></use>
                                </svg>
                              </button>
                            </span>
                        </label>
                    </div>
                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "TEXT") : ?>
                    <div class="popup__field">
                        <div class="popup__label"><?=$field["NAME"]?>
                            <? if($isRequired): ?>
                                <i>*</i>
                            <? endif ?>
                        </div>
                        <textarea class="popup__area"
                            name="<?=$field["CODE"]?>"
                            data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                            data-msg-required="<?=$field["ERROR_MSG"]?>"
                            <?=($isDisabled) ? "disabled" : ""?>></textarea>
                    </div>

                <? endif ?>

                <? if ($field["PROPERTY_TYPE"] == "RATING") : ?>
                    <div class="popup__field">

                        <div class="popup__rating">

                            <div class="rating2">
                                <div class="rating2__wrapper">
                                    <? for ($i = $field["MAX_VALUE"]; $i > $field["MIN_VALUE"]; $i--): ?>
                                        <input class="rating2__input radio__input" type="radio" name="<?=$field["CODE"]?>" value="<?=$i?>" id="rating-<?=$i?>">
                                        <label class="rating2__star" for="rating-<?=$i?>" data-rate="<?=$i?>">
                                            <svg class="rating2__star-svg">
                                                <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#star"></use>
                                            </svg>
                                        </label>
                                    <? endfor ?>
                                </div>
                                <div class="rating2__txt">Без оценки</div>
                            </div>
                        </div>

                    </div>

                <? endif ?>

            <? endforeach ?>

        </div>

        <? if ($arSettings["WEBCOMP_CHECKBOX_USE_POLICY"] == "Y"): ?>
            <div class="popup__policy">

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

        <button class="btn popup__submit" type="submit"><?=Loc::getMessage("WEBCOMP_FORM_BTN_SUBMIT")?></button>
        <input type="hidden" name="IBLOCK_ID" value="<?=$arResult["IBLOCK"]["ID"]?>">
        <input type="hidden" name="EMAIL_EVENT_ID" value="<?=$arResult["EMAIL_EVENT_ID"]?>">
        <input type="hidden" name="FORM_NAME" value="<?=$arResult["FORM_NAME"]?>">
        <?=bitrix_sessid_post()?>
        <input type="hidden" name="EVENT" value="sendForm">

    </form>
</div>


