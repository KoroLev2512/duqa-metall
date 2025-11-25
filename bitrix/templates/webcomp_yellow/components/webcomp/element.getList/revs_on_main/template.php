<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

?>

<?
$arSettings = [];
$arSettings["AUTO_PLAY_SPEED"] = (int)$arParams["AUTO_PLAY_SPEED"];
$arSettings["AUTO_PLAY_DELAY_SPEED"] = (int)$arParams["AUTO_PLAY_DELAY_SPEED"];
$arSettings["AUTO_PLAY"] = ($arParams["AUTO_PLAY"] === "Y") ? "true" : "false";
$arSettings["PAGINATION"] = ($arParams["PAGINATION"] === "Y") ? "true"
    : "false";
?>

<div class="irevs">
    <div class="container">
        <div class="irevs__top title">
            <h2 class="irevs__title title__title">
                <?= $arParams['TITLE']; ?>
                <? if($arParams["LINK_LINK"]): ?>
                    <a class="view-all-link" href="<?= $arParams["LINK_LINK"] ?>" title="<?= $arParams['LINK_TITLE']?>">
                        <span class="view-all-link__icon">›</span>
                    </a>
                <? endif ?>
            </h2>
        </div>
        <div class="irevs__bottom">
            <div class="irevs__slider"
                 data-speed="<?= $arSettings["AUTO_PLAY_SPEED"] ?>"
                 data-autoplay="<?= $arSettings["AUTO_PLAY"] ?>"
                 data-autoplayDelay="<?= $arSettings["AUTO_PLAY_DELAY_SPEED"] ?>"
                 data-pagination="<?= $arSettings["PAGINATION"] ?>">
                <button class="arrow irevs__prev" type="button">
                    <?= CMarketView::showIcon("prev", "arrow__svg") ?>
                </button>
                <div class="swiper-container irevs__container">
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

                            $props = $arItem["PROPERTIES"];
                            $photo = ( ! empty($props["PHOTO"]["VALUE"]["SRC"]))
                                ? $props["PHOTO"]["VALUE"]["SRC"]
                                : SITE_TEMPLATE_PATH
                                ."/images/reviewsDefault.png"

                            ?>
                            <div class="swiper-slide irev"
                                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                            <span class="irev__img">
                                <span class="irev__img-wrap">
                                    <img class="irev__img-img"
                                         src="<?= $photo ?>"
                                         alt="<?= $arItem['NAME']; ?>"/>
                                </span>
                            </span>
                                <span class="irev__bottom">
                                <span class="irev__title"><?= $arItem['NAME']; ?></span>
                                <span class="irev__prof"><?= $props['POSITION']['VALUE']; ?></span>
                                <span class="irev__rating">
                                    <span class="rating">
                                        <span class="rating__list">
                                            <? for ($i = 0; $i < 5; $i++): ?>
                                                <span class="rating__star <?= ($i
                                                    < $props['RATING']['VALUE'])
                                                    ? 'active' : ''; ?>">
                                                    <?= CMarketView::showIcon("star",
                                                        "rating__star-svg") ?>
                                                </span>
                                            <? endfor; ?>
                                        </span>
                                    </span>
                                </span>
                                <span class="irev__txt">
                                   <?= unserialize($props["MESSAGE"]["VALUE"])["TEXT"] ?>
                                </span>
                            </span>
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
                <button class="arrow irevs__next" type="button">
                    <?= CMarketView::showIcon("next", "arrow__svg") ?>
                </button>
            </div>
            <div class="irevs__pag">
                <div class="pag"></div>
            </div>
            <button class="irevs__btn btn" type="button" data-trigger="click"
                    data-target="REVIEWS" data-elements_id="0">
                <?= Loc::getMessage("SEND_REVIEWS_BTN") ?>
            </button>
        </div>
    </div>
</div>

