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
<div class="iprojects">
    <div class="container">
        <div class="iprojects__top title">
            <h2 class="iprojects__title title__title">
                <?= $arParams['TITLE']; ?>
                <? if($arParams["LINK_LINK"]): ?>
                    <a class="view-all-link" href="<?= $arParams["LINK_LINK"] ?>" title="<?= $arParams['LINK_TITLE']?>">
                        <span class="view-all-link__icon">›</span>
                    </a>
                <? endif ?>
            </h2>
        </div>
    </div>
    <div class="iprojects__bottom">
        <div class="iprojects__slider"
             data-speed="<?= $arSettings["AUTO_PLAY_SPEED"] ?>"
             data-autoplay="<?= $arSettings["AUTO_PLAY"] ?>"
             data-autoplayDelay="<?= $arSettings["AUTO_PLAY_DELAY_SPEED"] ?>"
             data-pagination="<?= $arSettings["PAGINATION"] ?>">
            <div class="swiper-container iprojects__container">
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
                        <a class="swiper-slide iproject"
                           href="<?= $arItem['DETAIL_PAGE_URL']; ?>"
                           id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                            <img class="iproject__img"
                                 src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']; ?>"
                                 alt="<?= $arItem['NAME']; ?>"/>
                            <span class="iproject__content">
                                <span class="iproject__title"><?= $arItem['NAME']; ?></span>
                                <span class="iproject__txt">
                                    <?= $arItem['PREVIEW_TEXT']; ?>
                                </span>
                                <svg class="iproject__svg">
                                  <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow"></use>
                                </svg>
                            </span>
                        </a>
                    <? endforeach; ?>
                </div>
            </div>
            <div class="iprojects__pag">
                <div class="pag"></div>
            </div>
        </div>
    </div>
</div>



