<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$totalPrice = 0;

?>

<div class="cart__middle popup__middle">
    <div class="cart__list" data-type="cartList">


        <? foreach ($arResult["ITEMS"] as $key => $item): ?>
            <?
                $bPrdCompared = false;
                $bPrdFavorite = false;

                $result = [
                    "ID" => $item["ID"],
                    "NAME" => $item["NAME"],
                    "PREVIEW_PICTURE" => CFile::getPath($item["PREVIEW_PICTURE"]),
                    "DETAIL_PAGE_URL" => $item["DETAIL_PAGE_URL"],
                    "AVAILABLE" => "В наличии",
                    "PRICE" => CMarketCatalog::getPrice($item["PROPERTIES"]["PRICE"]["VALUE"]),
                    "~PRICE" => $item["PROPERTIES"]["PRICE"]["VALUE"],
                    "OLD_PRICE" => CMarketCatalog::getPrice($item["PROPERTIES"]["OLD_PRICE"]["VALUE"]),
                    "~OLD_PRICE" => $item["PROPERTIES"]["OLD_PRICE"]["VALUE"],
                    "COUNT" => $_SESSION["CART"][$item["ID"]],
                    "TOTAL_PRICE" => CMarketCatalog::getPrice($_SESSION["CART"][$item["ID"]] * $item["PROPERTIES"]["PRICE"]["VALUE"]),
                    "~TOTAL_PRICE" => $_SESSION["CART"][$item["ID"]] * $item["PROPERTIES"]["PRICE"]["VALUE"],
                    "ECONOMY" => (!empty($item["PROPERTIES"]["OLD_PRICE"]["VALUE"])) ? $item["PROPERTIES"]["OLD_PRICE"]["VALUE"] - $item["PROPERTIES"]["PRICE"]["VALUE"] : 0
                ];

                // Проверка добавлен ли уже товар в корзину
                if (isset($_SESSION["COMPARE"][$result["ID"]])) {
                    $bPrdCompared = true;
                }

                // Проверка добавлен ли уже товар в корзину
                if (isset($_SESSION["FAVORITE"][$result["ID"]])) {
                    $bPrdFavorite = true;
                }

            ?>

            <div class="citem" data-type="cartItem"
                 data-id="<?=$result["ID"]?>"
                 data-price="<?=$result["~PRICE"]?>"
                 data-economy="<?=$result["ECONOMY"]?>">
                <div class="citem__top">
                    <a class="citem__img" href="<?=$result["DETAIL_PAGE_URL"]?>">
                        <span class="citem__img-wrap">
                            <img class="citem__img-img" src="<?=$result["PREVIEW_PICTURE"]?>" alt="<?=$result["NAME"]?>"/>
                        </span>
                    </a>
                </div>
                <div class="citem__bottom">
                    <div class="citem__content">
                        <div class="citem__avaible">
                            <div class="citem__avaible__round"></div>
                            <div class="citem__avaible__txt"><?=$result["AVAILABLE"]?></div>
                        </div>
                        <? if (!empty($result["PRICE"])): ?>
                            <div class="citem__prices">
                                <span class="item__price price"><?=$result["PRICE"]?></span>
                                <? if (!empty($result["OLD_PRICE"])): ?>
                                    <span class="item__priceold priceold"><?=$result["OLD_PRICE"]?></span>
                                <? endif ?>
                            </div>
                        <? endif ?>


                        <span class="citem__title"><?=$result["NAME"]?></span>
                    </div>
                    <div class="citem__calc">
                        <div class="citem__count count">
                            <button class="citem__count-minus count__minus count__btn" type="button">-</button>
                            <input class="citem__count-input count__input"
                                   type="number"
                                   min="1"
                                   value="<?=$result["COUNT"]?>"
                                   step="1"
                                   max="999"
                                   data-event="changeQuantityInCart"
                                   data-request="/ajax/catalog/"
                                   data-id="<?=$result["ID"]?>"
                                   data-type="cartItemQuantity"
                            >
                            <button class="citem__count-plus count__plus count__btn" type="button">+</button>
                        </div>
                        <div class="citem__total" data-type="cartItemTotalPrice"><?=$result["TOTAL_PRICE"]?></div>
                    </div>
                    <div class="citem__btns">
                        <button class="citem__btn <?=($bPrdCompared) ? "active" : ""?>"
                                type="button"
                                data-event="changeCompareList"
                                data-request="/ajax/catalog/"
                                data-id="<?=$result["ID"]?>">
                            <?=CMarketView::showIcon("compare", "citem__btn-svg citem__btn-svg_compare")?>
                        </button>
                        <button class="citem__btn <?=($bPrdFavorite) ? "active" : ""?>"
                                type="button"
                                data-event="changeFavoriteList"
                                data-request="/ajax/catalog/"
                                data-id="<?=$result["ID"]?>">
                            <?=CMarketView::showIcon("heart", "citem__btn-svg citem__btn-svg_heart")?>
                        </button>
                        <button class="citem__del"
                                type="button"
                                data-event="deleteProductInCart"
                                data-request="/ajax/catalog/"
                                data-id="<?=$result["ID"]?>">
                            <?=CMarketView::showIcon("close", "citem__del-svg")?>
                        </button>
                    </div>
                </div>
            </div>

            <? $totalPrice += $result["~TOTAL_PRICE"] ?>
        <? endforeach ?>

    </div>

    <div class="cart__total">
        <div class="cart__total-left">
            <button class="cart__clear"
                    type="button"
                    data-event="clearCart"
                    data-request="/ajax/cart/">
                <span class="cart__clear-img">
                    <?=CMarketView::showIcon("close", "cart__clear-svg")?>
                </span>
                <span class="cart__clear-txt">очистить корзину</span>
            </button>
        </div>
        <div class="cart__total-right" id="tpl_totalFloatCartContainer">
            <div class="cart__total-txt">Итого:</div>
            <div class="cart__total-val"><?=CMarketCatalog::getPrice($totalPrice)?></div>
        </div>
    </div>

</div>

<template id="tpl_totalFloatCart">
    <div class="cart__total-txt">Итого:</div>
    <div class="cart__total-val">{{TOTAL_PRICE}}</div>
</template>

<template id="tpl_emptyCart">
    <div class="cart-empty">
        <div class="cart-empty__title"><?= Loc::getMessage("WEBCOMP_ORDER_EMPTY") ?></div>
        <div class="cart-empty__text"><?= Loc::getMessage("WEBCOMP_ORDER_EMPTY_TEXT") ?>
            <span class="popup__btn-img">
                <?=CMarketView::showIcon("cart", "popup__btn-svg popup__btn-svg_compare bread__link-svg")?>
            </span>
        </div>
        <a class="cart-empty__btn btn" href="/catalog/">
            <span><?= Loc::getMessage("WEBCOMP_ORDER_GO_CATALOG") ?></span>
        </a>
    </div>
</template>




