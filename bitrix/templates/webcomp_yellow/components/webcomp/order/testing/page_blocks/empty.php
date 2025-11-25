<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
?>

<div class="cart-empty">
    <div class="cart-empty__title"><?= Loc::getMessage("WEBCOMP_ORDER_EMPTY") ?></div>
    <div class="cart-empty__text"><?= Loc::getMessage("WEBCOMP_ORDER_EMPTY_TEXT") ?>
        <span class="popup__btn-img">
            <?=CMarketView::showIcon("cart", "popup__btn-svg popup__btn-svg_compare bread__link-svg")?>
        </span>
    </div>
    <a class="cart-empty__btn btn" href="/catalog/">
        <span><?= Loc::getMessage("WEBCOMP_ORDER_GO_CATALOG") ?></span>
    </a>
</div>
