<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);
?>
<div class="new__services">
    <div class="nservices">
        <div class="nservices__title"><?= $arParams['TITLE'] ?></div>
        <div class="nservices__list">
            <? foreach ($arResult["ITEMS"] as $key => $arItem): ?>
                <?
                $result = [
                    "ID"              => $arItem["ID"],
                    "NAME"            => $arItem["NAME"],
                    "PREVIEW_PICTURE" => CFile::getPath($arItem["PREVIEW_PICTURE"]),
                    "DETAIL_PAGE_URL" => $arItem["DETAIL_PAGE_URL"],
                ];
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                        "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                    CIBlock::GetArrayByID($arItem["IBLOCK_ID"],
                        "ELEMENT_DELETE"),
                    ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                ?>
                <a id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
                   class="nservice" href="<?= $result["DETAIL_PAGE_URL"] ?>">
                <span class="nservice__left">
                    <span class="nservice__img">
                        <img class="nservice__img-img"
                             src="<?= $result["PREVIEW_PICTURE"] ?>"
                             alt="<?= $result["NAME"] ?>"/>
                    </span>
                </span>
                    <span class="nservice__right">
                    <span class="nservice__main">
                        <span class="nservice__title"><?= $result["NAME"] ?></span>
                        <span class="nservice__txt"><?= $result["PREVIEW_TEXT"] ?></span>
                    </span>
                    <span class="nservice__link"><span class="link__txt">Подробнее</span>
                        <svg class="link__svg">
                          <use xlink:href="/images/icons/sprite.svg#arrow-s"></use>
                        </svg>
                    </span>
                </span>
                </a>
            <? endforeach ?>
        </div>
    </div>
</div>