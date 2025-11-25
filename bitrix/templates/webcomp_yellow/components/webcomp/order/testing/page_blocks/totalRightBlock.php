<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc;

global $totalPrice, $totalDelivery, $totalOldPrice;
$total =  CMarketCatalog::getPrice($totalDelivery  + $totalPrice);
$totalProductPrice = CMarketCatalog::getPrice($totalPrice);
$totalDeliveryPrice = (intval($totalDelivery) === 0)
    ? "Бесплатно"
    : CMarketCatalog::getPrice($totalDelivery);

?>

<div class="basket__block">
    <div class="bblock__title">
        <div class="bblock__title-txt"><?=Loc::getMessage("WEBCOMP_ORDER_TITLE")?></div>
    </div>
    <div class="bblock__content" id="tpl_totalRightContainer">
        <div class="basket__order">
            <div class="border">
                <div class="border__list">
                    <div class="border__item">
                        <div class="border__item-wrap">
                            <div class="border__item-title"><?=Loc::getMessage("WEBCOMP_ORDER_PRODUCTS_FOR")?></div>
                            <div class="border__item-prices">
                                <div class="border__item-price"><?=$totalProductPrice?></div>
                                <? if( ($totalOldPrice - $totalPrice ) > 0): ?>
                                    <div class="border__item-oldprice" data-if="OLD_PRICE"><?=CMarketCatalog::getPrice($totalOldPrice);?></div>
                                <? endif ?>
                            </div>
                        </div>
                    </div>
                    <? if( isset($totalDelivery) ): ?>
                        <div class="border__item" data-if="DELIVERY">
                            <div class="border__item-wrap">
                                <div class="border__item-title"><?=Loc::getMessage("WEBCOMP_ORDER_DELIVERY")?></div>
                                <div class="border__item-prices">
                                    <div class="border__item-price"><?=$totalDeliveryPrice?></div>
                                </div>
                            </div>
                        </div>
                    <? endif ?>
                </div>

                <? if( ($totalOldPrice - $totalPrice ) > 0): ?>
                    <div class="border__discount" data-if="ECONOMY">
                        <div class="border__discount-title"><?=Loc::getMessage("WEBCOMP_ORDER_ECONOMY")?></div>
                        <div class="border__discount-price"><?=CMarketCatalog::getPrice($totalOldPrice - $totalPrice);?></div>
                    </div>
                <? endif ?>

                <div class="border__total">
                    <div class="border__total-title"><?=Loc::getMessage("WEBCOMP_ORDER_TOTAL_TEXT")?></div>
                    <div class="border__total-price"><?=$total?></div>
                </div>
                <div class="border__btns">
                    <button class="btn2 border__submit" type="submit" data-event="orderSubmit"><?=Loc::getMessage("WEBCOMP_ORDER_SUBMIT_BTN_SMALL")?></button>
                    <button class="btn3 border__fast"
                            type="button"
                            data-event="showForm"
                            data-request="/ajax/cart/"
                            data-form_name="ORDER"
                            data-form_id=<?=$GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_fastorder']?>
                            data-email_event_id="WEBCOMP_NEW_ORDER">
                        <svg class="btn3__svg">
                            <use xlink:href="/images/icons/sprite.svg#one"></use>
                        </svg>
                        <span class="btn3__txt"><?=Loc::getMessage("WEBCOMP_ORDER_CLICK_ONE_BUY")?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="tpl_totalRight">
    <div class="basket__order">
        <div class="border">
            <div class="border__list">
                <div class="border__item">
                    <div class="border__item-wrap">
                        <div class="border__item-title"><?=Loc::getMessage("WEBCOMP_ORDER_PRODUCTS_FOR")?></div>
                        <div class="border__item-prices">
                            <div class="border__item-price">{{TOTAL_PRD_PRICE}}</div>
                            <div class="border__item-oldprice" data-if="OLD_PRICE">{{TOTAL_PRD_OLD_PRICE}}</div>
                        </div>
                    </div>
                </div>
                <div class="border__item" data-if="DELIVERY">
                    <div class="border__item-wrap">
                        <div class="border__item-title"><?=Loc::getMessage("WEBCOMP_ORDER_DELIVERY")?></div>
                        <div class="border__item-prices">
                            <div class="border__item-price">{{TOTAL_DELIVERY_PRICE}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border__discount" data-if="ECONOMY">
                <div class="border__discount-title"><?=Loc::getMessage("WEBCOMP_ORDER_ECONOMY")?></div>
                <div class="border__discount-price">{{TOTAL_ECONOMY}}</div>
            </div>
            <div class="border__total">
                <div class="border__total-title"><?=Loc::getMessage("WEBCOMP_ORDER_TOTAL_TEXT")?></div>
                <div class="border__total-price">{{TOTAL_PRICE}}</div>
            </div>
            <div class="border__btns">
                <button class="btn2 border__submit" type="submit" data-event="orderSubmit"><?=Loc::getMessage("WEBCOMP_ORDER_SUBMIT_BTN_SMALL")?></button>
                <button class="btn3 border__fast"
                        type="button"
                        data-event="showForm"
                        data-request="/ajax/cart/"
                        data-form_name="ORDER"
                        data-form_id=<?=$GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_fastorder']?>
                        data-email_event_id="WEBCOMP_NEW_ORDER">
                    <svg class="btn3__svg">
                        <use xlink:href="/images/icons/sprite.svg#one"></use>
                    </svg>
                    <span class="btn3__txt"><?=Loc::getMessage("WEBCOMP_ORDER_CLICK_ONE_BUY")?></span>
                </button>
            </div>
        </div>
    </div>
</template>
