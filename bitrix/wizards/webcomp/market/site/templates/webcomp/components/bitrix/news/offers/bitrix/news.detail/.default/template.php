<? if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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

$arResult["PROPERTIES"]["BANNER"]["VALUE"];

if ( ! empty($arResult["PROPERTIES"]["BANNER"]["VALUE"])) {
    global $arrSection;
    $arrSection = ["ID" => [$arResult["PROPERTIES"]["BANNER"]["VALUE"]]];

    $banner = $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "default",
        [
            "CACHE_FILTER"          => "N",
            "CACHE_TIME"            => "36000000",
            "CACHE_TYPE"            => "A",
            "ELEMENTS_COUNT"        => "20",
            "FIELD_CODE"            => [
                0 => "ID",
                1 => "IBLOCK_ID",
                2 => "NAME",
                3 => "PREVIEW_PICTURE",
                4 => "PREVIEW_TEXT",
            ],
            "FILTER_NAME"           => "arrSection",
            "IBLOCK_ID"             => $GLOBALS['WEBCOMP']['IBLOCKS']['marketing']['webcomp_market_marketing_banner'],
            "IBLOCK_TYPE"           => "marketing",
            "PROPERTY_CODE"         => [
                0 => "UF_PICTURE_DESC",
                1 => "UF_PICTURE_TAB",
                2 => "UF_PICTURE_MOB",
                3 => "UF_LINK_MORE",
                4 => "UF_LINK_CATALOG",
                5 => "UF_ORDER_BTN",
            ],
            "SHOW_ONLY_ACTIVE"      => "Y",
            "SORT_BY1"              => "ACTIVE_FROM",
            "SORT_BY2"              => "SORT",
            "SORT_ORDER1"           => "DESC",
            "SORT_ORDER2"           => "ASC",
            "COMPONENT_TEMPLATE"    => ".default",
            "PAGINATION"            => "Y",
            "SHOW_ARROW"            => "Y",
            "AUTO_PLAY"             => "Y",
            "AUTO_PLAY_SPEED"       => "500",
            "AUTO_PLAY_DELAY_SPEED" => "7000",
            "USE_FILTER"            => "Y",
            "DONT_INCLUDE_TEMPLATE" => "Y",
        ],
        false
    );
}

?>
<? $offerDate = ''; ?>

<? if ( ! empty($arResult["ACTIVE_FROM"])): ?>
    <?
    $offerDate = '<div class="offers__active">';
    $offerDate .= getMessage("WEBCOMP_OFFERS_FROM")." ".date("d.m.Y",
            strtotime($arResult["ACTIVE_FROM"]))." ".getMessage("WEBCOMP_OFFERS_YEAR");

    if (isset($arResult["ACTIVE_TO"]) && ! empty($arResult["ACTIVE_TO"])) {
        $offerDate .= " ".getMessage("WEBCOMP_OFFERS_TO")." ".date("d.m.Y", strtotime($arResult["ACTIVE_TO"]))
            ." ".getMessage("WEBCOMP_OFFERS_YEAR");
    }

    $offerDate .= '</div>';
    ?>

<? endif ?>


    <div class="service__top">

        <? if ( ! empty($arResult["DETAIL_PICTURE"]["SRC"])): ?>
            <div class="service__img">
                <img class="service__img-img"
                     src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>"
                     alt="<?= $arResult["NAME"] ?>">
            </div>
        <? endif ?>

        <? if ( ! empty($arResult["DETAIL_TEXT"])): ?>
            <div class="service__content">
                <div class="content">
                    <?= $arResult["DETAIL_TEXT"] ?>
                </div>
            </div>
        <? endif ?>

        <?= $offerDate ?>
    </div>

<? if ( ! empty($arResult["PROPERTIES"]["SUB_TITLE"]["VALUE"])): ?>
    <blockquote class="service__quote">
        <?= $arResult["PROPERTIES"]["SUB_TITLE"]["VALUE"] ?>
    </blockquote>
<? endif ?>

    <div class="service__banner">
        <div class="sbanner">
            <div class="sbanner__left">
                <div class="sbanner__img">
                    <img class="sbanner__img-img"
                         src="<?= SITE_TEMPLATE_PATH ?>/images/content/service/icon.svg"
                         alt="<?= $arResult["NAME"] ?>">
                </div>
                <div class="sbanner__txt"><?= GetMessage("BANNER_TEXT") ?></div>
            </div>
            <div class="sbanner__right">

                <? if ( ! empty($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"])): ?>
                    <div class="sbanner__oldprice"><?= $arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"] ?></div>
                <? endif ?>

                <? if ( ! empty($arResult["PROPERTIES"]["PRICE"]["VALUE"])): ?>
                    <div class="sbanner__price"><?= $arResult["PROPERTIES"]["PRICE"]["VALUE"] ?></div>
                <? endif ?>

                <button data-id="<?= $arResult["ID"] ?>"
                        class="btn sbanner__btn"
                        data-trigger="click"
                        data-target="CALLORDER"
                        type="button">
                    <?= GetMessage("CALLORDER_BUTTON_TEXT") ?></button>
            </div>
        </div>
    </div>

<? if ( ! empty($arResult['PROPERTIES']['RECOMMENDED']['VALUE'])): ?>
    <?
    global $recommendProductFilter;
    $recommendProductFilter
        = ['ID' => $arResult['PROPERTIES']['RECOMMENDED']['VALUE']];

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
            "TITLE"                 => "Товары по акции",
            "DONT_INCLUDE_TEMPLATE" => "N",
            "USE_FILTER"            => "Y",
        ],
        false
    ); ?>
<? endif; ?>


<? if (isset($banner) && $banner = current($banner["ITEMS"])): ?>
    <? $this->SetViewTarget('offer_banner'); ?>
    <div class="action__banner abanner">
        <div class="abanner__img">
            <picture>

                <? if ( ! empty($banner["PROPERTIES"]["UF_PICTURE_DESC"]["VALUE"]["SRC"])): ?>
                    <source srcset="<?= $banner["PROPERTIES"]["UF_PICTURE_DESC"]["VALUE"]["SRC"] ?>"
                            type="<?= $banner["PROPERTIES"]["UF_PICTURE_DESC"]["VALUE"]["CONTENT_TYPE"] ?>"
                            media="(min-width:991.9px)">
                <? endif ?>

                <? if ( ! empty($banner["PROPERTIES"]["UF_PICTURE_TAB"]["VALUE"]["SRC"])): ?>
                    <source srcset="<?= $banner["PROPERTIES"]["UF_PICTURE_TAB"]["VALUE"]["SRC"] ?>"
                            type="<?= $banner["PROPERTIES"]["UF_PICTURE_TAB"]["VALUE"]["CONTENT_TYPE"] ?>"
                            media="(min-width:768.9px)">
                <? endif ?>

                <? if ( ! empty($banner["PROPERTIES"]["UF_PICTURE_MOB"]["VALUE"]["SRC"])): ?>
                    <source srcset="<?= $banner["PROPERTIES"]["UF_PICTURE_MOB"]["VALUE"]["SRC"] ?>"
                            type="<?= $banner["PROPERTIES"]["UF_PICTURE_MOB"]["VALUE"]["CONTENT_TYPE"] ?>"
                            media="(min-width:320px)">
                <? endif ?>

                <? if ( ! empty($banner["PROPERTIES"]["UF_PICTURE_DESC"]["VALUE"]["SRC"])): ?>
                    <img class="abanner__img-img"
                         src="<?= $banner["PROPERTIES"]["UF_PICTURE_DESC"]["VALUE"]["SRC"] ?>"
                         alt="<?= $banner['NAME'] ?>">
                <? endif ?>

            </picture>
        </div>
        <div class="container abanner__container">
            <div class="row abanner__row">
                <div class="abanner__content">
                    <div class="abanner__title"><?= $banner["NAME"] ?></div>
                    <? if ( ! empty($banner["PREVIEW_TEXT"])): ?>
                        <div class="abanner__txt"><?= $banner["PREVIEW_TEXT"] ?></div>
                    <? endif ?>
                    <div class="abanner__btns">

                        <? if ( ! empty($banner["PROPERTIES"]["UF_LINK_MORE"]["VALUE"])): ?>
                            <a class="abanner__link btn2"
                               href="<?= $banner["PROPERTIES"]["UF_LINK_MORE"]["VALUE"] ?>"><?=getMessage("WEBCOMP_OFFERS_DETAIL")?></a>
                        <? endif ?>

                        <? if ( ! empty($banner["PROPERTIES"]["UF_LINK_CATALOG"]["VALUE"])): ?>
                            <a class="abanner__btn btn"
                               href="<?= $banner["PROPERTIES"]["UF_LINK_CATALOG"]["VALUE"] ?>"><?=getMessage("WEBCOMP_OFFERS_IN_CATALOG")?></a>
                        <? endif ?>

                        <? if ( ! empty($banner["PROPERTIES"]["UF_ORDER_BTN"]["VALUE"])): ?>
                            <button class="abanner__btn btn"
                                    data-trigger="click" data-target="CALLORDER"
                                    type="button"><?=getMessage("WEBCOMP_OFFERS_CALLORDER")?>
                            </button>
                        <? endif ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <? $this->EndViewTarget(); ?>
<? endif ?>
