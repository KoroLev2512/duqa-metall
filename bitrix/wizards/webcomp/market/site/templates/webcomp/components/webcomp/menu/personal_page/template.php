<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?php

use Bitrix\Main\Context;

$urlWithoutQueryString = substr(Context::getCurrent()->getRequest()
    ->getRequestUri(), 0,
    strpos(Context::getCurrent()->getRequest()->getRequestUri(), "?"));

$exitLink = "/?logout=yes&".bitrix_sessid_get();

$arResult['ITEMS'][] = [
    "NAME" => getMessage("PERSONAL_EXIT_BTN"),
    "LINK" => $exitLink,
];

$iconsArr = [
    "/personal/orders/"          => [
        "shop_only" => "Y",
        "class" => "cabinet__icon_lk-orders",
        "url"   => "/images/icons/sprite.svg#lk-orders",
    ],
    "/personal/personal-data/"   => [
        "class" => "cabinet__icon_lk-data",
        "url"   => "/images/icons/sprite.svg#lk-data",
    ],
    "/personal/change-password/" => [
        "class" => "cabinet__icon_lk-basket",
        "url"   => "/images/icons/sprite.svg#lk-password",
    ],
    "/cart/"                     => [
        "shop_only" => "Y",
        "class" => "cabinet__icon_lk-basket",
        "url"   => "/images/icons/sprite.svg#lk-basket",
    ],
    "/cart/favorite/"            => [
        "shop_only" => "Y",
        "class" => "cabinet__icon_lk-favorite",
        "url"   => "/images/icons/sprite.svg#lk-favorite",
    ],
    "/cart/compare/"             => [
        "shop_only" => "Y",
        "class" => "cabinet__icon_lk-compare",
        "url"   => "/images/icons/sprite.svg#compare",
    ],
];

$iconsArr[$exitLink] = [
    "class" => "cabinet__icon_lk-exit",
    "url"   => "/images/icons/sprite.svg#lk-exit",
];

foreach ($arResult['ITEMS'] as $key => &$ITEM) {
    if ($ITEM['LINK'] == $urlWithoutQueryString) {
        unset($arResult['ITEMS'][$key]);
        continue;
    }

    if (isset($iconsArr[$ITEM['LINK']])) {
        $ITEM["ICON"] = $iconsArr[$ITEM['LINK']];
    }
}

if ( ! empty($arResult['ITEMS'])):?>
    <div class="cabinet__list">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <?
                if($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_CHECKBOX_E-SHOP"] !== "Y" &&
                    $iconsArr[$item["LINK"]]["shop_only"] === "Y") {
                    continue;
                }
            ?>

            <a class="cabinet__item" href="<?= $item['LINK'] ?>">
                <span class="cabinet__img">
                      <svg class="cabinet__icon <?= $item['ICON']['class'] ?>">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?><?= $item['ICON']['url'] ?>"></use>
                      </svg>
                </span>
                <span class="cabinet__item-titlel"><?= $item['NAME'] ?></span>
            </a>
        <? endforeach; ?>
    </div>
<?php
endif; ?>
