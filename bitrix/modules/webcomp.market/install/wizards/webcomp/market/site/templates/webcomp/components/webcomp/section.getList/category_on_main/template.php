<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);
if(!empty($arResult['ITEMS'])):
?>
<div class="icats">
    <div class="container">
        <div class="icats__top title">
            <h2 class="icats__title title__title">
                <?= $arParams['TITLE']; ?>
                <? if($arParams["LINK_LINK"]): ?>
                    <a class="view-all-link" href="<?= $arParams["LINK_LINK"] ?>" title="<?= $arParams['LINK_TITLE']?>">
                        <span class="view-all-link__icon">›</span>
                    </a>
                <? endif ?>
            </h2>
        </div>
        <div class="icats__bottom">
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
                    <a class="icat" href="<?= $arItem["SECTION_PAGE_URL"] ?>"
                       id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <span class="icat__img">
                        <img class="icat__img-img"
                             src="<?= CFile::getPath($arItem["PICTURE"]) ?>"
                             alt="<?= $arItem["NAME"] ?>"/>
                    </span>
                        <span class="icat__bottom">
                        <span class="icat__title"><?= $arItem["NAME"] ?></span>
                    </span>
                    </a>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</div>
<? endif ?>
