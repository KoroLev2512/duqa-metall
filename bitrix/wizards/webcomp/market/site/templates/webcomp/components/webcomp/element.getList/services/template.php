<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);
?>
<div class="catalog__list services__list">
    <div class="row">
        <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
            <?
            // TODO: Добавить ссылку для добавления элемента ADD_LINK
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
            ?>
            <div class="services__item"
                 id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <a class="iservice" href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                <span class="iservice__left">
                    <span class="iservice__title"><?= $arItem['NAME']; ?></span>
                  <svg class="iservice__svg">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow"></use>
                  </svg>
                </span>
                    <span class="iservice__right">
                    <img class="iservice__img"
                         src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']; ?>"
                         alt="<?= $arItem['NAME']; ?>">
                </span>
                </a>
            </div>
        <? endforeach ?>
    </div>
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
