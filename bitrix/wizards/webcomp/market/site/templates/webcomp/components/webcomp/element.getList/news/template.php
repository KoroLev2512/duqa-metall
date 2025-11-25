<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);
?>
<div class="row">
    <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
            CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
            CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
            ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
        ?>
        <div class="news__item"
             id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <a class="nitem" href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                <span class="nitem__top">
                    <span class="nitem__img">
                        <img class="nitem__img-img"
                             src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']; ?>"
                             alt="<?= $arItem['NAME']; ?>"/>
                    </span>
                </span>
                <span class="nitem__bottom">
                    <span class="nitem__date"><?= $arItem['ACTIVE_FROM']->format('d.m.Y'); ?></span>
                    <span class="nitem__title"><?= $arItem['NAME']; ?></span>
                    <span class="nitem__txt">
                        <?= $arItem['PREVIEW_TEXT']; ?>
                    </span>
                    <span class="nitem__link link">
                        <span class="link__txt nitem__link-txt">Подробнее</span>
                            <svg class="link__svg nitem__link-svg">
                              <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow-s"></use>
                            </svg>
                    </span>
                </span>
            </a>
        </div>
    <? endforeach; ?>
</div>
<button class="news__more" type="button">Показать еще</button>
<div class="news__pag">
    <div class="pagination"><a class="pagination__item pagination__item_left"
                               href="#WIZARD_SITE_DIR#">
            <svg class="pagination__item-svg">
                <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#arr-l"></use>
            </svg>
        </a><a class="pagination__item active" href="#WIZARD_SITE_DIR#"><span
                    class="pagination__item-txt">1</span></a><a
                class="pagination__item"
                href="#WIZARD_SITE_DIR#"><span
                    class="pagination__item-txt">2</span></a><a
                class="pagination__item"
                href="#WIZARD_SITE_DIR#"><span
                    class="pagination__item-txt">3</span></a><a
                class="pagination__item"
                href="#WIZARD_SITE_DIR#"><span
                    class="pagination__item-txt">...</span></a><a
                class="pagination__item"
                href="#WIZARD_SITE_DIR#"><span
                    class="pagination__item-txt">48</span></a><a
                class="pagination__item pagination__item_right"
                href="#WIZARD_SITE_DIR#">
            <svg class="pagination__item-svg">
                <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#arr-r"></use>
            </svg>
        </a></div>
</div>
