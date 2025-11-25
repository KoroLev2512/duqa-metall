<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);

?>
<div class="basket__table btable">
    <table class="btable__table">
        <tr class="btable__top">
            <td class="btable__w-1 btable__th" colspan="2">
                <div class="btable__title">
                    <svg class="btable__title-svg btable__title-svg_cart">
                        <use xlink:href="/images/icons/sprite.svg#basket"></use>
                    </svg>
                    <div class="btable__title-txt btable__title-txt_15b">
                        Избранные товары
                    </div>
                </div>
            </td>
            <td class="btable__w-3 btable__th">
                <div class="btable__title btable__title_center">
                    <div class="btable__title-txt">В избранных:&nbsp;
                        <span class="tpl_favoriteCountContainer">
                            <b class="count_favorite"><?= \Webcomp\Market\Tools::num2word(count($_SESSION["FAVORITE"]),
                                ['товар', 'товара', 'товаров']); ?>
                            </b>
                        </span>
                    </div>
                </div>
            </td>
            <td class="btable__w-4 btable__th">
                <button class="btable__title favorite__clear"
                        type="button"
                        data-event="clearFavoriteList"
                        data-request="/ajax/catalog/">
                    <svg class="btable__title-svg btable__title-svg_close">
                        <use xlink:href="/images/icons/sprite.svg#close"></use>
                    </svg>
                    <span class="btable__title-txt btable__title-txt_m">Очистить</span>
                </button>
            </td>
        </tr>
        <tr class="btable__headers">
            <td class="btable__w-1">
                <div class="btable__head btable__head_b">Товар</div>
                <div class="btable__title btable__title_m">
                    <div class="btable__title-txt">В избранных:&nbsp;
                        <span class="tpl_favoriteCountContainer">
                            <b class="count_favorite"><?= \Webcomp\Market\Tools::num2word(count($_SESSION["FAVORITE"]),
                                ['товар', 'товара', 'товаров']); ?>
                            </b>
                        </span>
                    </div>
                </div>
            </td>
            <td class="btable__w-2">
                <div class="btable__head">Цена</div>
            </td>
            <td class="btable__w-3">
                <div class="btable__head"></div>
            </td>
            <td class="btable__w-4">
                <div class="btable__head"></div>
            </td>
        </tr>
    </table>
    <div class="btable__list" data-type="list">
        <? foreach ($arResult["ITEMS"] as $key => $item): ?>
            <?
            $bPrdCompared = false;
            $bCanBuy = false;

            $result = [
                "ID"              => $item["ID"],
                "NAME"            => $item["NAME"],
                "PREVIEW_PICTURE" => CFile::getPath($item["PREVIEW_PICTURE"]),
                "DETAIL_PAGE_URL" => $item["DETAIL_PAGE_URL"],
                "AVAILABLE"       => "В наличии",
                "PRICE"           => number_format($item["PROPERTIES"]["PRICE"]["VALUE"],
                    0, '', ' '),
                "~PRICE"          => $item["PROPERTIES"]["PRICE"]["VALUE"],
                "OLD_PRICE" => number_format($item["PROPERTIES"]["OLD_PRICE"]["VALUE"]
                    ?: 0,
                    0, '', ' '),
                "~OLD_PRICE"      => $item["PROPERTIES"]["OLD_PRICE"]["VALUE"],
                "COUNT"           => $_SESSION["CART"][$item["ID"]],
                "TOTAL_PRICE"     => number_format($_SESSION["CART"][$item["ID"]]
                    * $item["PROPERTIES"]["PRICE"]["VALUE"], 0, '', ' '),
                "~TOTAL_PRICE"    => $_SESSION["CART"][$item["ID"]]
                    * $item["PROPERTIES"]["PRICE"]["VALUE"],
            ];

            // Проверка добавлен ли уже товар в корзину
            if (isset($_SESSION["COMPARE"][$result["ID"]])) {
                $bPrdCompared = true;
            }

            // Проверка добавлен ли уже товар в корзину
            if (isset($_SESSION["CART"][$result["ID"]])) {
                $bCanBuy = true;
            }

            ?>
            <div class="citem_basket citem_favorite citem" data-type="item">
                <div class="citem__top"><a class="citem__img"
                                           href="<?= $result["DETAIL_PAGE_URL"] ?>">
                    <span class="citem__img-wrap">
                        <img class="citem__img-img"
                             src="<?= $result["PREVIEW_PICTURE"] ?>"
                             alt="<?= $result["NAME"] ?>"/>
                    </span>
                    </a>
                    <div class="citem__btns citem__btns_m">
                        <button class="citem__btn citem__btn_compare"
                                type="button"
                                data-event="changeCompareList"
                                data-request="/ajax/catalog/"
                                data-id="<?= $result["ID"] ?>">
                            <svg class="citem__btn-svg citem__btn-svg_compare">
                                <use xlink:href="/images/icons/sprite.svg#compare"></use>
                            </svg>
                        </button>
                        <button class="citem__btn citem__btn_cart"
                                type="button"
                                data-event="addToCart"
                                data-request="/ajax/catalog/"
                                data-id="<?= $result["ID"] ?>">
                            <svg class="citem__btn-svg citem__btn-svg_heart">
                                <use xlink:href="/images/icons/sprite.svg#cart"></use>
                            </svg>
                        </button>
                        <button class="citem__del"
                                type="button"
                                data-event="changeFavoriteList"
                                data-ext="removeNodeFavoriteItem"
                                data-request="/ajax/catalog/"
                                data-id="<?= $result["ID"] ?>">
                            <svg class="citem__del-svg">
                                <use xlink:href="/images/icons/sprite.svg#close"></use>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="citem__bottom">
                    <div class="citem__content">
                        <div class="citem__avaible">
                            <div class="citem__avaible__round"></div>
                            <div class="citem__avaible__txt"><?= $result["AVAILABLE"] ?></div>
                        </div>
                        <? if ($item["PROPERTIES"]["PRICE"]["VALUE"]==0): ?>
                            <div class="citem__prices">
                                <span class="item__price price">Договорная</span>
                            </div>
                        <? elseif ( ! empty($result["PRICE"])): ?>
                            <div class="citem__prices">
                                <span class="item__price price"><?= $result["PRICE"] ?> РУБ.</span>
                                <? if ( ! empty($result["OLD_PRICE"])): ?>
                                    <span class="item__priceold priceold"><?= $result["OLD_PRICE"] ?> руб.</span>
                                <? endif ?>
                            </div>
                        <? endif ?>
                        <a class="citem__title"
                           href="<?= $result["DETAIL_PAGE_URL"] ?>"><?= $result["NAME"] ?></a>
                    </div>
                    <div class="citem__btns">
                        <button class="citem__btn"
                                type="button"
                                data-event="changeCompareList"
                                data-request="/ajax/catalog/"
                                data-id="<?= $result["ID"] ?>">
                            <svg class="citem__btn-svg citem__btn-svg_compare">
                                <use xlink:href="/images/icons/sprite.svg#compare"></use>
                            </svg>
                        </button>
                        <button class="citem__btn citem__btn_cart"
                                type="button"
                                data-event="addToCart"
                                data-request="/ajax/catalog/"
                                data-id="<?= $result["ID"] ?>">
                            <svg class="citem__btn-svg citem__btn-svg_heart">
                                <use xlink:href="/images/icons/sprite.svg#cart"></use>
                            </svg>
                        </button>
                        <button class="citem__del"
                                type="button"
                                data-event="changeFavoriteList"
                                data-ext="removeNodeFavoriteItem"
                                data-request="/ajax/catalog/"
                                data-id="<?= $result["ID"] ?>">
                            <svg class="citem__del-svg">
                                <use xlink:href="/images/icons/sprite.svg#close"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        <? endforeach ?>
    </div>
</div>

<template id="tpl_favoriteCount">
    <b class="count_favorite">{{COUNT_PRD}}</b>
</template>

<template id="tpl_emptyFavorite">
    <div class="cart-empty">
        <div class="cart-empty__title"><?= Loc::getMessage("WEBCOMP_FAVORITE_EMPTY") ?></div>
        <div class="cart-empty__text"><?= Loc::getMessage("WEBCOMP_FAVORITE_EMPTY_TEXT") ?></div>
        <a class="cart-empty__btn btn" href="/catalog/">
            <span><?= Loc::getMessage("WEBCOMP_ORDER_GO_CATALOG") ?></span>
        </a>
    </div>
</template>


