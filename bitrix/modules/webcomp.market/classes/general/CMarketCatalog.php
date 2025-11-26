<?php

use Bitrix\Main\Localization\Loc;
use Webcomp\Market\Settings;
Loc::loadMessages(__FILE__);

/**
 * Class CMarketCatalog
 * Класс для обработки каталога
 */
class CMarketCatalog extends CMarket
{
    private static $currency;

    /**
     * Метод возвращает корректное значение для вывода цены
     * @param $price
     * @return string
     */
    public static function getPrice($price)
    {


        if(!isset($GLOBALS["WEBCOMP"]["SETTINGS"])) {
            $GLOBALS["WEBCOMP"]["SETTINGS"] = Settings::GetGlobalSettings();
        }

        // TODO: Скорее всего стоит прокидывать ID товара и из него уже забирать цену, скидки, валюты и т.д.
        if (!empty($price)) {
            $decimal = ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_STRING_DECIMAL"]) ?: 0;
            $decimal_point = ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_STRING_DECIMAL_POINT"]) ?: ".";
            $thousandth_seporator = ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_STRING_THOUSANDTH_SEPORATOR"]) ?: " ";

            $formatPrice = number_format((float) $price, $decimal, $decimal_point, $thousandth_seporator);
            $formatPrice .= " " . self::getCurrency();
        }

        return $formatPrice ?: "";
    }

    /**
     * Метод возвращает корректную валюту
     * @return string
     */
    private static function getCurrency()
    {
        // TODO: Забирать из highload блока валют основную валюту
        return Loc::getMessage("WEBCOMP_MARKET_CURRENCY");
    }

    public static function renderItem($item, $bCanBuy, $bShowStickers) {
        ob_start();
        ?>
        <div class="item <?= ($bCanBuy) ? "" : "no-available" ?>">
            <div class="item__top">
                <div class="item__img">
                    <a href="<?= $item["URL"] ?>" class="item__img-wrap">
                        <img class="item__img-img" src="<?= $item["PICTURE"] ?>" alt="<?= $item["NAME"] ?>">
                    </a>
                    <? if ($bShowStickers): ?>
                        <div class="item__sticks">
                            <div class="sticks">
                                <? foreach ($item["STICKER"] as $key => $sticker): ?>
                                    <span class="stick stick_<?= $sticker ?>"><?= GetMessage("STICKER_"
                                            .$sticker) ?></span>
                                <? endforeach ?>
                            </div>
                        </div>
                    <? endif ?>
                    <div class="item__controls">
                        <button class="item__control item__control_compare"
                                type="button"
                                data-event="changeCompareList"
                                data-request="<?= SITE_DIR ?>ajax/catalog/"
                                data-id="<?= $item["ID"] ?>">
                            <?= CMarketView::showIcon("compare",
                                "item__control-svg") ?>
                        </button>
                        <button class="item__control item__control_favorite"
                                type="button"
                                data-event="changeFavoriteList"
                                data-request="<?= SITE_DIR ?>ajax/catalog/"
                                data-id="<?= $item["ID"] ?>">
                            <?= CMarketView::showIcon("heart",
                                "item__control-svg") ?>
                        </button>
                    </div>

                </div>
            </div>
            <div class="item__bottom">
                <div class="item__content">
                    <div class="item__avaible">
                        <div class="item__avaible__round"></div>
                        <div class="item__avaible__txt"><?= $item["AVAILABLE"] ?></div>
                    </div>
                    <div class="item__prices">
                        <? if (!empty($item["PRICE"])): ?>
                            <div class="item__price price"><?= $item["PRICE"] ?></div>
                        <? endif ?>

                        <? if (!empty($item["OLD_PRICE"])): ?>
                            <div class="item__priceold priceold"><?= $item["OLD_PRICE"] ?></div>
                        <? endif ?>
                    </div>
                    <a href="<?= $item["URL"] ?>"
                       class="item__title"><?= $item["NAME"] ?></a>
                </div>
                <div class="item__btns">
                    <?= self::renderBuyBtn($item["ID"], $bCanBuy); ?>
                </div>
            </div>
            <div class="item__controls item__controls_list">
                <div class="item__control item__control_compare"
                     data-event="changeCompareList"
                     data-request="<?= SITE_DIR ?>ajax/catalog/"
                     data-id="<?= $item["ID"] ?>">
                    <?= CMarketView::showIcon("compare", "item__control-svg") ?>
                </div>
                <div class="item__control item__control_favorite"
                     data-event="changeFavoriteList"
                     data-request="<?= SITE_DIR ?>ajax/catalog/"
                     data-id="<?= $item["ID"] ?>">
                    <?= CMarketView::showIcon("heart", "item__control-svg") ?>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public static function renderBuyBtn($id, $bCanBuy) {
        \Webcomp\Market\Constants::getAllIblocks();
        ob_start();
        if (COption::GetOptionString("webcomp.market",
                "WEBCOMP_CHECKBOX_E-SHOP", "Y") === "Y"
            && $bCanBuy): ?>
            <a href="#" class="item__buy add"
               type="button"
               data-event="addToCart"
               data-request="<?= SITE_DIR ?>ajax/catalog/"
               data-id="<?= $id ?>">
                <?= CMarketView::showIcon("check", "add__svg") ?>
                <span class="add__txt"><?=Loc::getMessage("WEBCOMP_MARKET_ADD_TO_BASKET")?></span>
                <span class="add__txt2 jsCartForm"><?=Loc::getMessage("WEBCOMP_MARKET_IN_CART")?></span>
                <svg class="add__mobile">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#cart"></use>
                </svg>
            </a>
            <button type="button" class="item__fast btn3"
                    data-event="showForm"
                    data-request="<?= SITE_DIR ?>ajax/form/"
                    data-form_name="ONE_CLICK_BUY"
                    data-form_id="<?= $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_oneclick'] ?>"
                    data-email_event_id="WEBCOMP_ONE_CLICK_BUY"
                    data-elements_id="<?= $id ?>">
                <?= CMarketView::showIcon("one", "btn3__svg") ?>
                <span class="btn3__txt"><?=Loc::getMessage("WEBCOMP_MARKET_CLICK_ONE_BUY")?></span>
            </button>
        <? else: ?>
            <a href="#"
               class="item__buy add"
               data-event="showForm"
               data-request="<?= SITE_DIR ?>ajax/form/"
               data-form_name="ONE_CLICK_BUY"
               data-form_id="<?= $GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_oneclick'] ?>"
               data-email_event_id="WEBCOMP_ONE_CLICK_BUY"
               data-elements_id="<?= $id ?>">
                <span class="add__txt"><?=Loc::getMessage("WEBCOMP_MARKET_ORDER")?></span>
            </a>
        <? endif;
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
