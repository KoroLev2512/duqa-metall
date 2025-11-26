<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="new__info">
    <div class="new__info-item"><?= $arResult["ACTIVE_FROM"] ?></div>
</div>
<div class="new__top">
    <? if ( ! empty($arResult["DETAIL_PICTURE"]["SRC"])): ?>
        <div class="new__top-left">
            <img class="new__img"
                 src="<?= $arResult["DETAIL_PICTURE"]['SRC'] ?>"
                 alt="<?= $arResult["NAME"] ?>">
        </div>
    <? endif; ?>
    <div class="new__top-right">
        <div class="new__content">
            <div class="new__content-top">
                <div class="content">
                    <?= $arResult["DETAIL_TEXT"] ?>
                </div>
            </div>
            <div class="new__content-bottom">
                <? if ( ! empty($arResult['PROPERTIES']['SOURCE']['VALUE'])): ?>
                    <div class="new__from">
                        Источник: <?= $arResult['PROPERTIES']['SOURCE']['VALUE'] ?></div>
                <? endif; ?>
                <div class="new__share">
                    <div class="share">
                        <div class="share__txt">ПОДЕЛИТЬСЯ:</div>
                        <script src="https://yastatic.net/share2/share.js"></script>
                        <div class="ya-share2" data-curtain
                             data-services="whatsapp,telegram,vkontakte,facebook,odnoklassniki"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<? if ( ! empty($arResult['PROPERTIES']['PRODUCTS']['VALUE'])): ?>
    <?php
    global $recommendProductFilter;
    $recommendProductFilter
        = ['ID' => $arResult['PROPERTIES']['PRODUCTS']['VALUE']]
    ?>
    <?
    $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "recommend_product",
        [
            "CACHE_FILTER"          => "N",
            "CACHE_TIME"            => "36000000",
            "CACHE_TYPE"            => "A",
            "COMPONENT_TEMPLATE"    => "recommend_product",
            "ELEMENTS_COUNT"        => "20",
            "FIELD_CODE"            => [
                0 => "ID",
                1 => "ACTIVE_FROM",
                2 => "NAME",
                3 => "PREVIEW_PICTURE",
                4 => "PREVIEW_TEXT",
                5 => "CODE",
            ],
            "FILTER_NAME"           => "recommendProductFilter",
            "IBLOCK_ID"             => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
            "IBLOCK_TYPE"           => "catalog",
            "LINK_LINK"             => "/",
            "LINK_TITLE"            => "Все услуги",
            "PAGINATION"            => "Y",
            "PROPERTY_CODE"         => [
                0 => "OLD_PRICE",
                1 => "PRICE",
                2 => "STICKERS",
                3 => "AVAILABLE",
                4 => "",
                5 => "",
            ],
            "SHOW_ONLY_ACTIVE"      => "Y",
            "SORT_BY1"              => "ACTIVE_FROM",
            "SORT_BY2"              => "SORT",
            "SORT_ORDER1"           => "DESC",
            "SORT_ORDER2"           => "ASC",
            "TITLE"                 => "Товары по теме",
            "DONT_INCLUDE_TEMPLATE" => "N",
            "USE_FILTER"            => "Y",
        ],
        false
    ); ?>
<? endif; ?>

<? if ( ! empty($arResult['PROPERTIES']['SERVICES']['VALUE'])): ?>
    <?php
    global $recommendServicesFilter;
    $recommendServicesFilter
        = ['ID' => $arResult['PROPERTIES']['SERVICES']['VALUE']]
    ?>
    <?
    $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "services_in_news",
        [
            "CACHE_FILTER"          => "N",
            "CACHE_TIME"            => "36000000",
            "CACHE_TYPE"            => "A",
            "COMPONENT_TEMPLATE"    => "services_in_news",
            "ELEMENTS_COUNT"        => "20",
            "FIELD_CODE"            => [
                0 => "ID",
                1 => "ACTIVE_FROM",
                2 => "NAME",
                3 => "PREVIEW_PICTURE",
                4 => "PREVIEW_TEXT",
                5 => "CODE",
            ],
            "FILTER_NAME"           => "recommendServicesFilter",
            "IBLOCK_ID"             => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_services'],
            "IBLOCK_TYPE"           => "content",
            "LINK_LINK"             => "/",
            "LINK_TITLE"            => "Все услуги",
            "PAGINATION"            => "Y",
            "PROPERTY_CODE"         => [
                0 => "",
                1 => "",
            ],
            "SHOW_ONLY_ACTIVE"      => "Y",
            "SORT_BY1"              => "ACTIVE_FROM",
            "SORT_BY2"              => "SORT",
            "SORT_ORDER1"           => "DESC",
            "SORT_ORDER2"           => "ASC",
            "TITLE"                 => "Услуги по теме",
            "DONT_INCLUDE_TEMPLATE" => "N",
            "USE_FILTER"            => "Y",
        ],
        false
    ); ?>
<? endif; ?>





