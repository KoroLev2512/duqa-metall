<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

//Diag\Debug::dump($arResult);
if(!empty($arResult['ITEMS'])):
?>

  <div class="ipops">
    <div class="container">
        <div class="ipops__top title">
            <h2 class="ipops__title title__title">
                <?=$arParams['TITLE']; ?>
                <? if($arParams["LINK_LINK"]): ?>
                    <a class="view-all-link" href="<?= $arParams["LINK_LINK"] ?>" title="<?= $arParams['LINK_TITLE']?>">
                        <span class="view-all-link__icon">›</span>
                    </a>
                <? endif ?>
            </h2>
        </div>
        <div class="ipops__bottom">
            <div class="row">
                <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
                    <?

                    //print_r($arItem);
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <a class="ipop" href="<?= $arItem['DETAIL_PAGE_URL'] ?>" id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
                        <span class="ipop__img">
                            <img class="ipop__img-img" src="<?= $arItem['PREVIEW_PICTURE_VALUE']['SRC']?>" alt="<?= $arItem['NAME'] ?>"/>
                        </span>
                        <span class="ipop__title"><?= $arItem['NAME'] ?></span>
                        <span class="ipop__bottom">

                        <? if (!empty($arItem["PROPERTIES"]["PRICE"]["VALUE"])): ?>
                            <span class="ipop__prices">
                                <span class="<?=(!empty($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"])) ? "price_green" : ""?> ipop__price price"><?=$arItem["PROPERTIES"]["PRICE"]["VALUE"]?>
                                </span>

                                <? if (!empty($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"])): ?>
                                    <span class="ipop__priceold priceold"><?=$arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"]?></span> 
                                <? endif ?>
                            </span>
                        <? endif ?>
                        

                          <svg class="ipop__svg">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow"></use>
                          </svg>
                      </span>
                    </a>

                <? endforeach ?>
            </div>
        </div>
    </div>
</div>
<? endif ?>

