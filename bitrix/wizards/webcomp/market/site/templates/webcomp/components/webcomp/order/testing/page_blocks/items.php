<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

global $arrFilter, $totalPrice, $totalOldPrice, $cartCount;
$arrFilter = ["ID" => array_keys($_SESSION["CART"])];
$cartItems = $APPLICATION->IncludeComponent(
	"webcomp:element.getList", 
	".default", 
	array(
		"CACHE_FILTER" => "N",
		"CACHE_TIME" => "0",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"ELEMENTS_COUNT" => "100",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "PREVIEW_PICTURE",
			3 => "PREVIEW_TEXT",
			4 => "CODE",
		),
		"FILTER_NAME" => "arrFilter",
		"IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
		"IBLOCK_TYPE" => "content",
		"PROPERTY_CODE" => array(
			0 => "OLD_PRICE",
			1 => "PRICE",
			2 => "AVAILABLE",
			3 => "",
		),
		"SHOW_ONLY_ACTIVE" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"TITLE" => "Корзина",
		"USE_FILTER" => "Y",
		"DONT_INCLUDE_TEMPLATE" => "Y"
	),
	false
)["ITEMS"];

?>

<? if(!empty($cartItems)): ?>
<div class="basket__table btable">
    <table class="btable__table">
        <tr class="btable__top">
            <td class="btable__w-1 btable__th">
                <div class="btable__title">
                    <svg class="btable__title-svg btable__title-svg_cart">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/images/icons/sprite.svg#basket"></use>
                    </svg>
                    <div class="btable__title-txt btable__title-txt_15b"><?=Loc::getMessage("WEBCOMP_ORDER_TITLE")?></div>
                </div>
            </td>
            <td class="btable__w-2 btable__th"></td>
            <td class="btable__w-3 btable__th">
                <div class="btable__title btable__title_center">
                    <div class="btable__title-txt">
                        <?=Loc::getMessage("WEBCOMP_ORDER_CART_TITLE")?>
                        <span class="tpl_productsCountContainer">
                            <b>
                                <?= \Webcomp\Market\Tools::num2word($cartCount,
                                ['товар', 'товара', 'товаров']); ?>
                            </b>
                        </span>
                    </div>
                </div>
            </td>

            <td class="btable__w-4 btable__th">
                <button class="btable__title basket__clear"
                        type="button"
                        data-event="clearCart"
                        data-request="/ajax/cart/">
                    <svg class="btable__title-svg btable__title-svg_close">
                        <use xlink:href="/images/icons/sprite.svg#close"></use>
                    </svg>
                    <span class="btable__title-txt btable__title-txt_m"><?=Loc::getMessage("WEBCOMP_ORDER_CART_CLEAR")?></span>
                </button>
            </td>

        </tr>
        <tr class="btable__headers">
            <td class="btable__w-1">
                <div class="btable__head btable__head_b"><?=Loc::getMessage("WEBCOMP_ORDER_PRODUCT")?></div>
                <div class="btable__title btable__title_m">
                    <div class="btable__title-txt">
                        <?=Loc::getMessage("WEBCOMP_ORDER_CART_TITLE")?>
                        <span class="tpl_productsCountContainer">
                            <b>
                                <?= \Webcomp\Market\Tools::num2word(count($_SESSION["CART"]),
                                ['товар', 'товара', 'товаров']); ?>
                            </b>
                        </span>
                    </div>
                </div>
            </td>
            <td class="btable__w-2">
                <div class="btable__head"><?=Loc::getMessage("WEBCOMP_ORDER_PRICE")?></div>
            </td>
            <td class="btable__w-3">
                <div class="btable__head"><?=Loc::getMessage("WEBCOMP_ORDER_COUNT")?></div>
            </td>
            <td class="btable__w-4">
                <div class="btable__head"></div>
            </td>
        </tr>
    </table>

<div class="btable__list" data-type="cartList">

<? foreach ($cartItems as $key => $item): ?>
    <?

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
            "TOTAL_PRICE" => CMarketCatalog::getPrice(floatval($_SESSION["CART"][$item["ID"]]) * floatval($item["PROPERTIES"]["PRICE"]["VALUE"])),
            "~TOTAL_PRICE" => floatval($_SESSION["CART"][$item["ID"]]) * floatval($item["PROPERTIES"]["PRICE"]["VALUE"]),
            "TOTAL_OLD_PRICE" => number_format(floatval($_SESSION["CART"][$item["ID"]]) * floatval($item["PROPERTIES"]["OLD_PRICE"]["VALUE"]), 0, '', ' '),
            "~TOTAL_OLD_PRICE" => floatval($_SESSION["CART"][$item["ID"]]) * floatval($item["PROPERTIES"]["OLD_PRICE"]["VALUE"]),
            "ECONOMY" => (!empty($item["PROPERTIES"]["OLD_PRICE"]["VALUE"])) ? $item["PROPERTIES"]["OLD_PRICE"]["VALUE"] - $item["PROPERTIES"]["PRICE"]["VALUE"] : 0
        ];
    ?>


    <div class="citem citem_basket"
         data-type="cartItem"
         data-id="<?=$result["ID"]?>"
         data-price="<?=$result["~PRICE"]?>"
         data-economy="<?=$result["ECONOMY"]?>"
    >
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
                <div class="citem__total" data-type="cartItemTotalPrice">
                    <?=$result["TOTAL_PRICE"]?>
                </div>
            </div>
            <div class="citem__btns">

                <button class="citem__btn"
                        type="button"
                        data-event="changeCompareList"
                        data-request="/ajax/catalog/"
                        data-id="<?=$result["ID"]?>">
                    <svg class="citem__btn-svg citem__btn-svg_compare">
                        <use xlink:href="/images/icons/sprite.svg#compare"></use>
                    </svg>
                </button>

                <button class="citem__btn"
                        type="button"
                        data-event="changeFavoriteList"
                        data-request="/ajax/catalog/"
                        data-id="<?=$result["ID"]?>">
                    <svg class="citem__btn-svg citem__btn-svg_heart">
                        <use xlink:href="/images/icons/sprite.svg#heart"></use>
                    </svg>
                </button>

                <button class="citem__del"
                        type="button"
                        data-event="deleteProductInCart"
                        data-request="/ajax/catalog/"
                        data-id="<?=$result["ID"]?>">
                    <svg class="citem__del-svg">
                        <use xlink:href="/images/icons/sprite.svg#close"></use>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <? $totalPrice += $result["~TOTAL_PRICE"] ?>
    <? $totalOldPrice += ($result["~TOTAL_OLD_PRICE"]) ?: $result["~TOTAL_PRICE"] ?>
    <? endforeach ?>

    </div>

</div>

<? endif ?>
