<? if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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
<? $offerDate = ''; ?>

<? // TODO: На этой странице должен быть баннер, надо подумать как его лучше впилить ?>

<? //print_r($arResult["ACTIVE_TO"])?>

<? if ( ! empty($arResult["ACTIVE_FROM"])): ?>
    <?
    $offerDate = '<h5>';
    $offerDate .= 'Акция дествует с '.date("d.m.Y",
            strtotime($arResult["ACTIVE_FROM"]));

    if (isset($arResult["ACTIVE_TO"]) && ! empty($arResult["ACTIVE_TO"])) {
        $offerDate .= ' до '.date("d.m.Y", strtotime($arResult["ACTIVE_TO"]))
            .' г.';
    }

    $offerDate .= '</h5>';
    ?>

<? endif ?>


<div class="service__top">

    <? if ( ! empty($arResult["DETAIL_PICTURE"]["SRC"])): ?>
        <div class="service__img">
            <img class="service__img-img"
                 src="<?= $arResult["DETAIL_PICTURE"]["SRC"] ?>"
                 alt="<?= $arResult["NAME"] ?>">
        </div>
    <? endif ?>

    <? if ( ! empty($arResult["DETAIL_TEXT"])): ?>
        <div class="service__content">
            <div class="content">
                <?= $arResult["DETAIL_TEXT"] ?>
            </div>
        </div>
    <? endif ?>

    <?= $offerDate ?>
</div>

<? if ( ! empty($arResult["PROPERTIES"]["SUB_TITLE"]["VALUE"])): ?>
    <blockquote class="service__quote">
        <?= $arResult["PROPERTIES"]["SUB_TITLE"]["VALUE"] ?>
    </blockquote>
<? endif ?>



<? //TODO: Скорее всего надо сделать настройку на вывод этого блока ?>
<div class="service__banner">
    <div class="sbanner">
        <div class="sbanner__left">
            <div class="sbanner__img"><img class="sbanner__img-img"
                                           src="<?= SITE_TEMPLATE_PATH ?>/images/content/service/icon.svg"
                                           alt="Заказать услугу <?= $arResult["NAME"] ?>">
            </div>
            <div class="sbanner__txt"><?= GetMessage("BANNER_TEXT") ?></div>
        </div>
        <div class="sbanner__right">

            <? if ( ! empty($arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"])): ?>
                <div class="sbanner__oldprice"><?= $arResult["PROPERTIES"]["OLD_PRICE"]["VALUE"] ?></div>
            <? endif ?>

            <? if ( ! empty($arResult["PROPERTIES"]["PRICE"]["VALUE"])): ?>
                <div class="sbanner__price"><?= $arResult["PROPERTIES"]["PRICE"]["VALUE"] ?></div>
            <? endif ?>

            <button data-id="<?= $arResult["ID"] ?>"
                    class="btn sbanner__btn jsCall"
                    type="button"><?= GetMessage("CALLORDER_BUTTON_TEXT") ?></button>
        </div>
    </div>
</div>

<? // TODO: Подумать как лучше подключить, может лучше собирать свойства в result_modifire, или делать компонент и вызываеть его, может даже в component_epiloge?>
<div class="service__recom">
    <div class="precom">
        <div class="precom__top">
            <div class="precom__title"><?= GetMessage("RECOM_PRODUCTS") ?></div>
            <div class="precom__nav">
                <button class="precom__prev product__arr" type="button"
                        tabindex="0" role="button" aria-label="Previous slide">
                    <svg class="product__arr-svg">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arr-l"></use>
                    </svg>
                </button>
                <button class="precom__next product__arr" type="button"
                        tabindex="0" role="button" aria-label="Next slide">
                    <svg class="product__arr-svg">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#arr-r"></use>
                    </svg>
                </button>
            </div>
        </div>
        <div class="precom__bottom">
            <div class="precom__slider" data-speed="500" data-pagination="true">
                <div class="swiper-container precom__container swiper-container-initialized swiper-container-horizontal">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide precom__item">
                            <!-- Дополнительные классы - nopadding, noheight, disabled-->
                            <a class="item" href="product.html">
                	<span class="item__top">
                		<span class="item__img">
                			<span class="item__propportion pt pt_221x177"></span>
                			<span class="item__img-wrap">
                				<img class="item__img-img"
                                     src="<?= SITE_TEMPLATE_PATH ?>/images/content/catalog/2.png"
                                     alt="lorem">
                			</span>
                			<span class="item__sticks">
                				<span class="sticks">
                					<span class="stick stick_hit">Хит</span>
                					<span class="stick stick_recom">Советуем</span>
                					<span class="stick stick_new">Новинка</span>
                					<span class="stick stick_action">Акция</span>
                				</span>
                			</span>
                			<span class="item__controls">
                				<span class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#compare"></use>
                          </svg>
                        </span>
                        <span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#heart"></use>
                          </svg>
                        </span>
                      </span>
                    </span>
                  </span>
                                <span class="item__bottom">
                  	<span class="item__content">
                  		<span class="item__avaible">
                  			<span class="item__avaible__round"></span>
                  			<span class="item__avaible__txt">В наличии</span>
                  		</span>
                  		<span class="item__prices">
                  			<span class="item__price price">32 990 руб.</span>
                  			<span class="item__priceold priceold"></span>
                  		</span>
                  		<span class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch Professional 0602490671...</span>
                  	</span>
                  	<span class="item__btns">
                  		<span class="add_in item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span>
                        <span class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#one"></use>
                        </svg>
                        <span class="btn3__txt">Купить в 1 клик</span>
                      </span>
                    </span>
                  </span>
                                <span class="item__controls item__controls_list">
                  	<span class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#compare"></use>
                      </svg>
                    </span>
                    <span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/images/icons/sprite.svg#heart"></use>
                      </svg>
                    </span>
                  </span>
                            </a>
                        </div>

                        <div class="swiper-slide precom__item swiper-slide-duplicate"
                             data-swiper-slide-index="2"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/1.jpg"
                                                    alt="lorem"></span><span
                                                class="item__sticks"></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">7 000 РУБ.</span><span
                                                    class="item__priceold priceold">65 000 руб.</span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch Professional 0602490671...</span></span><span
                                            class="item__btns"><span
                                                class="item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item swiper-slide-duplicate"
                             data-swiper-slide-index="3"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/2.png"
                                                    alt="lorem"></span><span
                                                class="item__sticks"></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">7 000 РУБ.</span><span
                                                    class="item__priceold priceold">65 000 руб.</span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT</span></span><span
                                            class="item__btns"><span
                                                class="item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item swiper-slide-duplicate swiper-slide-prev"
                             data-swiper-slide-index="4"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/1.jpg"
                                                    alt="lorem"></span><span
                                                class="item__sticks"></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">7 000 РУБ.</span><span
                                                    class="item__priceold priceold">65 000 руб.</span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch Professional 0602490671...</span></span><span
                                            class="item__btns"><span
                                                class="item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item swiper-slide-active"
                             data-swiper-slide-index="0"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/1.jpg"
                                                    alt="lorem"></span><span
                                                class="item__sticks"><span
                                                    class="sticks"><span
                                                        class="stick stick_hit">Хит</span><span
                                                        class="stick stick_recom">Советуем</span><span
                                                        class="stick stick_new">Новинка</span><span
                                                        class="stick stick_action">Акция</span></span></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">7 000 РУБ.</span><span
                                                    class="item__priceold priceold">65 000 руб.</span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch Professional 0602490671...</span></span><span
                                            class="item__btns"><span
                                                class="item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item swiper-slide-next"
                             data-swiper-slide-index="1"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/2.png"
                                                    alt="lorem"></span><span
                                                class="item__sticks"><span
                                                    class="sticks"><span
                                                        class="stick stick_hit">Хит</span><span
                                                        class="stick stick_recom">Советуем</span><span
                                                        class="stick stick_new">Новинка</span><span
                                                        class="stick stick_action">Акция</span></span></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">32 990 руб.</span><span
                                                    class="item__priceold priceold"></span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch Professional 0602490671...</span></span><span
                                            class="item__btns"><span
                                                class="add_in item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item"
                             data-swiper-slide-index="2"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/1.jpg"
                                                    alt="lorem"></span><span
                                                class="item__sticks"></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">7 000 РУБ.</span><span
                                                    class="item__priceold priceold">65 000 руб.</span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch Professional 0602490671...</span></span><span
                                            class="item__btns"><span
                                                class="item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item"
                             data-swiper-slide-index="3"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/2.png"
                                                    alt="lorem"></span><span
                                                class="item__sticks"></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">7 000 РУБ.</span><span
                                                    class="item__priceold priceold">65 000 руб.</span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT</span></span><span
                                            class="item__btns"><span
                                                class="item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item swiper-slide-duplicate-prev"
                             data-swiper-slide-index="4"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/1.jpg"
                                                    alt="lorem"></span><span
                                                class="item__sticks"></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">7 000 РУБ.</span><span
                                                    class="item__priceold priceold">65 000 руб.</span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch Professional 0602490671...</span></span><span
                                            class="item__btns"><span
                                                class="item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item swiper-slide-duplicate swiper-slide-duplicate-active"
                             data-swiper-slide-index="0"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/1.jpg"
                                                    alt="lorem"></span><span
                                                class="item__sticks"><span
                                                    class="sticks"><span
                                                        class="stick stick_hit">Хит</span><span
                                                        class="stick stick_recom">Советуем</span><span
                                                        class="stick stick_new">Новинка</span><span
                                                        class="stick stick_action">Акция</span></span></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">7 000 РУБ.</span><span
                                                    class="item__priceold priceold">65 000 руб.</span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch Professional 0602490671...</span></span><span
                                            class="item__btns"><span
                                                class="item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item swiper-slide-duplicate swiper-slide-duplicate-next"
                             data-swiper-slide-index="1"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/2.png"
                                                    alt="lorem"></span><span
                                                class="item__sticks"><span
                                                    class="sticks"><span
                                                        class="stick stick_hit">Хит</span><span
                                                        class="stick stick_recom">Советуем</span><span
                                                        class="stick stick_new">Новинка</span><span
                                                        class="stick stick_action">Акция</span></span></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">32 990 руб.</span><span
                                                    class="item__priceold priceold"></span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch Professional 0602490671...</span></span><span
                                            class="item__btns"><span
                                                class="add_in item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item swiper-slide-duplicate"
                             data-swiper-slide-index="2"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/1.jpg"
                                                    alt="lorem"></span><span
                                                class="item__sticks"></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">7 000 РУБ.</span><span
                                                    class="item__priceold priceold">65 000 руб.</span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT 30 без аккум. и ЗУ Bosch Professional 0602490671...</span></span><span
                                            class="item__btns"><span
                                                class="item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                        <div class="swiper-slide precom__item swiper-slide-duplicate"
                             data-swiper-slide-index="3"
                             style="width: 238.25px; margin-right: -1px;">
                            <!-- Дополнительные классы - nopadding, noheight, disabled--><a
                                    class="item" href="product.html"><span
                                        class="item__top"><span
                                            class="item__img"><span
                                                class="item__propportion pt pt_221x177"></span><span
                                                class="item__img-wrap"><img
                                                    class="item__img-img"
                                                    src="images/content/catalog/2.png"
                                                    alt="lorem"></span><span
                                                class="item__sticks"></span><span
                                                class="item__controls"><span
                                                    class="item__control item__control_compare jsCompare">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                          </svg></span><span class="item__control item__control_favorite jsFavorite active">
                          <svg class="item__control-svg">
                            <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                          </svg></span></span></span></span><span
                                        class="item__bottom"><span
                                            class="item__content"><span
                                                class="item__avaible"><span
                                                    class="item__avaible__round"></span><span
                                                    class="item__avaible__txt">В наличии</span></span><span
                                                class="item__prices"><span
                                                    class="item__price price">7 000 РУБ.</span><span
                                                    class="item__priceold priceold">65 000 руб.</span></span><span
                                                class="item__title">Аккумуляторный шуруповёрт ANGLE EXACT</span></span><span
                                            class="item__btns"><span
                                                class="item__buy jsAdd add">
                        <svg class="add__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#check"></use>
                        </svg><span class="add__txt">ДОБАВИТЬ В КОРЗИНУ</span><span
                                                    class="add__txt2">ТОВАР В КОРЗИНЕ</span>
                        <svg class="add__mobile">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#cart"></use>
                        </svg></span><span class="item__fast jsFast btn3">
                        <svg class="btn3__svg">
                          <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#one"></use>
                        </svg><span class="btn3__txt">Купить в 1 клик</span></span></span></span><span
                                        class="item__controls item__controls_list"><span
                                            class="item__control item__control_compare jsCompare">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#compare"></use>
                      </svg></span><span class="item__control item__control_favorite jsFavorite active">
                      <svg class="item__control-svg">
                        <use xlink:href="#WIZARD_SITE_DIR#images/icons/sprite.svg#heart"></use>
                      </svg></span></span></a>
                        </div>
                    </div>
                    <span class="swiper-notification" aria-live="assertive"
                          aria-atomic="true"></span></div>
                <div class="precom__pag">
                    <div class="pag swiper-pagination-clickable swiper-pagination-bullets">
                        <span class="swiper-pagination-bullet swiper-pagination-bullet-active"
                              tabindex="0" role="button"
                              aria-label="Go to slide 1"></span><span
                                class="swiper-pagination-bullet" tabindex="0"
                                role="button" aria-label="Go to slide 2"></span><span
                                class="swiper-pagination-bullet" tabindex="0"
                                role="button" aria-label="Go to slide 3"></span><span
                                class="swiper-pagination-bullet" tabindex="0"
                                role="button" aria-label="Go to slide 4"></span><span
                                class="swiper-pagination-bullet" tabindex="0"
                                role="button" aria-label="Go to slide 5"></span>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

