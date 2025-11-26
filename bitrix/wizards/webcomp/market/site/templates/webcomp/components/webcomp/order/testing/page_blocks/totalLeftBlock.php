<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc;

global $totalPrice, $totalDelivery;
$total =  CMarketCatalog::getPrice($totalDelivery  + $totalPrice);
$totalProductPrice = CMarketCatalog::getPrice($totalPrice);
$totalDeliveryPrice = (intval($totalDelivery) === 0)
    ? "Бесплатно"
    : CMarketCatalog::getPrice($totalDelivery);
?>


<div class="basket__total btotal" id="tpl_totalBottomContainer">
    <div class="btotal__col">
        <div class="btotal__item">
            <div class="btotal__item-left">
                <div class="btotal__item-icon">
                    <svg class="btotal__item-svg btotal__item-svg_cart">
                        <use xlink:href="/images/icons/sprite.svg#basket"></use>
                    </svg>
                </div>
            </div>
            <div class="btotal__item-right">
                <div class="btotal__item-title"><?=Loc::getMessage("WEBCOMP_ORDER_FOR")?></div>
                <div class="btotal__item-price"><?=$totalProductPrice?></div>
            </div>
        </div>
    </div>
    <div class="btotal__col">
        <div class="btotal__item">
            <div class="btotal__item-left">
                <div class="btotal__item-icon">
                    <svg class="btotal__item-svg btotal__item-svg_car">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/images/icons/sprite.svg#car"></use>
                    </svg>
                </div>
            </div>
            <div class="btotal__item-right">
                <div class="btotal__item-title"><?=Loc::getMessage("WEBCOMP_ORDER_DELIVERY")?></div>
                <div class="btotal__item-price"><?=$totalDeliveryPrice?></div>
            </div>
        </div>
    </div>
    <div class="btotal__col">
        <div class="btotal__price">
            <div class="btotal__price-txt"><?=Loc::getMessage("WEBCOMP_ORDER_TOTAL_PRICE")?></div>
            <div class="btotal__price-price"><?=$total?></div>
        </div>
    </div>
    <div class="btotal__col">
        <button class="btotal__submit" type="submit" data-event="orderSubmit"><?=Loc::getMessage("WEBCOMP_ORDER_SUBMIT_BTN")?></button>
    </div>
</div>

<template id="tpl_totalBottom">
    <div class="btotal__col">
        <div class="btotal__item">
            <div class="btotal__item-left">
                <div class="btotal__item-icon">
                    <svg class="btotal__item-svg btotal__item-svg_cart">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/images/icons/sprite.svg#basket"></use>
                    </svg>
                </div>
            </div>
            <div class="btotal__item-right">
                <div class="btotal__item-title"><?=Loc::getMessage("WEBCOMP_ORDER_FOR")?></div>
                <div class="btotal__item-price">{{TOTAL_PRD_PRICE}}</div>
            </div>
        </div>
    </div>
    <div class="btotal__col" data-if="DELIVERY">
        <div class="btotal__item">
            <div class="btotal__item-left">
                <div class="btotal__item-icon">
                    <svg class="btotal__item-svg btotal__item-svg_car">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/images/icons/sprite.svg#car"></use>
                    </svg>
                </div>
            </div>

                <div class="btotal__item-right">
                    <div class="btotal__item-title"><?=Loc::getMessage("WEBCOMP_ORDER_DELIVERY")?></div>
                    <div class="btotal__item-price">{{TOTAL_DELIVERY_PRICE}}</div>
                </div>

        </div>
    </div>
    <div class="btotal__col">
        <div class="btotal__price">
            <div class="btotal__price-txt"><?=Loc::getMessage("WEBCOMP_ORDER_TOTAL_PRICE")?></div>
            <div class="btotal__price-price">{{TOTAL_PRICE}}</div>
        </div>
    </div>
    <div class="btotal__col">
        <button class="btotal__submit" type="submit" data-event="orderSubmit"><?=Loc::getMessage("WEBCOMP_ORDER_SUBMIT_BTN")?></button>
    </div>
</template>
