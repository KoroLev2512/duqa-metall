<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);

?>

<? foreach ($arResult['ITEMS'] as $arItem): ?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
        ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
    ?>

    <div class="ipromo" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
        <div class="ipromo__img">
            <img class="ipromo__img-img"
                 src="<?= $arItem['DETAIL_PICTURE_VALUE']['SRC']; ?>"
                 alt="<?= $arItem['NAME']; ?>"/>
            <img class="ipromo__img-img ipromo__img-img_m"
                 src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']; ?>"
                 alt="<?= $arItem['NAME']; ?>"/>
        </div>
        <div class="ipromo__wrapper">
            <div class="container ipromo__container">
                <div class="ipromo__content">
                    <h2 class="ipromo__title"><?= $arItem['NAME']; ?></h2>
                    <div class="ipromo__txt">
                        <?= $arItem['PREVIEW_TEXT']; ?>
                    </div>
                    <? if ( ! empty($arItem['PROPERTIES']['link'])): ?>
                        <a class="btn4 ipromo__link"
                           href="<?= $arItem['PROPERTIES']['link']['VALUE']; ?>">ПОДРОБНЕЕ</a>
                    <? endif; ?>
                </div>
            </div>
        </div>
    </div>
<? endforeach; ?>

