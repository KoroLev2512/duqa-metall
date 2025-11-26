<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

use Bitrix\Main\Localization\Loc;
CMarket::Init();
global $USER;
?>
<!DOCTYPE html>
<html xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>">
<head>
    <title><? $APPLICATION->ShowTitle(); ?></title>
    <? $APPLICATION->ShowHead(); ?>

	<script data-skip-moving="true">
	/* === ЖЁСТКИЙ ХОТФИКС ДЛЯ ГОСТЕЙ ===
	   1) Создаём BX.admin с безопасными полями сразу.
	   2) Переопределяем adjustToNodeGetPos так, чтобы он не падал,
		  даже если BX.admin отсутствует.
	   3) Делаем это как можно раньше.
	*/
	(function(){
	  // 1) Сразу создадим BX.admin, если BX уже есть
	  if (window.BX) {
		BX.admin = BX.admin || {};
		if (typeof BX.admin.__border_dx !== 'number') BX.admin.__border_dx = 0;
		if (typeof BX.admin.__border_dy !== 'number') BX.admin.__border_dy = 0;
	  } else {
		// Если BX ещё не загружен, подстрахуемся позже тоже
		document.addEventListener('DOMContentLoaded', function(){
		  if (!window.BX) return;
		  BX.admin = BX.admin || {};
		  if (typeof BX.admin.__border_dx !== 'number') BX.admin.__border_dx = 0;
		  if (typeof BX.admin.__border_dy !== 'number') BX.admin.__border_dy = 0;
		});
	  }
	
	  // 2) Переопределение adjustToNodeGetPos: ждём появления CMenuOpener и патчим
	  var tries = 0, timer = setInterval(function(){
		tries++;
		if (window.BX && BX.CMenuOpener && BX.CMenuOpener.prototype) {
		  try {
			var proto = BX.CMenuOpener.prototype;
			var orig  = proto.adjustToNodeGetPos;
			if (typeof orig === 'function') {
			  proto.adjustToNodeGetPos = function(){
				var pos = BX.pos(this.PARAMS.parent);
				var scrollSize = BX.GetWindowScrollSize();
				var floatWidth = this.DIV.offsetWidth;
	
				var dx = (BX.admin && typeof BX.admin.__border_dx === 'number') ? BX.admin.__border_dx : 0;
				var dy = (BX.admin && typeof BX.admin.__border_dy === 'number') ? BX.admin.__border_dy : 0;
	
				pos.left -= dx;
				pos.top  -= dy;
				pos.top  -= 45;
	
				if (pos.left > scrollSize.scrollWidth - floatWidth) {
				  pos.left = scrollSize.scrollWidth - floatWidth;
				}
				return pos;
			  };
			}
		  } catch (e) {}
		  clearInterval(timer);
		}
		if (tries > 100) clearInterval(timer); // перестраховка: ~5 сек
	  }, 50);
	})();
	</script>

    <? CMarket::showMeta() ?>
    <style><? $APPLICATION->ShowViewContent('custom_css'); ?></style>
    <!-- Yandex.Metrika counter -->
	<script type="text/javascript">
    	(function(m,e,t,r,i,k,a){
        	m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        	m[i].l=1*new Date();
        	for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        	k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    	})(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=104589812', 'ym');

    	ym(104589812, 'init', {
        	ssr:true,
        	webvisor:true,
        	clickmap:true,
        	ecommerce:"dataLayer",
        	accurateTrackBounce:true,
        	trackLinks:true
    	});
	</script>
	<noscript>
    	<div><img src="https://mc.yandex.ru/watch/104589812" style="position:absolute; left:-9999px;" alt="" /></div>
	</noscript>
	<!-- /Yandex.Metrika counter -->
</head>
<body class="<?= CMarket::getBodyClass() ?>">
<? $APPLICATION->ShowPanel() ?>
<div class="wrapper">
    <header class="header <?= $isMainPage ? '' : 'header_second' ?>">
        <div class="header__top">
            <div class="container">
                <div class="header__top-row">
                    <div style="display:flex; justify-items: center;" class="header__top-left">
                        <!--button(type='button').header__city Москва-->
                        <img style="width:60px" alt="Репин груп" src="/upload/repin.png">
                    </div>
                    <div class="header__top-right">
                        <div class="header__controls">
                            <a class="header__control jsSearch" href="#">
                                <?= CMarketView::showIcon('magnifier', 'header__control-svg') ?>
                                <span class="header__control-txt"><?=Loc::getMessage('WEBCOMP_HEADER_SEARCH')?></span>
                            </a>
                            <? if ($WEBCOMP["SETTINGS"]["WEBCOMP_CHECKBOX_LK"] === "Y"): ?>
                                <a class="header__control" href="/personal/" style="display: none;">
                                    <?= CMarketView::showIcon('cabinet', 'header__control-svg') ?>
                                    <span class="header__control-txt">
                                        <?= ($USER->GetLogin() ? $USER->GetLogin() : Loc::getMessage("WEBCOMP_USER_ENTER")) ?>
                                    </span>
                                </a>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header__middle">
            <div class="container">
                <div class="header__middle-row row">
                    <div class="header__middle-left">
                        <div class="header__mmenu">
                            <button class="mmenu-btn jsMenuOpen" type="button">
                                <?= CMarketView::showIcon('menu', 'mmenu-btn__svg') ?>
                            </button>
                        </div>
                        <div class="header__logo">
                            <? CMarketView::showPageBlock('header_logo', 'header') ?>
                            <? CMarketView::showPageBlock('header_slogan', 'header') ?>
                        </div>
                    </div>
                    <div class="header__middle-right">
                        <div class="header__info">
                            <div class="worktime">
                                <div class="worktime__left">
                                    <?= CMarketView::showIcon('time', 'worktime__svg') ?>
                                </div>
                                <div class="worktime__right">
                                    <div class="worktime__title"><?= Loc::getMessage("WEBCOMP_WORKING_TIME") ?></div>
                                    <div class="worktime__txt">
    									<? $APPLICATION->IncludeComponent(
    									    "bitrix:main.include",
    									    "",
    									    array(
    									        "AREA_FILE_SHOW" => "file",
    									        "AREA_FILE_SUFFIX" => "inc",
    									        "EDIT_TEMPLATE" => "",
    									        "PATH" => SITE_TEMPLATE_PATH . "/include/worktime.php"
    									    )
    									); ?>
									</div>
                                </div>
                            </div>
                        </div>
                        <div class="header__links">
    						<? $APPLICATION->IncludeComponent(
    						    "bitrix:main.include",
    						    "",
    						    array(
    						        "AREA_FILE_SHOW" => "file",
    						        "AREA_FILE_SUFFIX" => "inc",
    						        "EDIT_TEMPLATE" => "",
    						        "PATH" => SITE_TEMPLATE_PATH . "/include/email.php"
    						    )
    						); ?>

    						<? $APPLICATION->IncludeComponent(
    						    "bitrix:main.include",
    						    "",
    						    array(
    						        "AREA_FILE_SHOW" => "file",
    						        "AREA_FILE_SUFFIX" => "inc",
    						        "EDIT_TEMPLATE" => "",
    						        "PATH" => SITE_TEMPLATE_PATH . "/include/address.php"
    						    )
    						); ?>
						</div>

                        <div class="header__phones">
							<!-- Кнопка «Заказать звонок» — показываем на ПК -->
    						<button class="header__call d-desktop"
            					data-trigger="click"
            					data-target="CALLORDER"
            					type="button">
        						<?= Loc::getMessage("WEBCOMP_CALLORDER_BTN") ?>
    						</button>
    						<? CMarketView::showPageBlock('header_phones', 'header') ?>

    						<!-- Иконка-звонок — показываем на мобильных -->
    						<a href="tel:+79028320088"
    						   class="phones__icon d-mobile"
    						   aria-label="Позвонить">
    						    <img
    						        src="/upload/icons/phonewhround.svg"
    						        width="36"
    						        height="36"
    						        class="phones__icon-img"
    						        alt="Позвонить">
    						</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header__bottom">
            <div class="container">
                <div class="header__bottom-row">
                    <nav class="<?= $isMainPage ? "" : "menu_white" ?> header__menu menu">

                        <? $APPLICATION->IncludeComponent(
                            "webcomp:menu",
                            "menu_with_catalog",
                            [
                                "TYPE_MENU"                       => "top",
                                "MAX_DEPTH"                       => "3",
                                "USE_CATALOG"                     => "Y",
                                "CATALOG_PATH"                    => "/catalog/",
                                "IBLOCK_ID"                       => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                                "CATALOG_ONLY"                    => "N",
                                "COMPONENT_TEMPLATE"              => "menu_with_catalog",
                                "IBLOCK_TYPE"                     => "catalog",
                                "PARAMS_CATALOG_MAX_DEPTH"        => "3",
                                "PARAMS_CATALOG_SHOW_ONLY_ACTIVE" => "Y",
                                "CACHE_TYPE"                      => "A",
                                "CACHE_TIME"                      => "36000000",
                                "PARAMS_CATALOG_FIELD_CODE"       => [
                                    0 => "ID",
                                    1 => "NAME",
                                    2 => "CODE",
                                    3 => "UF_ICON",
                                    4 => "",
                                ],
                                "START_DIRECTORY"                 => SITE_DIR,
                            ],
                            false
                        ); ?>
                    </nav>

                </div>
            </div>
        </div>
    </header>

    <? $APPLICATION->ShowViewContent('offer_banner') ?>

    <div class="<?= $isMainPage ? '' : 'page_second' ?> page">
        <? if (!$isMainPage): ?>
        <div class="catalog">
            <div class="container">
                <? $APPLICATION->IncludeComponent("bitrix:breadcrumb",
                    "custom",
                    array(
                        "PATH" => "",
                        "SITE_ID" => "s1",
                        "START_FROM" => "0",
                    ),
                    false
                ); ?>

                <h1 class="catalog__title right__title"><? $APPLICATION->ShowTitle(false) ?></h1>
                <div class="row">
                    <!--left menu start -->

                    <? if ($isShowLeftMenu && !$isDetailNews): ?>
                        <aside class="catalog__left left">
                            <? if ($isCatalog): ?>
                                <? $APPLICATION->IncludeComponent(
                                    "webcomp:menu",
                                    "left_menu",
                                    [
                                        "CACHE_TIME"                      => "36000000",
                                        "CACHE_TYPE"                      => "A",
                                        "MAX_DEPTH"                       => "2",
                                        "TYPE_MENU"                       => "top",
                                        "USE_CATALOG"                     => "Y",
                                        "COMPONENT_TEMPLATE"              => "left_menu",
                                        "START_DIRECTORY"                 => "THIS_DIR",
                                        "IBLOCK_TYPE"                     => "catalog",
                                        "IBLOCK_ID"                       => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'],
                                        "CATALOG_PATH"                    => "/catalog/",
                                        "CATALOG_ONLY"                    => "Y",
                                        "PARAMS_CATALOG_FIELD_CODE"       => [
                                            0 => "ID",
                                            1 => "NAME",
                                            2 => "CODE",
                                            3 => "UF_ICON",
                                        ],
                                        "PARAMS_CATALOG_MAX_DEPTH"        => "2",
                                        "PARAMS_CATALOG_SHOW_ONLY_ACTIVE" => "Y",
                                        "MENU_TITLE"                      => Loc::getMessage("WEBCOMP_CATALOG_MENU_TITLE"),
                                    ],
                                    false
                                ); ?>
                            <? else: ?>

                                <? $APPLICATION->IncludeComponent(
                                    "webcomp:menu",
                                    "left_menu",
                                    array(
                                        "CACHE_TIME" => "36000000",
                                        "CACHE_TYPE" => "N",
                                        "MAX_DEPTH" => "\$depthLevelLeftMenu",
                                        "TYPE_MENU" => "top",
                                        "USE_CATALOG" => "N",
                                        "COMPONENT_TEMPLATE" => "left_menu",
                                        "START_DIRECTORY" => "THIS_DIR",
                                        "MENU_TITLE" => "",
                                        "USE_EXT" => "Y",
                                    ),
                                    false
                                ); ?>
                            <? endif; ?>

                            <? $APPLICATION->ShowViewContent('catalog_filter'); ?>

                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => SITE_TEMPLATE_PATH
                                        . "/include/banner_left_menu.php",
                                )
                            ); ?>

                            <? $arrSection = ["IBLOCK_SECTION_ID" => [27]] ?>
                            <? $APPLICATION->IncludeComponent("webcomp:element.getList",
                                "inner_banners",
                                [
                                    "CACHE_FILTER"       => "N",
                                    // Кешировать при установленном фильтре
                                    "CACHE_TIME"         => "36000000",
                                    // Время кеширования (сек.)
                                    "CACHE_TYPE"         => "A",
                                    // Тип кеширования
                                    "ELEMENTS_COUNT"     => "20",
                                    // Максимальное количество элементов
                                    "FIELD_CODE"         => [    // Поля
                                                                 0 => "ID",
                                                                 1 => "IBLOCK_ID",
                                                                 2 => "NAME",
                                                                 3 => "PREVIEW_PICTURE",
                                                                 4 => "PREVIEW_TEXT",
                                    ],
                                    "FILTER_NAME"        => "arrSection",
                                    // Имя переменной фильтра
                                    "IBLOCK_ID"          => $GLOBALS['WEBCOMP']['IBLOCKS']['marketing']['webcomp_market_marketing_banner'],
                                    // Код информационного блока
                                    "IBLOCK_TYPE"        => "marketing",
                                    // Тип информационного блока
                                    "PROPERTY_CODE"      => [    // Свойства
                                                                 0 => "UF_PICTURE_DESC",
                                                                 1 => "UF_PICTURE_TAB",
                                                                 2 => "UF_PICTURE_MOB",
                                                                 3 => "UF_LINK_MORE",
                                                                 4 => "UF_LINK_CATALOG",
                                                                 5 => "UF_ORDER_BTN",
                                    ],
                                    "SHOW_ONLY_ACTIVE"   => "Y",
                                    // Показывать только активные элементы
                                    "SORT_BY1"           => "ACTIVE_FROM",
                                    // Поле для первой сортировки
                                    "SORT_BY2"           => "SORT",
                                    // Поле для второй сортировки
                                    "SORT_ORDER1"        => "DESC",
                                    // Направление для первой сортировки
                                    "SORT_ORDER2"        => "ASC",
                                    // Направление для второй сортировки
                                    "COMPONENT_TEMPLATE" => "slider_main_page",
                                    "USE_FILTER"         => "Y",
                                    // Использовать фильтр
                                ],
                                false
                            );
                            ?>
                        </aside>
                    <? endif; ?>
                    <!--left menu end -->
                    <? if ($isCartPage): ?>
                    <main class="w100">
                        <? elseif (!$isShowLeftMenu): ?>
                        <main class="catalog__right w100">
                            <? elseif ($isDetailNews): ?>
                            <main class="new__left">
                                <? else: ?>
                                <main class="catalog__right right">
                                    <? endif; ?>

                                    <? endif; // isMainPage?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Детект мобайла: на мобиле оставляем поведение tel:, на ПК — перенаправляем в форму
  var isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile/i.test(navigator.userAgent);
  if (isMobile) return;

  // На ПК: любые tel:-ссылки внутри .header__phones ведут в "Заказать звонок"
  var telLinks = document.querySelectorAll('.header__phones a[href^="tel"]');
  telLinks.forEach(function(a) {
    a.addEventListener('click', function(e) {
      e.preventDefault();

      // берем target из ссылки, если есть, иначе дефолт
      var target = a.getAttribute('data-target') || 'CALLORDER';
      // ищем основную кнопку
      var callBtn = document.querySelector('.header__call[data-target="' + target + '"]') 
                 || document.querySelector('.header__call');

      if (callBtn) {
        // гарантируем, что кнопка сверху (на случай перекрытий)
        callBtn.style.position = callBtn.style.position || 'relative';
        callBtn.style.zIndex = callBtn.style.zIndex || '2';
        callBtn.click();
      }
    }, { passive: false });
  });
});
</script>
