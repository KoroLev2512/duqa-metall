<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $totalPrice;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);

$totalPrice = 0;

?>


     <div class="btable__list">

    <? foreach ($arResult["ITEMS"] as $key => $item): ?>

        <?
            $bPrdCompared = false;
            $bPrdFavorite = false;

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
            if (isset($_SESSION["FAVORITE"][$result["ID"]])) {
                $bPrdFavorite = true;
            }

        ?>

        <div class="citem citem_basket">
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
                            <span class="item__price price"><?=$result["PRICE"]?> РУБ.</span>
                            <? if (!empty($result["OLD_PRICE"])): ?>
                                <span class="item__priceold priceold"><?=$result["OLD_PRICE"]?> руб.</span>
                            <? endif ?>
                        </div>
                    <? endif ?>
                    

                    <span class="citem__title"><?=$result["NAME"]?></span>
                </div>
                <div class="citem__calc">
                    <div class="citem__count count jsCartCount">
                        <button class="citem__count-minus count__minus count__btn" type="button">-</button>
                        <input class="citem__count-input count__input" type="number" min="1" value="<?=$result["COUNT"]?>" step="1"
                               max="999">
                        <button class="citem__count-plus count__plus count__btn" type="button">+</button>
                    </div>
                    <div class="citem__total"><?=$result["TOTAL_PRICE"]?> РУБ.</div>
                </div>
                <div class="citem__btns">
                    <button class="citem__btn jsCompare <?=($bPrdCompared) ? "active" : ""?>" type="button" " data-action="Compare" data-id="<?=$result["ID"]?>">
                    <svg class="citem__btn-svg citem__btn-svg_compare">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                    </svg>
                    </button>
                    <button class="citem__btn jsFavorite <?=($bPrdFavorite) ? "active" : ""?>" type="button" data-action="Favorite" data-id="<?=$result["ID"]?>">
                        <svg class="citem__btn-svg citem__btn-svg_heart">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                        </svg>
                    </button>
                    <button class="citem__del" type="button"
                            data-action="delPrdOfBasket"
                            data-id="<?=$result["ID"]?>">
                        <svg class="citem__del-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#close"></use>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <? $totalPrice += $result["~TOTAL_PRICE"] ?>
    <? endforeach ?>

</div>


