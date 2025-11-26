<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc;

global $WEBCOMP, $cartCount;
$arSettings = $WEBCOMP["SETTINGS"];

$cartForm = $APPLICATION->IncludeComponent(
	"webcomp:form", 
	".default", 
	array(
		"CACHE_FILTER" => "N",
		"CACHE_TIME" => "0",
		"CACHE_TYPE" => "A",
		"ELEMENTS_COUNT" => "20",
		"FIELD_CODE" => "",
		"FILTER_NAME" => "",
		"IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_order_form'],
		"IBLOCK_TYPE" => "forms",
		"PROPERTY_CODE" => "",
		"SHOW_ONLY_ACTIVE" => "Y",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "NAME",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"COMPONENT_TEMPLATE" => ".default",
		"EMAIL_EVENT_ID" => "WEBCOMP_NEW_ORDER",
		"BIND_ELEMENTS" => "",
		"FORM_NAME" => "ORDER",
		"DONT_INCLUDE_TEMPLATE" => "Y"
	),
	false
);

?>

<? if(!empty($cartForm["FIELDS"])): ?>
    <div class="basket__row">
        <div class="brow">
            <div class="brow__info">
                <div class="brow__header brow__header_m">
                    <svg class="brow__header-svg brow__header-svg_cart">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#basket"></use>
                    </svg>
                    <div class="brow__header-txt"><?=$cartForm["IBLOCK"]["NAME"]?></div>
            </div>
            <div class="brow__header">
                <div class="brow__header-txt">
                    <?=Loc::getMessage("WEBCOMP_ORDER_CART_TITLE")?>
                    <span class="tpl_productsCountContainer">
                        <b>
                            <?= \Webcomp\Market\Tools::num2word($cartCount,
                            ['товар', 'товара', 'товаров']); ?>
                        </b>
                    </span>
                </div>
            </div>
        </div>

        <? if(!empty($cartForm["IBLOCK"]["DESCRIPTION"])): ?>
            <div class="brow__title"><?=$cartForm["IBLOCK"]["DESCRIPTION"]?></div>
        <? endif ?>
        <div class="brow__content">
            <div class="basket__fields">
                <div class="row">
                    <? foreach ($cartForm["FIELDS"] as $field): ?>

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

                            <div class="basket__field basket__field_8">
                                <label class="basket__label"><?=$field["NAME"]?>&nbsp;
                                    <? if($isRequired): ?>
                                        <span class="req">*</span>
                                    <? endif ?>
                                </label>

                                <input class="basket__input"
                                   type="text"
                                   name="<?=$field["CODE"]?>"
                                   data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                                   data-msg-required="<?=$field["ERROR_MSG"]?>"
                                    <?=($isDisabled) ? "disabled" : ""?>
                                >
                            </div>

                        <? endif ?>

                        <? if ($field["PROPERTY_TYPE"] == "ADDRESS") : ?>

                            <? $this->SetViewTarget('order_address_field') ?>
                            <div class="brow__bottom" id="additionalField">
                                <div class="bdelivery__addr">
                                    <div class="bdelivery__addr-info"><?=$field["NAME"]?>
                                        <? if($isRequired): ?>
                                            <span class="req">*</span>
                                        <? endif ?>
                                    </div>
                                    <input class="bdelivery__addr-input"
                                           type="text"
                                           name="<?=$field["CODE"]?>"
                                           data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                                           data-msg-required="<?=$field["ERROR_MSG"]?>"
                                        <?=($isDisabled) ? "disabled" : ""?>
                                    >
                                </div>
                            </div>
                            <? $this->EndViewTarget() ?>

                        <? endif ?>

                        <? if ($field["PROPERTY_TYPE"] == "PHONE") : ?>

                            <div class="basket__field basket__field_8">
                                <label class="basket__label"><?=$field["NAME"]?>&nbsp;
                                    <? if($isRequired): ?>
                                        <span class="req">*</span>
                                    <? endif ?>
                                </label>

                                <input class="basket__input"
                                   type="tel"
                                   name="<?=$field["CODE"]?>"
                                   data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                                   data-msg-tel="<?=$field["ERROR_MSG"]?>"
                                   data-msg-required="<?=$field["ERROR_MSG"]?>"
                                   data-mask="<?=$arSettings["WEBCOMP_STRING_PHONE_MASK"]?>"
                                   placeholder="<?=$arSettings["WEBCOMP_STRING_PHONE_MASK"]?>"
                                <?=($isDisabled) ? "disabled" : ""?>
                                >
                            </div>

                        <? endif ?>

                        <? if ($field["PROPERTY_TYPE"] == "EMAIL") : ?>

                            <div class="basket__field basket__field_8">
                                <label class="basket__label"><?=$field["NAME"]?>&nbsp;
                                    <? if($isRequired): ?>
                                        <span class="req">*</span>
                                    <? endif ?>
                                </label>

                                <input class="basket__input"
                                   type="email"
                                   name="<?=$field["CODE"]?>"
                                   data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                                   data-msg-email="<?=$field["ERROR_MSG"]?>"
                                   data-msg-required="<?=$field["ERROR_MSG"]?>"
                                    <?=($isDisabled) ? "disabled" : ""?>
                                >
                            </div>

                        <? endif ?>

                        <? if ($field["PROPERTY_TYPE"] == "TEXT") : ?>

                            <div class="basket__field basket__field_24">
                                <label class="basket__label"><?=$field["NAME"]?>&nbsp;
                                    <? if($isRequired): ?>
                                        <span class="req">*</span>
                                    <? endif ?>
                                </label>

                                <textarea class="basket__input basket__input_area"
                                      name="<?=$field["CODE"]?>"
                                      data-rule-required="<?=($isRequired) ? "true" : "false"?>"
                                      data-msg-required="<?=$field["ERROR_MSG"]?>"
                                      <?=($isDisabled) ? "disabled" : ""?>
                                ></textarea>
                            </div>

                        <? endif ?>


                    <? endforeach ?>

                    <input type="hidden" name="IBLOCK_ID" value="<?=$cartForm["IBLOCK"]["ID"]?>">
                    <input type="hidden" name="EMAIL_EVENT_ID" value="<?=$cartForm["EMAIL_EVENT_ID"]?>">
                    <input type="hidden" name="FORM_NAME" value="<?=$cartForm["FORM_NAME"]?>">
                    <?=bitrix_sessid_post()?>
                    <input type="hidden" name="EVENT" value="sendOrderForm">


                </div>
            </div>
        </div>
    </div>
</div>
<? endif ?>