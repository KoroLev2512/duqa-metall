<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if (empty($arResult)) {
    return "";
}

$strReturn = '';


$strReturn .= ' <div class="catalog__bread bread right__bread" itemprop="http://schema.org/breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';


$strReturn .= '<a class="bread__link" href="/">
            <svg class="bread__link-svg">
              <use xlink:href="'.SITE_TEMPLATE_PATH.'/images/icons/sprite.svg#home"></use>
            </svg>
          </a>';

$itemSize = count($arResult);
for ($index = 0; $index < $itemSize; $index++) {
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);

    if ($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1) {

        $strReturn .= '<a class="bread__link" id="bx_breadcrumb_'.$index
            .'" href="'.$arResult[$index]["LINK"]
            .'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" title="'
            .$title.'">
          <span itemprop="name" class="bread__link-txt">'.$title.'</span>
          <meta itemprop="position" content="'.($index + 1).'" />
          <link href="'.$arResult[$index]["LINK"].'" itemprop="item">
         </a>';
    } else {
        $strReturn .= '
				<span class="bread__link active" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
          <span itemprop="name" class="bread__link-txt">'.$title.'</span>
          <meta itemprop="position" content="'.($index + 1).'" />
          <link href="'.$arResult[$index]["LINK"].'" itemprop="item">
         </span>';
    }
}

$strReturn .= '<div style="clear:both"></div></div>';

return $strReturn;

