<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$APPLICATION->SetTitle("Заказ оформлен"); ?>

<? if (isset($_SESSION["ORDER"]) && $_SESSION["ORDER"]["STATUS"] === true): ?>

    <?
    global $arrFilter;

    $arResult = [];

    $arrFilter = ["ID" => $_SESSION["ORDER"]["ORDER_ID"]];

    $orderInfo = current($APPLICATION->IncludeComponent(
        "webcomp:highload.getList",
        ".default",
        array(
            "CACHE_TIME" => "0",
            "CACHE_TYPE" => "A",
            "ELEMENTS_COUNT" => "20",
            "FIELD_CODE" => array(
                0 => "ID",
                1 => "UF_STATUS",
                2 => "UF_DELIVERY_ID",
                3 => "UF_PAYMENT_ID",
                4 => "UF_SUM",
                5 => "UF_DELIVERY_PRICE",
                6 => "UF_FIO",
                7 => "UF_PHONE",
                8 => "UF_EMAIL",
                9 => "UF_COMMENT",
                10 => "UF_ADDRESS",
            ),
            "HLBLOCK_ID" => $GLOBALS['WEBCOMP']['HLBLOCKS']['WebCompMarketOrders'],
            "USE_FILTER" => "Y",
            "USE_SORT" => "Y",
            "COMPONENT_TEMPLATE" => ".default",
            "SORT_FILED" => "ID",
            "SORT_ORDER" => "ASC",
            "FILTER_NAME" => "arrFilter",
            "DONT_INCLUDE_TEMPLATE" => "Y"
        ),
        false
    )["ITEMS"]);

    if (!empty($orderInfo)) {
        $arResult["ORDER"] = [
            "ID" => $orderInfo["ID"],
            "UF_DELIVERY_ID" => $orderInfo["UF_DELIVERY_ID"]["VALUE"] ?: false,
            "UF_PAYMENT_ID" => $orderInfo["UF_PAYMENT_ID"]["VALUE"] ?: false,
            "PRODUCT_PRICE" => $orderInfo["UF_SUM"]["VALUE"],
            "TOTAL_PRICE" => $orderInfo["UF_SUM"]["VALUE"],
            "USER_INFO" => [
                "NAME" => $orderInfo["UF_FIO"]["VALUE"],
                "PHONE" => $orderInfo["UF_PHONE"]["VALUE"],
                "EMAIL" => $orderInfo["UF_EMAIL"]["VALUE"],
                "ADDRESS" => $orderInfo["UF_ADDRESS"]["VALUE"],
                "COMMENT" => $orderInfo["UF_COMMENT"]["VALUE"],
            ],
        ];
    }


    if ($arResult["ORDER"]["UF_DELIVERY_ID"]) {
        $arrFilter = ["ID" => $arResult["ORDER"]["UF_DELIVERY_ID"]];
        $delivery = current($APPLICATION->IncludeComponent(
            "webcomp:highload.getList",
            ".default",
            array(
                "CACHE_TIME" => "0",
                "CACHE_TYPE" => "A",
                "ELEMENTS_COUNT" => "20",
                "FIELD_CODE" => array(
                    0 => "ID",
                    1 => "UF_NAME",
                    2 => "UF_PRICE_FOR_USER",
                    3 => "UF_PRICE",
                ),
                "HLBLOCK_ID" => $GLOBALS['WEBCOMP']['HLBLOCKS']['WebCompMarketDelivery'],
                "USE_FILTER" => "Y",
                "USE_SORT" => "Y",
                "COMPONENT_TEMPLATE" => ".default",
                "SORT_FILED" => "ID",
                "SORT_ORDER" => "ASC",
                "FILTER_NAME" => "arrFilter",
                "DONT_INCLUDE_TEMPLATE" => "Y"
            ),
            false
        )["ITEMS"]);

        if (!empty($delivery)) {

            unset($arResult["ORDER"]["UF_DELIVERY_ID"]);

            $arResult["ORDER"]["DELIVERY"] = [
                "ID" => $delivery["ID"],
                "NAME" => $delivery["UF_NAME"]["VALUE"],
                "PRICE" => ($delivery["UF_PRICE"]["VALUE"] > 0)
                    ? number_format($delivery["UF_PRICE"]["VALUE"], 0, ".", " ") . " руб."
                    : $delivery["UF_PRICE_FOR_USER"]["VALUE"],
            ];

            $arResult["ORDER"]["TOTAL_PRICE"] += $delivery["UF_PRICE"]["VALUE"];
        }

    }

    if ($arResult["ORDER"]["UF_PAYMENT_ID"]) {
        $arrFilter = ["ID" => $arResult["ORDER"]["UF_PAYMENT_ID"]];
        $pay = current($APPLICATION->IncludeComponent(
            "webcomp:highload.getList",
            ".default",
            array(
                "CACHE_TIME" => "0",
                "CACHE_TYPE" => "A",
                "ELEMENTS_COUNT" => "20",
                "FIELD_CODE" => array(
                    0 => "ID",
                    1 => "UF_NAME",
                    2 => "UF_DESCRIPTION",
                ),
                "HLBLOCK_ID" => $GLOBALS['WEBCOMP']['HLBLOCKS']['WebCompMarketPayments'],
                "USE_FILTER" => "Y",
                "USE_SORT" => "Y",
                "COMPONENT_TEMPLATE" => ".default",
                "SORT_FILED" => "ID",
                "SORT_ORDER" => "ASC",
                "FILTER_NAME" => "arrFilter",
                "DONT_INCLUDE_TEMPLATE" => "Y"
            ),
            false
        )["ITEMS"]);

        if (!empty($pay)) {
            unset($arResult["ORDER"]["UF_PAYMENT_ID"]);

            $arResult["ORDER"]["PAY"] = [
                "ID" => $pay["ID"],
                "NAME" => $pay["UF_NAME"]["VALUE"],
                "DESCRIPTION" => $pay["UF_DESCRIPTION"]["VALUE"],
            ];
        }
    }

    $arrFilter = ["UF_ORDER_ID" => $_SESSION["ORDER"]["ORDER_ID"]];

    $orderProducts = $APPLICATION->IncludeComponent(
        "webcomp:highload.getList",
        ".default",
        array(
            "CACHE_TIME" => "0",
            "CACHE_TYPE" => "A",
            "ELEMENTS_COUNT" => "20",
            "FIELD_CODE" => array(
                0 => "ID",
                1 => "UF_NAME",
                2 => "UF_QUANTITY",
                3 => "UF_PRICE",
            ),
            "HLBLOCK_ID" => $GLOBALS['WEBCOMP']['HLBLOCKS']['WebCompMarketOrderPosition'],
            "USE_FILTER" => "Y",
            "USE_SORT" => "Y",
            "COMPONENT_TEMPLATE" => ".default",
            "SORT_FILED" => "ID",
            "SORT_ORDER" => "ASC",
            "FILTER_NAME" => "arrFilter",
            "DONT_INCLUDE_TEMPLATE" => "Y"
        ),
        false
    )["ITEMS"];

    if (!empty($orderProducts)) {
        foreach ($orderProducts as $key => $item) {
            $arResult["ORDER"]["PRODUCTS"][$key] = [
                "NAME" => $item["UF_NAME"]["VALUE"],
                "QUANTITY" => $item["UF_QUANTITY"]["VALUE"],
                "PRICE" => $item["UF_PRICE"]["VALUE"],
                "TOTAL_PRICE" => CMarketCatalog::getPrice($item["UF_PRICE"]["VALUE"] * $item["UF_QUANTITY"]["VALUE"]),
            ];
        }
    }

    $arResult["ORDER"]["TOTAL_PRICE"] = CMarketCatalog::getPrice($arResult["ORDER"]["TOTAL_PRICE"]);

    ?>


    <section class="ok__main">
        <div class="container">
            <div class="row">
                <div class="ok__left">
                    <? if ($WEBCOMP["SETTINGS"]["WEBCOMP_CHECKBOX_LK"] === "Y"): ?>
                        <div class="ok__block">
                            <!--<div class="bblock">-->
                            <!--    <div class="bblock__title">-->
                            <!--        <svg class="bblock__title-svg">-->
                            <!--            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#cabinet"></use>-->
                            <!--        </svg>-->
                            <!--        <div class="bblock__title-txt">Личный кабинет</div>-->
                            <!--    </div>-->
                                <!--<div class="bblock__content">-->
                                <!--    <div class="bblock__info">-->
                                <!--        <div class="bblock__info-left">-->
                                <!--            <img class="bblock__info-img"-->
                                <!--                 src="<?= SITE_TEMPLATE_PATH ?>/images/icons/info.svg"-->
                                <!--                 alt="lorem"/>-->
                                <!--        </div>-->
                                <!--        <div class="bblock__info-right">-->
                                <!--            <div class="bblock__info-txt">-->
                                <!--                Зарегистрируйтесь на сайте,-->
                                <!--                чтобы получить-->
                                <!--                доступ-->
                                <!--                в личный кабинет и вести архив и-->
                                <!--                отслеживать статус заказов-->
                                <!--            </div>-->
                                <!--            <div class="bblock__info-links">-->
                                <!--                <a class="bblock__info-link"-->
                                <!--                   href="/personal/register/">Зарегистрироваться</a>-->
                                <!--                <a class="bblock__info-link"-->
                                <!--                   href="/personal/">-->
                                <!--                    <svg class="bblock__info-svg">-->
                                <!--                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#cabinet"></use>-->
                                <!--                    </svg>-->
                                <!--                    Войти-->
                                <!--                </a>-->
                                <!--            </div>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div>-->
                            <!--</div>-->
                        </div>
                    <? endif; ?>
                    <div class="ok__consult catalog__consult">
                        <div class="catalog__consult-top">
                            <div class="catalog__consult-image">
                                <img class="catalog__consult-img"
                                     src="<?= SITE_TEMPLATE_PATH ?>/images/content/catalog/img.svg" alt="lorem"/>
                            </div>
                            <div class="catalog__consult-title">Нужна консультация?</div>
                            <div class="catalog__consult-txt">Наши специалисты ответят на любой интересующий вопрос
                            </div>
                        </div>
                        <button class="catalog__consult-btn" data-trigger="click" data-target="QUESTION" type="button">
                            ЗАДАТЬ ВОПРОС
                        </button>
                    </div>
                </div>
                <div class="ok__middle">
                    <h1 class="title ok__title">Спасибо за заказ!</h1>
                    <div class="ok__sub">Мы свяжемся с вами в ближайшее время для уточнения деталей оплаты и доставки.
                    </div>
                    <div class="ok__num">
                        <div class="oknum">
                            <div class="oknum__left">
                                <svg class="oknum__svg">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#chech-round"></use>
                                </svg>
                            </div>
                            <div class="oknum__right">
                                <div class="oknum__item">Номер вашего
                                    заказа:&nbsp;<b><?= $arResult["ORDER"]["ID"] ?></b></div>
                                <div class="oknum__item">Сумма:&nbsp;<b><?= $arResult["ORDER"]["TOTAL_PRICE"] ?></b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ok__info">
                        <div class="okinfo">
                            <div class="okinfo__title">Иформация о заказе:</div>
                            <div class="okinfo__list">
                                <? if (isset($arResult["ORDER"]["PRODUCTS"])): ?>
                                    <? foreach ($arResult["ORDER"]["PRODUCTS"] as $item): ?>
                                        <div class="okinfo__item">
                                            <div class="okinfo__item-wrap">
                                                <div class="okinfo__item-txt">
                                                    <div class="okinfo__item-text"><?= $item["NAME"] ?>
                                                        (<?= $item["QUANTITY"] ?> шт.)
                                                    </div>
                                                </div>
                                                <div class="okinfo__item-prices">
                                                    <div class="okinfo__item-price"><?= $item["TOTAL_PRICE"] ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <? endforeach ?>
                                <? endif ?>
                                <? if (isset($arResult["ORDER"]["DELIVERY"])): ?>
                                    <div class="okinfo__item okinfo__item_delivery">
                                        <div class="okinfo__item-title">Доставка:</div>
                                        <div class="okinfo__item-wrap">
                                            <div class="okinfo__item-txt">
                                                <div class="okinfo__item-text"><?= $arResult["ORDER"]["DELIVERY"]["NAME"] ?></div>
                                            </div>
                                            <div class="okinfo__item-prices">
                                                <div class="okinfo__item-price"><?= $arResult["ORDER"]["DELIVERY"]["PRICE"] ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <? endif ?>
                            </div>

                            <div class="okinfo__details">
                                <? if (!empty($arResult["ORDER"]["USER_INFO"]["ADDRESS"])): ?>
                                    <div class="okinfo__detail">
                                        <div class="okinfo__detail-img">
                                            <img class="okinfo__detail-pic"
                                                 src="<?= SITE_TEMPLATE_PATH ?>/images/icons/car2.svg"
                                                 alt="Адрес доставки"/>
                                        </div>
                                        <div class="okinfo__detail-txt"><?= $arResult["ORDER"]["USER_INFO"]["ADDRESS"] ?></div>
                                    </div>
                                <? endif ?>
                                <? if (isset($arResult["ORDER"]["PAY"])): ?>
                                    <div class="okinfo__detail">
                                        <div class="okinfo__detail-img">
                                            <img class="okinfo__detail-pic"
                                                 src="<?= SITE_TEMPLATE_PATH ?>/images/icons/wallet.svg"
                                                 alt="Способ оплаты"/>
                                        </div>
                                        <div class="okinfo__detail-txt"><?= $arResult["ORDER"]["PAY"]["NAME"] ?></div>
                                    </div>
                                <? endif ?>
                            </div>

                        </div>
                    </div>
                    <div class="ok__btn">
                        <a class="ok__back" href="/catalog/">
                            <svg class="ok__back-svg">
                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow-s-left"></use>
                            </svg>
                            <span class="ok__back-txt">Вернуться к покупкам</span>
                        </a>
                    </div>
                </div>
                <div class="ok__right">
                    <div class="ok__client">
                        <div class="okclient">
                            <div class="okclient__title">Информация о клиенте:</div>
                            <div class="okclient__list">

                                <div class="okclient__item">
                                    <? if (isset($arResult["ORDER"]["USER_INFO"])): ?>
                                        <? foreach ($arResult["ORDER"]["USER_INFO"] as $key => $info): ?>
                                            <? if ($key == "COMMENT") continue ?>
                                            <? if (!empty($info)) echo $info . "<br>" ?>
                                        <? endforeach ?>
                                    <? endif ?>
                                </div>
                                <? if (isset($arResult["ORDER"]["USER_INFO"]) && !empty($arResult["ORDER"]["USER_INFO"]["COMMENT"])): ?>
                                    <div class="okclient__item"><?= $arResult["ORDER"]["USER_INFO"]["COMMENT"] ?></div>
                                <? endif ?>
                            </div>
                            <div class="okclient__btn">
                                <a class="ok__back"
                                   href="/catalog/">
                                    <svg class="ok__back-svg">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow-s-left"></use>
                                    </svg>
                                    <span class="ok__back-txt">Вернуться к покупкам</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<? else: ?>
    <? LocalRedirect("/cart/"); ?>
<? endif ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>