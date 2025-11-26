<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?// print_r($arResult["SECTIONS"])?>

<? if(!empty($arResult["SECTIONS"])): ?>
    <? foreach($arResult["SECTIONS"] as $key => $section): ?>
        <? if(empty($section["ITEMS"])) continue ?>

        <div class="company-documents__section">
            <div class="company-documents__heading"><?=$section["NAME"]?></div>
            <? if(!empty($section["DESCRIPTION"])): ?>
                <div class="company-documents__description"><?=$section["DESCRIPTION"]?></div>
            <? endif ?>

            <? foreach ($section["ITEMS"] as $arItem): ?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>

                <div class="company-documents__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <div class="company-documents__item-left">
                        <a href="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" data-fancybox="documents">
                            <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>">
                        </a>
                    </div>
                    <div class="company-documents__item-right">
                        <div class="company-documents__item-title"><?=$arItem["NAME"]?></div>
                        <div class="company-documents__item-description"><?=$arItem["PREVIEW_TEXT"]?></div>
                    </div>
                </div>
            <? endforeach; ?>

        </div>
    <? endforeach ?>
<? endif ?>
</div>
