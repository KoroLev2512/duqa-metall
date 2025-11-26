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
    <div class="iabout" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
        <div class="container">
            <div class="row iabout__row">
                <div class="iabout__left">
                    <img class="iabout__img"
                         src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']; ?>"
                         alt="<?= $arItem['NAME']; ?>"/>
                </div>
                <div class="iabout__right">
                    <div class="iabout__top">
                        <div class="iabout__bread"><?= $arItem['NAME']; ?></div>
                        <h2 class="iabout__title">
                            <?= unserialize($arItem['PROPERTIES']['subtitle']['VALUE'])['TEXT']; ?>
                        </h2>
                    </div>
                    <div class="iabout__content">
                        <?= $arItem['PREVIEW_TEXT']; ?>
                    </div>
                    <div class="iabout__btns">
                        <? if ($arParams['LINK'] == 'Y'): ?>
                            <a class="btn iabout__link"
                               href="<?= $arParams['LINK_LINK']; ?>"><?= $arParams['LINK_TITLE']; ?></a>
                        <? endif; ?>

                        <? if ($arParams['LINK2'] == 'Y'): ?>
                            <a class="btn2 iabout__catalog"
                               href="<?= $arParams['LINK2_LINK']; ?>"><?= $arParams['LINK2_TITLE']; ?></a>
                        <? endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? endforeach; ?>

