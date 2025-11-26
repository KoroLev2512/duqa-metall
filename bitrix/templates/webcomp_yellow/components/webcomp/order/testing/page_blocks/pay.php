<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

global $arrFilter;
$arrFilter = ["UF_ACTIVE" => "1"];

$cartPay = $APPLICATION->IncludeComponent(
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
            4 => "UF_DESCRIPTION",
            5 => "UF_ICON",
            6 => "UF_CHECKED",
        ),
        "HLBLOCK_ID" => $GLOBALS['WEBCOMP']['HLBLOCKS']['WebCompMarketPayments'],
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

<? if (!empty($cartPay)): ?>

    <div class="basket__row">
        <div class="brow">
            <div class="brow__title"><?= Loc::getMessage("WEBCOMP_ORDER_PAY_TITLE") ?></div>
            <div class="brow__content brow__content_p">
                <div class="row">
                    <div class="brow__left">
                        <div class="payopts">
                            <? global $jsPayItems; ?>
                            <? foreach ($cartPay as $key => $pay): ?>
                                <? /*
                                <div class="payopt">
                                    <label class="payopt__label">
                                        <input class="payopt__input"
                                               type="radio"
                                               name="PAY"
                                               value="<?= $pay["ID"] ?>"
                                               data-name="<?= $pay["UF_NAME"]["VALUE"] ?>"
                                               data-description="<?= $pay["UF_DESCRIPTION"]["VALUE"] ?>"
                                            <?= ($pay["UF_CHECKED"]["VALUE"]) ? "checked" : "" ?>
                                        >
                                        <span class="payopt__block">
                                        <span class="payopt__top">
                                            <span class="payopt__fake">
                                                <svg class="payopt__fake-svg">
                                                    <use xlink:href="/images/icons/sprite.svg#check2"></use>
                                                </svg>
                                            </span>
                                            <img class="payopt__img" src="<?= $pay["UF_ICON"]["VALUE"][0]["SRC"] ?>"
                                                 alt="<?= $pay["UF_NAME"]["VALUE"] ?>"/>
                                        </span>
                                    </span>
                                    </label>
                                    <div class="delopt__title"><?= $pay["UF_NAME"]["VALUE"] ?></div>
                                </div>
                                */ ?>
                                <? $jsPayItems[] = [
                                    "value" => $pay["ID"],
                                    "name" => $pay["UF_NAME"]["VALUE"],
                                    "description" => $pay["UF_DESCRIPTION"]["VALUE"],
                                    "checked" => ($pay["UF_CHECKED"]["VALUE"]) ? "checked" : "",
                                    "img" => $pay["UF_ICON"]["VALUE"][0]["SRC"]
                                ]; ?>

                            <? endforeach ?>

                        </div>
                    </div>

                    <div class="brow__right" id="tpl_payContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <template id="tpl_paopt">
        <div class="payopt">
            <label class="payopt__label">
                <input class="payopt__input"
                       type="radio"
                       name="PAY"
                       value="{{value}}"
                       data-name="{{name}}"
                       data-description="{{description}}"
                       {{checked}}>
                <span class="payopt__block">
                    <span class="payopt__top">
                        <span class="payopt__fake">
                            <svg class="payopt__fake-svg">
                                <use xlink:href="/images/icons/sprite.svg#check2"></use>
                            </svg>
                        </span>
                        <img class="payopt__img" src="{{img}}"
                             alt="{{name}}"/>
                    </span>
                </span>
            </label>
            <div class="delopt__title">{{name}}</div>
        </div>
    </template>

    <template id="tpl_pay">
        <div class="bdelivery__info">
            <div class="bdelivery__info-title">{{NAME}}</div>
            <div class="bdelivery__info-sub">{{DESCRIPTION}}</div>
            <a class="bdelivery__info-link" target="_blank"
               href="/company/pay/"><?= Loc::getMessage("WEBCOMP_ORDER_PAY_MORE") ?></a>
        </div>
    </template>

<? endif ?>
