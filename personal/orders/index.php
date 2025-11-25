<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("История заказов");?>

<?
global $arrFilter, $USER;
$userID = $USER->GetID();

$arResult = [];

$arrFilter = ["UF_USER" => (int) $userID];

$orderStatus = [
    1 => "Принят",
    2 => "Оплачен",
    3 => "Выполнен"
];

$orderInfo = $APPLICATION->IncludeComponent(
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
            11 => "UF_USER",
            12 => "UF_DATE",
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
)["ITEMS"];



if (!empty($orderInfo)) {

    foreach($orderInfo as $key => $order) {
        $arResult[$key]["ORDER"] = [
            "ID" => $order["ID"],
            "UF_DELIVERY_ID" => $order["UF_DELIVERY_ID"]["VALUE"] ?: false,
            "UF_PAYMENT_ID" => $order["UF_PAYMENT_ID"]["VALUE"] ?: false,
            "PRODUCT_PRICE" => $order["UF_SUM"]["VALUE"],
            "TOTAL_PRICE" => $order["UF_SUM"]["VALUE"],
            "USER_INFO" => [
                "USER_ID" => $order["UF_USER"]["VALUE"],
                "STATUS" => $orderStatus[$order["UF_STATUS"]["VALUE"]] ?: "В ожидании",
                "NAME" => $order["UF_FIO"]["VALUE"],
                "PHONE" => $order["UF_PHONE"]["VALUE"],
                "EMAIL" => $order["UF_EMAIL"]["VALUE"],
                "ADDRESS" => $order["UF_ADDRESS"]["VALUE"],
                "COMMENT" => $order["UF_COMMENT"]["VALUE"],
                "DATE" => $order["UF_DATE"]["VALUE"]->format("d.m.Y H:i"),
            ],
        ];

        if ($arResult[$key]["ORDER"]["UF_DELIVERY_ID"]) {
            $arrFilter = ["ID" => $arResult[$key]["ORDER"]["UF_DELIVERY_ID"]];
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
                unset($arResult[$key]["ORDER"]["UF_DELIVERY_ID"]);

                $arResult[$key]["ORDER"]["DELIVERY"] = [
                    "ID" => $delivery["ID"],
                    "NAME" => $delivery["UF_NAME"]["VALUE"],
                    "PRICE" => ($delivery["UF_PRICE"]["VALUE"] > 0)
                        ? CMarketCatalog::getPrice($delivery["UF_PRICE"]["VALUE"])
                        : $delivery["UF_PRICE_FOR_USER"]["VALUE"],
                ];

                $arResult[$key]["ORDER"]["TOTAL_PRICE"] += $delivery["UF_PRICE"]["VALUE"];
            }

        }

        if ($arResult[$key]["ORDER"]["UF_PAYMENT_ID"]) {
            $arrFilter = ["ID" => $arResult[$key]["ORDER"]["UF_PAYMENT_ID"]];
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
                unset($arResult[$key]["ORDER"]["UF_PAYMENT_ID"]);

                $arResult[$key]["ORDER"]["PAY"] = [
                    "ID" => $pay["ID"],
                    "NAME" => $pay["UF_NAME"]["VALUE"],
                    "DESCRIPTION" => $pay["UF_DESCRIPTION"]["VALUE"],
                ];
            }


            $arrFilter = ["UF_ORDER_ID" => $arResult[$key]["ORDER"]["ID"]];

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
                foreach ($orderProducts as $keyPrd => $item) {
                    $arResult[$key]["ORDER"]["PRODUCTS"][$keyPrd] = [
                        "NAME" => $item["UF_NAME"]["VALUE"],
                        "QUANTITY" => $item["UF_QUANTITY"]["VALUE"],
                        "PRICE" => $item["UF_PRICE"]["VALUE"],
                        "TOTAL_PRICE" => CMarketCatalog::getPrice($item["UF_PRICE"]["VALUE"] * $item["UF_QUANTITY"]["VALUE"]),
                    ];
                }
            }

            $arResult[$key]["ORDER"]["TOTAL_PRICE"] = CMarketCatalog::getPrice($arResult[$key]["ORDER"]["TOTAL_PRICE"]);

        }
    }
}

?>

<? if(!empty($arResult)): ?>
    <div class="orders__list">
        <? foreach($arResult as $key => $item): ?>
            <div class="orders__item order">
            <div class="order__top">
                <div class="order__num">Номер заказа:&nbsp;<b><?=$item["ORDER"]["ID"]?></b></div>
                <div class="order__sum">Сумма:&nbsp;<b><?=$item["ORDER"]["TOTAL_PRICE"]?></b></div>
                <div class="order__status">Статус:&nbsp;
                    <!--еще доп класс order__status_or - в ожидании-->
                    <span class="order__status_b"><?=$item["ORDER"]["USER_INFO"]["STATUS"]?>&nbsp;</span>
                    <span class="order__status_l"><?=$item["ORDER"]["USER_INFO"]["DATE"]?></span>
                </div>
            </div>
            <div class="order__bottom">
                <div class="order__date"><b>Дата оформления:&nbsp;</b><?=$item["ORDER"]["USER_INFO"]["DATE"]?></div>
                <? if(!empty($item["ORDER"]["PRODUCTS"])): ?>
                <div class="order__items">
                    <? foreach ($item["ORDER"]["PRODUCTS"] as $product): ?>
                        <span class="order__item oitem">
                            <span class="oitem__title">
                                <span class="oitem__title-txt"><?=$product["NAME"]?> (<?=$product["QUANTITY"]?> шт.)</span>
                            </span>
                            <span class="oitem__prices">
                                <span class="oitem__price">
                                    <?=CMarketCatalog::getPrice($product["PRICE"]);?>
                                </span>
                            </span>
                        </span>
                    <? endforeach ?>
                </div>
                <? endif ?>

                <? if(!empty($item["ORDER"]["DELIVERY"])): ?>
                    <div class="order__delivery">
                        <div class="order__delivery-title">Доставка:</div>
                        <div class="order__delivery-item oitem">
                            <span class="oitem__title">
                                <span class="oitem__title-txt"><?=$item["ORDER"]["DELIVERY"]["NAME"]?></span>
                            </span>
                            <span class="oitem__prices">
                                <span class="oitem__price"><?=$item["ORDER"]["DELIVERY"]["PRICE"]?></span>
                            </span>
                        </div>
                    </div>
                <? endif ?>

                <div class="order__total">
                    <div class="oitem order__total-item">
                        <span class="oitem__title oitem__title_b">
                            <span class="oitem__title-txt">Итого:</span>
                        </span>
                        <span class="oitem__prices">
                            <span class="oitem__price oitem__price_b"><?=$item["ORDER"]["TOTAL_PRICE"]?></span>
                        </span>
                    </div>
                </div>

                <div class="order__info oinfo">
                    <? if($item["ORDER"]["USER_INFO"]["ADDRESS"]): ?>
                        <div class="oinfo__item">
                            <div class="oinfo__img">
                                <?=CMarketView::showIcon("car", "oinfo__img-svg oinfo__img-svg_car")?>
                            </div>
                            <div class="oinfo__txt"><?=$item["ORDER"]["USER_INFO"]["ADDRESS"]?></div>
                        </div>
                    <? endif ?>
                    <? if($item["ORDER"]["PAY"]): ?>
                        <div class="oinfo__item">
                            <div class="oinfo__img">
                                <?=CMarketView::showIcon("wallet", "oinfo__img-svg oinfo__img-svg_wallet")?>
                            </div>
                            <div class="oinfo__txt"><?=$item["ORDER"]["PAY"]["NAME"]?></div>
                        </div>
                    <? endif ?>
                </div>

                <? if($item["ORDER"]["USER_INFO"]): ?>
                    <div class="order__client">
                        <div class="order__client-title">Информация о клиенте:</div>
                        <div class="oclient">

                            <? if($item["ORDER"]["USER_INFO"]['NAME']): ?>
                                <div class="oclient__item">
                                    <div class="oclient__img">
                                        <?=CMarketView::showIcon("cabinet", "oclient__svg oclient__svg_cabinet")?>
                                    </div>
                                    <div class="oclient__txt"><?=$item["ORDER"]["USER_INFO"]['NAME']?></div>
                                </div>
                            <? endif ?>

                            <? if($item["ORDER"]["USER_INFO"]['PHONE']): ?>
                                <div class="oclient__item">
                                    <div class="oclient__img">
                                        <?=CMarketView::showIcon("tel2", "oclient__svg oclient__svg_tel2")?>
                                    </div>
                                    <div class="oclient__txt"><?=$item["ORDER"]["USER_INFO"]['PHONE']?></div>
                                </div>
                            <? endif ?>

                            <? if($item["ORDER"]["USER_INFO"]['EMAIL']): ?>
                                <div class="oclient__item">
                                    <div class="oclient__img">
                                        <?=CMarketView::showIcon("pin", "oclient__svg oclient__svg_pin")?>
                                    </div>
                                    <div class="oclient__txt"><?=$item["ORDER"]["USER_INFO"]['EMAIL']?></div>
                                </div>
                            <? endif ?>

                            <? if($item["ORDER"]["USER_INFO"]['ADDRESS']): ?>
                                <div class="oclient__item">
                                    <div class="oclient__img">
                                        <?=CMarketView::showIcon("pin", "oclient__svg oclient__svg_pin2")?>
                                    </div>
                                    <div class="oclient__txt"><?=$item["ORDER"]["USER_INFO"]['ADDRESS']?>
                                        <?=$item["ORDER"]["USER_INFO"]['COMMENT']?></div>
                                </div>
                            <? endif ?>
                        </div>
                    </div>
                <? endif ?>
            </div>
        </div>
        <? endforeach ?>
    </div>
<? endif ?>
    <a class="orders__back" href="/personal/">
        <?=CMarketView::showIcon("arrow-s-left", "orders__back-svg")?>
    <div class="orders__back-txt">Вернуться в кабинет</div>
</a>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>