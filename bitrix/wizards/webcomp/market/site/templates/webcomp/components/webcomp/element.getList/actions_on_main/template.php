<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);
?>

<?
$arSettings = [];
$arSettings["AUTO_PLAY_SPEED"] = (int)$arParams["AUTO_PLAY_SPEED"];
$arSettings["AUTO_PLAY_DELAY_SPEED"] = (int)$arParams["AUTO_PLAY_DELAY_SPEED"];
$arSettings["AUTO_PLAY"] = ($arParams["AUTO_PLAY"] === "Y") ? "true" : "false";
$arSettings["PAGINATION"] = ($arParams["PAGINATION"] === "Y") ? "true"
    : "false";
?>

<div class="iactions">
    <div class="container">
        <div class="iactions__top title">
            <h2 class="iactions__title title__title">
                <?= $arParams['TITLE']; ?>
                <? if($arParams["LINK_LINK"]): ?>
                    <a class="view-all-link" href="<?= $arParams["LINK_LINK"] ?>" title="<?= $arParams['LINK_TITLE']?>">
                        <span class="view-all-link__icon">›</span>
                    </a>
                <? endif ?>
            </h2>
        </div>
        <div class="iactions__bottom">
            <div class="iactions__slider"
                 data-speed="<?= $arSettings["AUTO_PLAY_SPEED"] ?>"
                 data-autoplay="<?= $arSettings["AUTO_PLAY"] ?>"
                 data-autoplayDelay="<?= $arSettings["AUTO_PLAY_DELAY_SPEED"] ?>"
                 data-pagination="<?= $arSettings["PAGINATION"] ?>">
                <button class="arrow iactions__prev" type="button">
                    <svg class="arrow__svg">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#prev"></use>
                    </svg>
                </button>
                <div class="swiper-container iactions__container">
                    <div class="swiper-wrapper">
                        <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
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
                            ?>
                            <div class="swiper-slide iactions__item"
                                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                                <a class="iaction"
                                   href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                                <span class="iaction__img">
                                    <img class="iaction__img-img"
                                         src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']; ?>"
                                         alt="<?= $arItem['NAME']; ?>"/>
                                </span>
                                    <span class="iaction__bottom">
                                    <span class="iaction__date"><?= $arItem['ACTIVE_FROM']->format('d.m.Y'); ?></span>
                                    <span class="iaction__title"><?= $arItem['NAME']; ?></span>
                                </span>
                                </a>
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
                <button class="arrow iactions__next" type="button">
                    <svg class="arrow__svg">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#next"></use>
                    </svg>
                </button>
            </div>
            <div class="iactions__pag">
                <div class="pag"></div>
            </div>
        </div>
    </div>
</div>



