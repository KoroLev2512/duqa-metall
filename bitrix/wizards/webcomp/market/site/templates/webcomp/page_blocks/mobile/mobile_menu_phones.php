<? if(!empty($arParams["ITEMS"])): ?>

<div class="mitem">
    <a class="mitem__link mitem__link_phone <?=($arParams["SHOW_DROP_BLOCK"]) ? "mitem__link_arr" : ""?>" href="tel:<?= current($arParams["ITEMS"])['~VALUE'] ?>">
        <span class="mitem__phone">
            <svg class="mitem__phone-svg">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#phone"></use>
            </svg>
            <span class="mitem__phone-wrap">
                <span class="mitem__phone-phone"><?= current($arParams["ITEMS"])['VALUE'] ?></span>
                <? if(!empty(current($arParams["ITEMS"])['DESCRIPTION'])): ?>
                    <span class="mitem__phone-txt"><?= current($arParams["ITEMS"])['DESCRIPTION'] ?></span>
                <? endif ?>
            </span>
        </span>
    </a>
    <? if($arParams["SHOW_DROP_BLOCK"]):?>
        <div class="mmenu__wrapper">
            <div class="mmenu__top">
                <div class="mmenu__top-left">
                    <button class="mmenu__title jsWrapClose" type="button">
                        <svg class="mmenu__title-svg">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arrow-s-left"></use>
                        </svg>
                        <span class="mmenu__title-title">Телефоны</span>
                    </button>
                </div>

                <div class="mmenu__top-right">
                    <button class="mmenu__close jsMenuClose" type="button">
                        <svg class="mmenu__close-svg">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#close"></use>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mmenu__bottom">
                <div class="mmenu__list">
                    <? foreach($arParams["ITEMS"] as $key => $phone): ?>
                        <? if(!$key) continue?>
                        <div class="mitem">
                            <a class="mitem__link mitem__link_phone" href="tel:<?=$phone["~VALUE"]?>">
                                    <span class="mitem__phone">
                                        <span class="mitem__phone-wrap">
                                            <span class="mitem__phone-phone"><?=$phone["VALUE"]?></span>
                                            <? if(!empty($phone['DESCRIPTION'])): ?>
                                                <span class="mitem__phone-txt"><?=$phone['DESCRIPTION']?></span>
                                            <? endif ?>
                                        </span>
                                    </span>
                            </a>
                        </div>
                    <? endforeach ?>
                </div>
            </div>

        </div>
    <? endif ?>
</div>

<? endif ?>