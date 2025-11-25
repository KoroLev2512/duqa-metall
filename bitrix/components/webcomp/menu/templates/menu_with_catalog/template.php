<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?
function renderTemplate($items,$catalogUrl='') {
    if(is_array($items)):
        foreach ($items as $item):
            if($item['SELECTED']){
                $selected = ' selected';
            }else{
                $selected = '';
            }
            if($item['IS_PARENT']){
                $isParent = ' is_parent';
            }else{
                $isParent = '';
            }
            if(!empty($catalogUrl) && $item['DEPTH_LEVEL']==1 && $catalogUrl==$item['LINK']){
                $isCatalog = ' catalog_parent_menu';
            }else{
                $isCatalog = '';
            }

            echo "<div class='menu__level-{$item['DEPTH_LEVEL']}{$isParent}{$selected}{$isCatalog}'>";
                echo "<div class='menu__section-name'>{$item['NAME']}</div>";
                if(@count($item['CHILD']) > 0):
                    renderTemplate($item['CHILD']);
                endif;
            echo '</div>';
        endforeach;
    endif;
}
renderTemplate($arResult['ITEMS'],$arParams['CATALOG_PATH']);
?>
