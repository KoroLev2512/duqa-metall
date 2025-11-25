<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);

?>
<? if ( ! empty($arResult['ITEMS'])): ?>
    <div class="iservices">
        <div class="container">
            <div class="iservices__top title">
                <h2 class="iservices__title title__title">
                    <?= $arParams['TITLE']; ?>

                    <? if($arParams["LINK_LINK"]): ?>
                        <a class="view-all-link" href="<?= $arParams["LINK_LINK"] ?>" title="<?= $arParams['LINK_TITLE']?>">
                            <span class="view-all-link__icon">›</span>
                        </a>
                    <? endif ?>
                </h2>
            </div>
            <div class="iservices__bottom">
                <div class="row">
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
                        ?>
                        <div class="iservices__item"
                             id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                            <a class="iservice"
                               href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                            <span class="iservice__left">
                                <span class="iservice__title"><?= $arItem['NAME']; ?></span>
                                    <svg class="iservice__svg">
                                      <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow"></use>
                                    </svg>
                            </span>
                                <span class="iservice__right">
                                <img class="iservice__img"
                                     src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']; ?>"
                                     alt="<?= $arItem['NAME']; ?>"/>
                            </span>
                            </a>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<? endif ?>


