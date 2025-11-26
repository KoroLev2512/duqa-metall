<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

$sections2LvlCountMax = 3;
?>
<div class="menu__list">
<?
foreach ($arResult['ITEMS'] as $itemLvl1):
    $isCatalog = false;
    $isParentLvl1 = false;

    if ($arParams['CATALOG_PATH'] == $itemLvl1['LINK']) {
        $isCatalog = true;
    }
    if($itemLvl1['IS_PARENT']){
        $isParentLvl1 = true;
    }
    ?>
        <div class="menu__item<?=$isCatalog ? " menu__item_catalog jsHoverLink" : ""?>">
            <a class="menu__link <?=$isParentLvl1 ? " menu__link_sub" : ""?>" href="<?=$itemLvl1['LINK']?>" rel=""><?=$itemLvl1['NAME']?></a>
            <?if(!empty($itemLvl1['CHILD'])):?>
                <div class="<?=$isCatalog ? "submenu" : "submenu2"?>">
                    <?foreach ($itemLvl1['CHILD'] as $itemLvl2):
                        $isParentLvl2 = false;
                        if($itemLvl2['IS_PARENT']){
                            $isParentLvl2 = true;
                        }
                        if($isCatalog){
                            if(!empty($itemLvl2['SECTION_PAGE_URL'])){
                                $itemLvl2['LINK'] = $itemLvl2['SECTION_PAGE_URL'];
                            }
                        }
                        if($isCatalog):
                            if(!empty($itemLvl2['UF_ICON'])) {

                                $UF_ICON = \Bitrix\Main\FileTable::getList([
                                    'select' => [
                                        'ID',
                                        'TIMESTAMP_X',
                                        'HEIGHT',
                                        'WIDTH',
                                        'FILE_SIZE',
                                        'CONTENT_TYPE',
                                        'SUBDIR',
                                        'FILE_NAME',
                                        'ORIGINAL_NAME',
                                        'DESCRIPTION',
                                        'SRC'
                                    ],
                                    'filter' => [
                                        'ID' => $itemLvl2['UF_ICON']
                                    ],
                                    'runtime' => [
                                        new \Bitrix\Main\Entity\ExpressionField('SRC' , "(CONCAT('/upload/',SUBDIR,'/',FILE_NAME))")
                                    ]
                                ]);
                                if($UF_ICON_OBJ = $UF_ICON->fetch()){
                                    $UF_ICON_SRC = $UF_ICON_OBJ['SRC'];
                                }
                            }else{
                                $UF_ICON_SRC = false;
                            }
                            ?>
                            <div class="submenu__cat">
                                <a class="submenu__title" href="<?=$itemLvl2['LINK']?>" >
                                    <?if($UF_ICON_SRC):?>
                                        <img class="submenu__title-svg" src="<?=$UF_ICON_SRC?>" alt="<?=$itemLvl2['NAME']?>">
                                    <?endif;?>
                                    <span class="submenu__title-title"><?=$itemLvl2['NAME']?></span></a>
                                <?if(!empty($itemLvl2['CHILD'])):?>
                                    <div class="submenu__list">
                                        <? foreach (array_slice($itemLvl2['CHILD'], 0, $sections2LvlCountMax) as $itemLvl3):
                                            $isParentLvl3 = false;
                                            if($itemLvl3['IS_PARENT']){
                                                $isParentLvl3 = true;
                                            }
                                            if($isCatalog){
                                                if(!empty($itemLvl3['SECTION_PAGE_URL'])){
                                                    $itemLvl3['LINK'] = $itemLvl3['SECTION_PAGE_URL'];
                                                }
                                            }
                                            ?>
                                            <a class="submenu__item" href="<?=$itemLvl3['LINK']?>"><?=$itemLvl3['NAME']?></a>
                                        <?endforeach;?>
                                    </div>

                                    <?if(count($itemLvl2['CHILD'])>$sections2LvlCountMax):?>
                                    <a class="submenu__more" href="<?=$itemLvl2['LINK']?>"><span
                                                class="submenu__more-round"></span><span
                                                class="submenu__more-round"></span><span
                                                class="submenu__more-round"></span></a>
                                    <?endif;?>
                                <?endif;?>
                            </div>
                        <?else:?>
                            <div class="jsHoverItem submenu2__item">
                                <a class="jsHoverLink submenu2__link <?=($isParentLvl2) ? "submenu2__link_arr" : ""?>" href="<?=$itemLvl2['LINK']?>" rel=""><?=$itemLvl2['NAME']?></a>
                                <?if($isParentLvl2):?>
                                <div class="submenu2 submenu2_right">
                                    <? foreach ($itemLvl2['CHILD'] as $itemLvl3):
                                            $isParentLvl3 = false;
                                            if($itemLvl3['IS_PARENT']){
                                                $isParentLvl3 = true;
                                            }?>
                                    <a class="submenu2__link" href="<?=$itemLvl3['LINK']?>"><?=$itemLvl3['NAME']?></a>
                                    <?endforeach;?>
                                </div>
                                <?endif;?>
                            </div>
                        <?endif;?>
                    <?endforeach;?>
                </div>
            <?endif;?>
        </div>
<?endforeach;?>
</div>

<div class="menu__more mmore">
    <button class="mmore__btn" type="button">
        <span class="mmore__btn-round"></span>
        <span class="mmore__btn-round"></span>
        <span class="mmore__btn-round"></span>
    </button>
    <div class="mmore__list"></div>
</div>
