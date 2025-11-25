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
<div class="ibrands">
    <div class="container">
        <div class="ibrands__top title">
            <h2 class="ibrands__title title__title">
                <?= $arParams['TITLE']; ?>
                <? if($arParams["LINK_LINK"]): ?>
                    <a class="view-all-link" href="<?= $arParams["LINK_LINK"] ?>" title="<?= $arParams['LINK_TITLE']?>">
                        <span class="view-all-link__icon">›</span>
                    </a>
                <? endif ?>
            </h2>
        </div>
        <div class="ibrands__bottom">
            <div class="ibrands__slider"
                 data-speed="<?= $arSettings["AUTO_PLAY_SPEED"] ?>"
                 data-autoplay="<?= $arSettings["AUTO_PLAY"] ?>"
                 data-autoplayDelay="<?= $arSettings["AUTO_PLAY_DELAY_SPEED"] ?>"
                 data-pagination="<?= $arSettings["PAGINATION"] ?>">
                <button class="arrow ibrands__prev" type="button">
                    <svg class="arrow__svg">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#prev"></use>
                    </svg>
                </button>
                <div class="swiper-container ibrands__container">
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
                            <a class="swiper-slide ibrand"
                               href="<?= $arItem['DETAIL_PAGE_URL']; ?>"
                               id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                                <img class="ibrand__img"
                                     src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']; ?>"
                                     alt="<?= $arItem['NAME']; ?>"/>
                            </a>
                        <? endforeach; ?>
                    </div>
                </div>
                <button class="arrow ibrands__next" type="button">
                    <svg class="arrow__svg">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#next"></use>
                    </svg>
                </button>
            </div>
            <div class="ibrands__pag">
                <div class="pag"></div>
            </div>
        </div>
    </div>
</div>

