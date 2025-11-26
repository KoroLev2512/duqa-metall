<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);

?>
<div class="compare__head">
    <div class="compare__th">
        <svg class="compare__th-svg compare__th-svg_compare">
            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
        </svg>
        <div class="compare__th-txt"><b>Сравнение товаров</b></div>
    </div>
    <div class="compare__th">
        <div class="compare__th-txt">В сравнении:&nbsp;
            <span class="tpl_compareCountContainer">
                <b class="count_compare"><?= \Webcomp\Market\Tools::num2word(count($_SESSION["COMPARE"]),
                        ['товар', 'товара', 'товаров']); ?></b></span>
        </div>
    </div>
    <div class="compare__th">
        <a class="compare__reset" data-action="delAllPrdOfCompare">
            <svg class="compare__th-svg compare__th-svg_close">
                <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#close"></use>
            </svg>
            <div class="compare__th-txt">Очистить</div>
        </a>
    </div>
</div>
<div class="compare__slider" data-speed="500" data-pagination="true">
    <button class="compare__prev arrow compare__prev" type="button">
        <svg class="arrow__svg">
            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#prev"></use>
        </svg>
    </button>
    <div class="swiper-container compare__container" data-type="list">
        <div class="swiper-wrapper">

            <?$arCompare = []?>

            <? foreach ($arResult["ITEMS"] as $key => $item): ?>

            <?
                $bPrdFavorited = false;
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

            // Проверка добавлен ли уже товар в избранное
            if (isset($_SESSION["FAVORITE"][$result["ID"]])) {
                $bPrdFavorited = true;
            }

            // Проверка добавлен ли уже товар в корзину
            if (isset($_SESSION["CART"][$result["ID"]])) {
                $bCanBuy = true;
            }

            // опеределяем какие свойтва нужно показывать
            foreach ($item["PROPERTIES"] as $propertyName => $property) {
                if(in_array($propertyName, ["AVAILABLE", "BRAND"]))
                    continue;
                if(!empty($property["VALUE"])) $arCompare[$propertyName] = 1;
            }

            ?>
            <div class="swiper-slide compare__item" data-type="item">
                <!-- Дополнительные классы - nopadding, noheight, disabled-->
                <a class="item" href="<?=$result["DETAIL_PAGE_URL"]?>">
                    <span class="item__top">
                        <span class="item__img">
                            <span class="item__img-wrap">
                                <img class="item__img-img" src="<?=$result["PREVIEW_PICTURE"]?>" alt="<?=$result["NAME"]?>"/>
                            </span>
                            <!--<span class="item__sticks">
                                <span class="sticks">
                                    <span class="stick stick_hit">Хит</span>
                                    <span class="stick stick_recom">Советуем</span>
                                    <span class="stick stick_new">Новинка</span>
                                    <span class="stick stick_action">Акция</span>
                                </span>
                            </span>-->
                            <span class="item__controls">
                                <span class="item__control item__control_favorite"
                                      data-event="changeFavoriteList"
                                      data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                                      data-id="<?=$result["ID"]?>">
                                  <svg class="item__control-svg">
                                    <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                                  </svg>
                                </span>

                                <span class="item__control item__control_close"
                                      data-event="changeCompareList"
                                      data-ext="removeNodeCompareItem"
                                      data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                                      data-id="<?=$result["ID"]?>">
                                      <svg class="item__control-svg">
                                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#close"></use>
                                      </svg>
                                </span>
                            </span>
                        </span>
                    </span>
                    <span class="item__bottom">
                        <span class="item__content"><span class="item__avaible">
                                <span class="item__avaible__round"></span>
                                <span class="item__avaible__txt"><?=$result["AVAILABLE"]?></span>
                            </span>
                            <? if (!empty($result["PRICE"])): ?>
                                <span class="item__prices">
                                    <span class="item__price price"><?= $result["PRICE"] ?> РУБ.</span>
                                    <? if ( ! empty($result["OLD_PRICE"])): ?>
                                        <span class="item__priceold priceold"><?= $result["OLD_PRICE"] ?> руб.</span>
                                    <? endif ?>
                                </span>
                            <? endif ?>
                            <span class="item__title"><?= $result["NAME"] ?></span>
                        </span>
                        <span class="item__btns">
                            <span class="item__buy add"
                                  data-event="addToCart"
                                  data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                                  data-id="<?= $item["ID"] ?>">
                                <svg class="add__svg">
                                  <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                                </svg>
                                <span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span>
                                <span class="add__txt2 jsCartForm">ТОВАР В КОРЗИНЕ</span>
                                <svg class="add__mobile">
                                  <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                                </svg>
                            </span>
                            <span class="item__fast btn3"
                                  data-event="showForm"
                                  data-request="#WIZARD_SITE_DIR#ajax/form/"
                                  data-form_name="ONE_CLICK_BUY"
                                  data-form_id=<?=$GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_oneclick']?>
                                  data-email_event_id="WEBCOMP_ONE_CLICK_BUY"
                                  data-elements_id="<?= $item["ID"] ?>">
                                <svg class="btn3__svg">
                                  <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                                </svg>
                                <span class="btn3__txt">Купить в 1 клик</span>
                            </span>
                        </span>
                    </span>
                    <span class="item__controls item__controls_list">
                        <span class="item__control item__control_favorite"
                              data-event="changeFavoriteList"
                              data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                              data-id="<?=$result["ID"]?>">
                              <svg class="item__control-svg">
                                <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                              </svg>
                        </span>
                        <span class="item__control item__control_close"
                              data-event="changeCompareList"
                              data-ext="removeNodeCompareItem"
                              data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                              data-id="<?=$result["ID"]?>">
                              <svg class="item__control-svg">
                                <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#close"></use>
                              </svg>
                        </span>
                    </span>
                </a>
            </div>
            <?endforeach;?>
        </div>
    </div>
    <button class="compare__next arrow compare__next" type="button">
        <svg class="arrow__svg">
            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#next"></use>
        </svg>
    </button>
</div>
<div class="compare__pag">
    <div class="pag"></div>
</div>

<div class="compare__slider2" data-speed="500">
    <div class="swiper-container compare__container2">
        <div class="swiper-wrapper">
            <?$keyRow = 1?>
            <? foreach ($arResult["ITEMS"] as $key => $item): ?>
                <? if(!empty($item["PROPERTIES"])): ?>
                    <div class="swiper-slide compare__item2" data-slider2-compare-id="<?=$item['ID']?>">
                        <div class="cchars">
                            <? foreach ($item["PROPERTIES"] as $propName => $property): ?>
                                <?

                                if(!array_key_exists ($propName, $arCompare)) continue;

                                    if(in_array($propName, ["PRICE", "OLD_PRICE"]) && !empty($property["VALUE"]))
                                        $property["VALUE"] = number_format($property["VALUE"], 0, ".", " ") . " руб.";
                                ?>
                                <div class="cchar" data-row="<?=$keyRow?>">
                                    <div class="cchar__title"><?=$property["NAME"]?></div>
                                    <div class="cchar__val"><?=$property["VALUE"] ?: "-"?></div>
                                </div>
                            <? endforeach ?>
                        </div>
                    </div>

                    <?$keyRow++?>
                <? endif ?>
            <?endforeach;?>
        </div>
    </div>
</div>

<template id="tpl_compareCount">
    <b class="count_compare">{{COUNT_PRD}}</b>
</template>

<template id="tpl_emptyCompare">
    <div class="cart-empty">
        <div class="cart-empty__title"><?=Loc::getMessage("WEBCOMP_COMPARE_EMPTY")?></div>
        <div class="cart-empty__text"><?=Loc::getMessage("WEBCOMP_COMPARE_EMPTY_TEXT")?>
            <span class="popup__btn-img">
                  <svg class="popup__btn-svg popup__btn-svg_compare bread__link-svg">
                    <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                  </svg>
                </span>
        </div>
        <a class="cart-empty__btn btn" href="#WIZARD_SITE_DIR#catalog/">
            <span><?=Loc::getMessage("WEBCOMP_ORDER_GO_CATALOG")?></span>
        </a>
    </div>
</template>



