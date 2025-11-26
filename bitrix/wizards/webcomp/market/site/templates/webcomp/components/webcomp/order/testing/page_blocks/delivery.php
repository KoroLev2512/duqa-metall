<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

global $arrFilter, $totalDelivery;
$arrFilter = ["UF_ACTIVE" => "1"];

$cartDelivery = $APPLICATION->IncludeComponent(
    "webcomp:highload.getList",
    ".default",
    array(
        "CACHE_TIME" => "0",
        "CACHE_TYPE" => "A",
        "ELEMENTS_COUNT" => "20",
        "FIELD_CODE" => array(
            0 => "ID",
            1 => "UF_ACTIVE",
            2 => "UF_SORT",
            3 => "UF_NAME",
            4 => "UF_PRICE",
            5 => "UF_PERIOD_TEXT",
            6 => "UF_DESCRIPTION",
            7 => "UF_ICON",
            8 => "UF_PRICE_FOR_USER",
            9 => "UF_CHECKED",
            10 => "UF_HIDE_FIELD",
            11 => "UF_PAY_OPTIONS",
        ),
        "HLBLOCK_ID" => $GLOBALS['WEBCOMP']['HLBLOCKS']['WebCompMarketDelivery'],
        "USE_FILTER" => "Y",
        "USE_SORT" => "Y",
        "COMPONENT_TEMPLATE" => ".default",
        "SORT_FILED" => "UF_SORT",
        "SORT_ORDER" => "ASC",
        "FILTER_NAME" => "arrFilter",
        "DONT_INCLUDE_TEMPLATE" => "Y"
    ),
    false
)["ITEMS"];

?>

<? if (!empty($cartDelivery)): ?>
    <div class="basket__row">
        <div class="brow">
            <div class="brow__title"><?= Loc::getMessage("WEBCOMP_ORDER_DELIVERY_TITLE") ?></div>
            <div class="brow__content brow__content_p">
                <div class="row basket__delivery bdelivery">
                    <div class="brow__left">
                        <div class="bdelivery__options delopts">
                            <? foreach($cartDelivery as $key => $delivery): ?>

                                <?
                                    if($delivery["UF_CHECKED"]["VALUE"]) {
                                        $totalDelivery = $delivery["UF_PRICE"]["VALUE"];
                                    }
                                ?>

                                <div class="delopt">
                                    <label class="delopt__label">
                                        <input class="delopt__input"
                                               type="radio"
                                               name="DELIVERY"
                                               value="<?=$delivery["ID"]?>"
                                               data-pay = "<?=implode(",", $delivery["UF_PAY_OPTIONS"]["VALUE"]) ?>"
                                               data-name="<?=$delivery["UF_NAME"]["VALUE"]?>"
                                               data-description="<?=$delivery["UF_DESCRIPTION"]["VALUE"]?>"
                                               data-period="<?=$delivery["UF_PERIOD_TEXT"]["VALUE"]?>"
                                               data-price="<?=$delivery["UF_PRICE"]["VALUE"]?>"
                                               data-price_for_user="<?=($delivery["UF_PRICE_FOR_USER"]["VALUE"]) ?: $delivery["UF_PRICE"]["VALUE"]?>"
                                               data-hide_field="<?=($delivery["UF_HIDE_FIELD"]["VALUE"]) ?: ""?>"
                                               <?=($delivery["UF_CHECKED"]["VALUE"]) ? "checked" : ""?>
                                        >
                                        <span class="delopt__block">
                                            <span class="delopt__top">
                                                <span class="delopt__fake">
                                                      <svg class="delopt__fake-svg">
                                                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check2"></use>
                                                      </svg>
                                                </span>
                                                <img class="delopt__img" src="<?=$delivery["UF_ICON"]["VALUE"][0]["SRC"]?>" alt="<?=$delivery["UF_NAME"]["VALUE"]?>"/>
                                            </span>
                                            <span class="delopt__bottom">
                                                <span class="delopt__price"><?=($delivery["UF_PRICE_FOR_USER"]["VALUE"]) ?: $delivery["UF_PRICE"]["VALUE"]?></span>
                                            </span>
                                        </span>
                                    </label>
                                    <div class="delopt__title"><?=$delivery["UF_NAME"]["VALUE"]?></div>
                                </div>

                            <? endforeach ?>

                        </div>
                    </div>



                    <div class="brow__right" id="tpl_deliveryContainer"></div>

                    <? $APPLICATION->ShowViewContent('order_address_field'); ?>

                </div>
            </div>
        </div>
    </div>

    <template id="tpl_delivery">
        <div class="bdelivery__info">
            <div class="bdelivery__info-title">{{NAME}}</div>
            <div class="bdelivery__info-sub">{{DESCRIPTION}}</div>
            <div class="bdelivery__info-list">
                <div class="bdelivery__info-item"><?= Loc::getMessage("WEBCOMP_ORDER_DELIVERY_PRICES") ?>
                    &nbsp;<b>{{PRICE}}</b></div>
                <div class="bdelivery__info-item"><?= Loc::getMessage("WEBCOMP_ORDER_DELIVERY_PERIOD") ?>
                    &nbsp;<b>{{PERIOD}}</b></div>
            </div>
            <a class="bdelivery__info-link" target="_blank"
               href="#WIZARD_SITE_DIR#company/delivery/"><?= Loc::getMessage("WEBCOMP_ORDER_DELIVERY_MORE") ?></a>
        </div>
    </template>

<? endif ?>
