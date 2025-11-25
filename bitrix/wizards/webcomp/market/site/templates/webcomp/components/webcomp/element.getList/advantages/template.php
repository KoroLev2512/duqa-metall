<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);
?>

<div class="iadvantages">
    <div class="container">
        <div class="iadvantages__row">

            <? foreach ($arResult["ITEMS"] as $key => $arItem): ?>

                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                        "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                        "ELEMENT_DELETE"),
                    ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                ?>

                <div class="iadvantage"
                     id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <div class="iadvantage__img">
                        <?= CMarketView::showSvg($arItem["PROPERTIES"]["ICON"]["VALUE"]["SRC"],
                            'iadvantage__img-img') ?>
                    </div>
                    <h3 class="iadvantage__title"><?= $arItem["NAME"] ?></h3>
                    <div class="iadvantage__txt"><?= $arItem["PREVIEW_TEXT"] ?></div>
                </div>
            <? endforeach ?>

        </div>
    </div>
</div>



