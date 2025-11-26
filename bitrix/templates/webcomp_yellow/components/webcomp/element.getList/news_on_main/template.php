<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;
use Webcomp\Market\Tools;

//Diag\Debug::dump($arResult);
?>

<div class="inews">
    <div class="container">
        <div class="inews__top title">
            <h2 class="inews__title title__title">
                <?= $arParams['TITLE']; ?>
                <? if($arParams["LINK_LINK"]): ?>
                    <a class="view-all-link" href="<?= $arParams["LINK_LINK"] ?>" title="<?= $arParams['LINK_TITLE']?>">
                        <span class="view-all-link__icon">›</span>
                    </a>
                <? endif ?>
            </h2>
        </div>
        <div class="iactions__bottom">
            <div class="row">
                <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
                    <?
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                        CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                            "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'],
                        $arItem['DELETE_LINK'],
                        CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                            "ELEMENT_DELETE"),
                        ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                    ?>
                    <a class="inew" href="<?= $arItem['DETAIL_PAGE_URL']; ?>"
                       id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <span class="inew__img">
                        <img class="inew__img-img"
                             src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']; ?>"
                             alt="<?= $arItem['NAME']; ?>"/>
                    </span>
                        <span class="inew__bottom">
                        <span class="inew__date"><?= $arItem['ACTIVE_FROM']->format('d.m.Y'); ?></span>
                        <span class="inew__title"><?= $arItem['NAME']; ?></span>
                        <span class="inew__txt"><?= Tools::cutString($arItem['PREVIEW_TEXT']) ?></span>
                        <span class="inew__link link">
                            <span class="inew__link-txt link__txt"><?=getMessage("WEBCOMP_MORE_LINK")?></span>
                            <svg class="inew__link-svg link__svg">
                              <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow-s"></use>
                            </svg>
                        </span>
                    </span>
                    </a>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</div>



