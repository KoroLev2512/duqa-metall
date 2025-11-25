<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

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

$arSettings = [];
$arSettings["AUTO_PLAY_SPEED"] = (int)$arParams["AUTO_PLAY_SPEED"];
$arSettings["AUTO_PLAY_DELAY_SPEED"] = (int)$arParams["AUTO_PLAY_DELAY_SPEED"];
$arSettings["AUTO_PLAY"] = ($arParams["AUTO_PLAY"] === "Y") ? "true" : "false";
$arSettings["PAGINATION"] = ($arParams["PAGINATION"] === "Y") ? "true"
    : "false";
$arSettings["SHOW_ARROW"] = ($arParams["SHOW_ARROW"] === "Y")
    ? "ibanner__slider_arr" : "";
?>

    <div class="ibanner">
        <div class="ibanner__slider <?= $arSettings["SHOW_ARROW"] ?>"
             data-speed="<?= $arSettings["AUTO_PLAY_SPEED"] ?>"
             data-autoplay="<?= $arSettings["AUTO_PLAY"] ?>"
             data-autoplayDelay="<?= $arSettings["AUTO_PLAY_DELAY_SPEED"] ?>"
             data-pagination="<?= $arSettings["PAGINATION"] ?>">
            <div class="swiper-container ibanner__container">
                <div class="swiper-wrapper">

                    <? foreach ($arResult["ITEMS"] as $key => $arItem): ?>

                        <?
                        $this->AddEditAction($arItem['ID'],
                            $arItem['EDIT_LINK'],
                            CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                                "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'],
                            $arItem['DELETE_LINK'],
                            CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                                "ELEMENT_DELETE"),
                            ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);

                        $UserField = CIBlockPropertyEnum::GetList([],
                            ["ID" => $arItem["PROPERTIES"]["UF_THEME"]["VALUE"]]);
                        if ($UserFieldAr = $UserField->GetNext()) {
                            $theme = $UserFieldAr["EXTERNAL_ID"];
                        }

                        ?>

                        <div class="swiper-slide ibanner__slide"
                             data-theme=<?= $theme ?>
                             id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                            <div class="ibanner__slide-imgs">
                                <picture>
                                    <source srcset="<?= $arItem["PROPERTIES"]["UF_PICTURE_DESC"]["VALUE"]['SRC'] ?>"
                                            type="image/jpeg"
                                            media="(min-width:991.9px)">
                                    <source srcset="<?= $arItem["PROPERTIES"]["UF_PICTURE_TAB"]["VALUE"]['SRC'] ?>"
                                            type="image/jpeg"
                                            media="(min-width:768.9px)">
                                    <source srcset="<?= $arItem["PROPERTIES"]["UF_PICTURE_MOB"]["VALUE"]['SRC'] ?>"
                                            type="image/jpeg"
                                            media="(min-width:320px)">
                                    <img class="ibanner__slide-img"
                                         src="<?= $arItem["PROPERTIES"]["UF_PICTURE_DESC"]["VALUE"]['SRC'] ?>"
                                         alt="<?= $arItem["NAME"] ?>">
                                </picture>
                            </div>
                            <div class="container ibanner__slide-container">
                                <div class="row ibanner__slide-row">
                                    <div class="ibanner__slide-content">
                                        <h2 class="ibanner__slide-title"><?= $arItem["NAME"] ?></h2>
                                        <div class="ibanner__slide-txt"><?= $arItem["PREVIEW_TEXT"] ?></div>
                                        <div class="ibanner__slide-links">

                                            <? if ( ! empty($arItem["PROPERTIES"]["UF_LINK_MORE"]["VALUE"])): ?>
                                                <a class="ibanner__slide-link btn"
                                                   href="<?= $arItem["PROPERTIES"]["UF_LINK_MORE"]["VALUE"] ?>">Подробнее</a>
                                            <? endif ?>

                                            <? if ( ! empty($arItem["PROPERTIES"]["UF_LINK_CATALOG"]["VALUE"])): ?>
                                                <a class="ibanner__slide-link btn2"
                                                   href="<?= $arItem["PROPERTIES"]["UF_LINK_CATALOG"]["VALUE"] ?>">В
                                                    каталог</a>
                                            <? endif ?>

                                            <? if ( ! empty($arItem["PROPERTIES"]["UF_ORDER_BTN"]["VALUE"])): ?>
                                                <a class="ibanner__slide-link btn"
                                                   href=""
                                                   data-trigger="click"
                                                   data-target="CALLORDER">Заказать
                                                    звонок
                                                </a>
                                            <? endif ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <? endforeach ?>

                </div>
            </div>
            <div class="ibanner__nav">
                <div class="container ibanner__nav-container">
                    <div class="ibanner__arrs">
                        <div class="ibanner__prev">
                            <button class="arr ibanner__prev-btn" type="button">
                                <?= CMarketView::showIcon("arr-l",
                                    "arr__svg") ?>
                            </button>
                        </div>
                        <div class="ibanner__next">
                            <button class="arr ibanner__next-btn" type="button">
                                <?= CMarketView::showIcon("arr-r",
                                    "arr__svg") ?>
                            </button>
                        </div>
                    </div>
                    <div class="swiper-pagination ibanner__pag"></div>
                </div>
            </div>
        </div>
    </div>

<?
unset($arSettings);
?>