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

$bShowDocs = false;

#documents
if ( ! empty($arResult["PROPERTIES"]["DOCUMENTS"]["VALUE"])) {
    $bShowDocs = true;
    $documents = $arResult["PROPERTIES"]["DOCUMENTS"]["VALUE"];
}


?>

<div class="service__wrap">

    <? if ( ! empty($arResult["PROPERTIES"]["SUB_TITLE"]["VALUE"])): ?>
        <blockquote class="service__quote">
            <?= $arResult["PROPERTIES"]["SUB_TITLE"]["VALUE"] ?>
        </blockquote>
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
    </div>

    <div class="service__banner">
        <div class="sbanner">
            <div class="sbanner__left">
                <div class="sbanner__img">
                    <img class="sbanner__img-img"
                         src="<?= SITE_TEMPLATE_PATH ?>/images/content/service/icon.svg"
                         alt="Заказать услугу <?= $arResult["NAME"] ?>">
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

                <button class="btn sbanner__btn" type="button"
                        data-event="showForm"
                        data-request="/ajax/form/"
                        data-form_name="PROJECT"
                        data-form_id=<?=$GLOBALS['WEBCOMP']['IBLOCKS']['forms']['webcomp_market_forms_order_project']?>
                        data-email_event_id="WEBCOMP_ORDER_PROJECT"
                        data-elements_id="<?= $arResult["ID"] ?>">
                    <?= GetMessage("CALLORDER_BUTTON_TEXT") ?>
                </button>
            </div>
        </div>
    </div>

    <? if ( ! empty($arResult["PROPERTIES"]["GALLERY"]["VALUE"])) : ?>
        <div class="service__gallery service__gallery_bdb">
            <div class="service__gallery-title"><?= GetMessage("GALLERY") ?></div>
            <div class="product__gallery pgallery">
                <button class="pgallery__prev pgallery__arr swiper-button-disabled"
                        type="button" tabindex="0" role="button"
                        aria-label="Previous slide" aria-disabled="true">
                    <?= CMarketView::showIcon("arr-l", "pgallery__arr-svg") ?>
                </button>

                <div class="swiper-container pgallery__container">
                    <div class="swiper-wrapper">
                        <? foreach (
                            $arResult["PROPERTIES"]["GALLERY"]["VALUE"] as $key
                        => $image
                        ): ?>
                            <div class="swiper-slide pgallery__item">
                                <div class="pgallery__img">
                                    <img class="pgallery__img-img"
                                         src="<?= CFile::getPath($image) ?>"
                                         alt="picture № <?= $key ?>">
                                </div>
                            </div>
                        <? endforeach ?>
                    </div>
                    <span class="swiper-notification" aria-live="assertive"
                          aria-atomic="true"></span></div>
                <button class="pgallery__next pgallery__arr" type="button"
                        tabindex="0" role="button"
                        aria-label="Next slide" aria-disabled="false">
                    <?= CMarketView::showIcon("arr-r", "pgallery__arr-svg") ?>
                </button>
            </div>

            <div class="product__thumbs2">
                <div class="swiper-container pthumbs2 ">
                    <div class="swiper-wrapper">
                        <? foreach (
                            $arResult["PROPERTIES"]["GALLERY"]["VALUE"] as $key
                        => $image
                        ): ?>
                            <div class="swiper-slide pthumb2">
                                <div class="pthumb2__img">
                                    <img class="pthumb__img-img"
                                         src="<?= CFile::getPath($image) ?>"
                                         alt="pucture № <?= $key ?>">
                                </div>
                            </div>
                        <? endforeach ?>
                    </div>
                    <span class="swiper-notification" aria-live="assertive"
                          aria-atomic="true"></span></div>
            </div>
        </div>
    <? endif ?>


    <? if ($bShowDocs) : ?>
        <div class="service__docs">
            <div class="service__docs-title"><?= GetMessage("DOCUMENTS") ?></div>
            <div class="pdocs">

                <? foreach ($documents as $key => $docs): ?>

                    <?
                    $fileInfo = CFile::GetFileArray($docs);
                    $type = end(explode(".", $fileInfo["ORIGINAL_NAME"]));

                    if (in_array($type, ["jpg", "png", "jpeg", "webp"])) {
                        $type = "Img";
                    }

                    $type = (file_exists($_SERVER["DOCUMENT_ROOT"]
                        .SITE_TEMPLATE_PATH."/images/icons/".$type.".svg")
                        ? $type : "file");
                    ?>

                    <div class="pdoc">
                        <div class="pdoc__left">
                            <img class="pdoc__img"
                                 src="<?= SITE_TEMPLATE_PATH ?>/images/icons/<?= $type ?>.svg"
                                 alt="<?=($fileInfo["DESCRIPTION"]) ?:$fileInfo["ORIGINAL_NAME"]?>"/>
                        </div>
                        <div class="pdoc__right">
                            <div class="pdoc__title"><?=($fileInfo["DESCRIPTION"]) ?:$fileInfo["ORIGINAL_NAME"]?></div>
                            <a class="pdoc__link" href="<?= $fileInfo["SRC"] ?>"
                               download="<?= $fileInfo["ORIGINAL_NAME"] ?>"><?=getMessage("WEBCOMP_DOWNLOAD_TITLE")?></a>
                        </div>
                    </div>

                <? endforeach ?>
            </div>
        </div>
    <? endif ?>

    <? if ( ! empty($arResult['PROPERTIES']['PRODUCTS']['VALUE'])): ?>
        <?
        global $recommendProductFilter;
        $recommendProductFilter
            = ['ID' => $arResult['PROPERTIES']['PRODUCTS']['VALUE']];

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

</div>
