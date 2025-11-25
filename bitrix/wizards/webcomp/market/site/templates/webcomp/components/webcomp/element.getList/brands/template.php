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
        <a class="brands__item" href="<?= $arItem['DETAIL_PAGE_URL']; ?>"
           id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
        <span class="brands__item-wrap">
            <img class="brands__item-img"
                 src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']; ?>"
                 alt="<?= $arItem['NAME']; ?>"/>
        </span>
        </a>
    <? endforeach; ?>
</div>
