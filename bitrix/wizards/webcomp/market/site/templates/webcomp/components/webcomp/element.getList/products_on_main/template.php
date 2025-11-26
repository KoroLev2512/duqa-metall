<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);
global $tabIndex;

?>

<div class="irecom__tab tabset__tab" data-type="list">
    <div class="irecom__slider" data-speed="500" data-pagination="true">
        <button class="irecom__prev-<?= $tabIndex ?> arrow irecom__prev" type="button">
            <?=CMarketView::showIcon("prev", "arrow__svg")?>
        </button>
        <div class="irecom__container-<?= $tabIndex ?> swiper-container irecom__container">
            <div class="swiper-wrapper">

                <? foreach ($arResult["ITEMS"] as $key => $item): ?>

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
                        "STICKER" => []
                    ];

                    $stickers = [
                        "5" => "action",
                        "6" => "recom",
                        "7" => "new",
                        "8" => "hit"
                    ];

                    if (!empty($item["PROPERTIES"]["STICKERS"]["VALUES"])) {
                        foreach ($item["PROPERTIES"]["STICKERS"]["VALUES"] as $sticker) {
                            $result["STICKER"][$sticker["VALUE"]] = $stickers[$sticker["VALUE"]];
                        }
                    }

                    ?>

                    <div class="swiper-slide irecom__item" data-type="item">
                        <!-- Дополнительные классы - nopadding, noheight, disabled-->
                        <a class="item" href="<?= $result["DETAIL_PAGE_URL"] ?>">
                            <span class="item__top">
                                <span class="item__img">
                                     <!-- <span class="item__propportion pt pt_1x1"></span>-->
                                    <span class="item__img-wrap">
                                        <img class="item__img-img" src="<?= $result["PREVIEW_PICTURE"] ?>"
                                             alt="<?= $result["NAME"] ?>"/>
                                    </span>

                                    <? if (!empty($result["STICKER"])): ?>
                                        <span class="item__sticks">
                                            <span class="sticks">
                                                <? foreach ($result["STICKER"] as $sticker): ?>
                                                    <span class="stick stick_<?= $sticker ?>"><?= Loc::getMessage("STICKER_" . $sticker) ?></span>
                                                <? endforeach ?>
                                            </span>
                                        </span>
                                    <? endif ?>

                                    <span class="item__controls">
                                        <span class="item__control item__control_compare"
                                              type="button"
                                              data-event="changeCompareList"
                                              data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                                              data-id="<?= $result["ID"] ?>">
                                            <?=CMarketView::showIcon("compare", "item__control-svg")?>
                                        </span>

                                        <span class="item__control item__control_favorite"
                                              type="button"
                                              data-event="changeFavoriteList"
                                              data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                                              data-id="<?= $result["ID"] ?>">
                                            <?=CMarketView::showIcon("heart", "item__control-svg")?>
                                        </span>

                                    </span>
                                </span>
                            </span>
                            <span class="item__bottom">
                                <span class="item__content">
                                    <span class="item__avaible">
                                        <span class="item__avaible__round"></span>
                                        <span class="item__avaible__txt"><?= $result["AVAILABLE"] ?></span>
	      		                    </span>
                                    <? if (!empty($result["PRICE"])): ?>
                                        <span class="item__prices">
                                            <span class="item__price price"><?= $result["PRICE"] ?></span>
                                            <? if (!empty($result["OLD_PRICE"])): ?>
                                                <span class="item__priceold priceold"><?= $result["OLD_PRICE"] ?></span>
                                            <? endif ?>
                                        </span>
                                    <? endif ?>
	      		                    <span class="item__title"><?= $result["NAME"] ?></span>
	      	                    </span>
                                <span class="item__btns">

                                    <span class="item__buy add"
                                          type="button"
                                          data-event="addToCart"
                                          data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                                          data-id="<?= $result["ID"] ?>">
                                        <?=CMarketView::showIcon("check", "add__svg")?>
                                        <span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span>
                                        <span class="add__txt2 jsCartForm">ТОВАР В КОРЗИНЕ</span>
                                        <?=CMarketView::showIcon("cart", "add__mobile")?>
                                    </span>

                                    <span class="item__fast btn3"
                                          data-event="showForm"
                                          data-request="#WIZARD_SITE_DIR#ajax/form/"
                                          data-form_name="ONE_CLICK_BUY"
                                          data-form_id=<?=$GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_oneclick']?> data-email_event_id="WEBCOMP_ONE_CLICK_BUY"
                                        data-elements_id="<?= $result["ID"] ?>">
                                        <?=CMarketView::showIcon("one", "btn3__svg")?>
                                        <span class="btn3__txt">Купить в 1 клик</span>
                                    </span>

                                </span>
                            </span>
                            <span class="item__controls item__controls_list">

                                <span class="item__control item__control_compare"
                                      type="button"
                                      data-event="changeCompareList"
                                      data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                                      data-id="<?= $result["ID"] ?>">
                                    <?=CMarketView::showIcon("compare", "item__control-svg")?>
                                </span>

                                <span class="item__control item__control_favorite"
                                      type="button"
                                      data-event="changeFavoriteList"
                                      data-request="#WIZARD_SITE_DIR#ajax/catalog/"
                                      data-id="<?= $result["ID"] ?>">
                                    <?=CMarketView::showIcon("heart", "item__control-svg")?>
                                </span>

                            </span>
                        </a>
                    </div>
                <? endforeach ?>

            </div>
        </div>

        <button class="irecom__next-<?= $tabIndex ?> arrow irecom__next" type="button">
            <?=CMarketView::showIcon("next", "arrow__svg")?>
        </button>
    </div>

    <div class="irecom__pag-<?= $tabIndex ?> irecom__pag">
        <div class="pag"></div>
    </div>
</div>