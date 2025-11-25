<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);

?>
 <div class="cart__middle popup__middle">
    <div class="cart__list" data-type="list">

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
                "OLD_PRICE"       => number_format($item["PROPERTIES"]["OLD_PRICE"]["VALUE"]
                    ?: 0, 0, '', ' '),
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

        <div class="citem" data-type="item">
            <div class="citem__top">
                <a class="citem__img" href="<?=$result["DETAIL_PAGE_URL"]?>">
                    <span class="citem__img-wrap">
                        <img class="citem__img-img" src="<?=$result["PREVIEW_PICTURE"]?>" alt="<?=$result["NAME"]?>">
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
                            <span class="item__price price"><?=$result["PRICE"]?> РУБ.</span>
                            <? if (!empty($result["OLD_PRICE"])): ?>
                                <span class="item__priceold priceold"><?=$result["OLD_PRICE"]?> руб.</span>
                            <? endif ?>
                        </div>
                    <? endif ?>
                    <span class="citem__title"><?=$result["NAME"]?></span>
                </div>

                <div class="citem__btns">

                    <button class="citem__btn <?=($bPrdCompared) ? "active" : ""?>"
                            type="button"
                            data-event="changeCompareList"
                            data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                            data-id="<?=$result["ID"]?>">
                        <svg class="citem__btn-svg citem__btn-svg_compare">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                        </svg>
                    </button>

                    <button class="citem__btn <?=($bCanBuy) ? "active" : ""?>"
                            type="button"
                            data-event="addToCart"
                            data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                            data-id="<?=$result["ID"]?>">
                        <svg class="citem__btn-svg citem__btn-svg_cart">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg>
                    </button>

                    <button class="citem__del"
                            type="button"
                            data-event="changeFavoriteList"
                            data-ext="removeNodeFavoriteItem"
                            data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                            data-id="<?=$result["ID"]?>">
                        <svg class="citem__del-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#close"></use>
                        </svg>
                    </button>

                </div>
            </div>
        </div>

    <? endforeach ?>

</div>
</div>

<template id="tpl_emptyFavorite">
    <div class="cart-empty">
        <div class="cart-empty__title"><?= Loc::getMessage("WEBCOMP_FAVORITE_EMPTY") ?></div>
        <div class="cart-empty__text"><?= Loc::getMessage("WEBCOMP_FAVORITE_EMPTY_TEXT") ?></div>
        <a class="cart-empty__btn btn" href="#WIZARD_SITE_DIR#catalog/">
            <span><?= Loc::getMessage("WEBCOMP_ORDER_GO_CATALOG") ?></span>
        </a>
    </div>
</template>


