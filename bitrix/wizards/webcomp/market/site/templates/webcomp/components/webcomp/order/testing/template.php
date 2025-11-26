<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<? if (isset($_SESSION["CART"]) && !empty($_SESSION["CART"])): ?>

    <? global $totalPrice, $totalOldPrice, $cartCount, $USER ?>
    <? $totalPrice = $totalOldPrice = 0 ?>

    <div id="cartRender">
        <form id="cartForm" class="basket__form catalog_basket"
              action="#WIZARD_SITE_DIR#ajax/cart/" method="POST">
            <div class="basket__left">

                <? include_once("page_blocks/items.php") ?>

                <div class="basket__list">
                    <? include_once("page_blocks/form.php") ?>
                    <? include_once("page_blocks/delivery.php") ?>
                    <? include_once("page_blocks/pay.php") ?>
                </div>

                <? include_once("page_blocks/totalLeftBlock.php") ?>

            </div>

            <div class="basket__right">
                <? if (!$USER->IsAuthorized()): ?>
                    <? if ($WEBCOMP["SETTINGS"]["WEBCOMP_CHECKBOX_LK"] === "Y"): ?>
                        <? include_once("page_blocks/auth.php") ?>
                    <? endif; ?>
                <? endif ?>
                <? include_once("page_blocks/totalRightBlock.php") ?>
            </div>
        </form>
    </div>

    <template id="tpl_emptyCart">
        <div class="cart-empty">
            <div class="cart-empty__title"><?= Loc::getMessage("WEBCOMP_ORDER_EMPTY") ?></div>
            <div class="cart-empty__text"><?= Loc::getMessage("WEBCOMP_ORDER_EMPTY_TEXT") ?>
                <span class="popup__btn-img">
                  <svg class="popup__btn-svg popup__btn-svg_compare bread__link-svg">
                    <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                  </svg>
                </span>
            </div>
            <a class="cart-empty__btn btn" href="#WIZARD_SITE_DIR#catalog/">
                <span><?= Loc::getMessage("WEBCOMP_ORDER_GO_CATALOG") ?></span>
            </a>
        </div>
    </template>

    <template id="tpl_productsCount">
        <b>
            {{COUNT_PRD}}
        </b>
    </template>

<? else: ?>
    <? include_once("page_blocks/empty.php") ?>
<? endif ?>

