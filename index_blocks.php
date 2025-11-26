<main class="index">

  <? $sections = unserialize($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_CHECKBOX_SECTIONS"])["ID"]; ?>

  <section class="index__banner"
           data-order="-1"
  >

    <?
    $rsSection = \Bitrix\Iblock\SectionTable::getList(array(
      'filter' => array(
        'IBLOCK_ID' => $GLOBALS['WEBCOMP']['IBLOCKS']['marketing']['webcomp_market_marketing_banner'],
        'DEPTH_LEVEL' => 1,
        'CODE' => 'slider_on_main',
      ),
      'select' => array('ID', 'CODE', 'NAME'),
    ));
    if ($arSection = $rsSection->fetch()) { ?>
      <? $arrSection = ["IBLOCK_SECTION_ID" => [$arSection['ID']]] ?>
      <? $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "slider_main_page",
        array(
          "CACHE_FILTER" => "N",
          "CACHE_TIME" => "36000000",
          "CACHE_TYPE" => "A",
          "ELEMENTS_COUNT" => "20",
          "FIELD_CODE" => array(
            0 => "ID",
            1 => "IBLOCK_ID",
            2 => "NAME",
            3 => "PREVIEW_PICTURE",
            4 => "PREVIEW_TEXT",
          ),
          "FILTER_NAME" => "arrSection",
          "IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['marketing']['webcomp_market_marketing_banner'],
          "IBLOCK_TYPE" => "marketing",
          "PROPERTY_CODE" => array(
            0 => "UF_PICTURE_DESC",
            1 => "UF_PICTURE_TAB",
            2 => "UF_PICTURE_MOB",
            3 => "UF_LINK_MORE",
            4 => "UF_LINK_CATALOG",
            5 => "UF_ORDER_BTN",
            6 => "UF_THEME",
            7 => "",
          ),
          "SHOW_ONLY_ACTIVE" => "Y",
          "SORT_BY1" => "ACTIVE_FROM",
          "SORT_BY2" => "SORT",
          "SORT_ORDER1" => "DESC",
          "SORT_ORDER2" => "ASC",
          "COMPONENT_TEMPLATE" => "slider_main_page",
          "PAGINATION" => "Y",
          "SHOW_ARROW" => "Y",
          "AUTO_PLAY" => "Y",
          "AUTO_PLAY_SPEED" => "500",
          "AUTO_PLAY_DELAY_SPEED" => "7000",
          "USE_FILTER" => "Y",
          "DONT_INCLUDE_TEMPLATE" => "N"
        ),
        false
      );
      ?>
    <? } ?>
  </section>

  <? if (in_array("advantages", $sections)): ?>
    <section class="ibanner__advantages"
             data-order="<?= array_search("advantages", $sections) ?>"
    >
      <?
      $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "advantages",
        array(
          "AUTO_PLAY" => "Y",
          "AUTO_PLAY_DELAY_SPEED" => "7000",
          "AUTO_PLAY_SPEED" => "500",
          "CACHE_FILTER" => "N",
          "CACHE_TIME" => "36000000",
          "CACHE_TYPE" => "A",
          "COMPONENT_TEMPLATE" => "advantages",
          "ELEMENTS_COUNT" => "5",
          "FIELD_CODE" => array(
            0 => "ID",
            1 => "ACTIVE_FROM",
            2 => "NAME",
            3 => "PREVIEW_PICTURE",
            4 => "PREVIEW_TEXT",
            5 => "CODE",
          ),
          "FILTER_NAME" => "",
          "IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_iadvantage'],
          "IBLOCK_TYPE" => "content",
          "LINK_LINK" => "/",
          "LINK_TITLE" => "Все услуги",
          "PAGINATION" => "Y",
          "PROPERTY_CODE" => array(
            0 => "",
            1 => "ICON",
            2 => "",
          ),
          "SHOW_ONLY_ACTIVE" => "Y",
          "SORT_BY1" => "ACTIVE_FROM",
          "SORT_BY2" => "SORT",
          "SORT_ORDER1" => "DESC",
          "SORT_ORDER2" => "ASC",
          "TITLE" => "Наши услуги",
          "DONT_INCLUDE_TEMPLATE" => "N",
          "USE_FILTER" => "N"
        ),
        false
      ); ?>

    </section>
  <? endif; ?>

  <? if (in_array("services", $sections)): ?>
    <section class="index__services"
             data-order="<?= array_search("services", $sections) ?>"
    >
      <?

      global $arrFilter;
      $arrFilter = ["PROPERTY_SHOW_ON_MAIN_VALUE" => "Y"];

      $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "services_on_main",
        [
          "CACHE_FILTER" => "N",
          "CACHE_TIME" => "36000000",
          "CACHE_TYPE" => "A",
          "ELEMENTS_COUNT" => "6",
          "FIELD_CODE" => [
            0 => "ID",
            1 => "NAME",
            2 => "PREVIEW_PICTURE",
            3 => "DETAIL_PICTURE",
            4 => "CODE",
          ],
          "FILTER_NAME" => "arrFilter",
          "IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_services'],
          "IBLOCK_TYPE" => "content",
          "PROPERTY_CODE" => [
            0 => "",
            1 => "",
          ],
          "SHOW_ONLY_ACTIVE" => "Y",
          "SORT_BY1" => "ID",
          "SORT_BY2" => "SORT",
          "SORT_ORDER1" => "DESC",
          "SORT_ORDER2" => "ASC",
          "COMPONENT_TEMPLATE" => "services_on_main",
          "TITLE" => "Наши услуги",
          "LINK_TITLE" => "Все услуги",
          "LINK_LINK" => "/services/",
          "USE_FILTER" => "Y",
        ],
        false
      ); ?>
    </section>
  <? endif; ?>

  <? if (in_array("projects", $sections)): ?>
    <section class="index__projects"
             data-order="<?= array_search("projects", $sections) ?>"
    >
      <? $APPLICATION->IncludeComponent("webcomp:element.getList", "projects_on_main", array(
        "AUTO_PLAY" => "Y",
        "AUTO_PLAY_DELAY_SPEED" => "7000",
        "AUTO_PLAY_SPEED" => "500",
        "CACHE_FILTER" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => "projects_on_main",
        "ELEMENTS_COUNT" => "20",
        "FIELD_CODE" => array(
          0 => "ID",
          1 => "NAME",
          2 => "PREVIEW_PICTURE",
          3 => "PREVIEW_TEXT",
          4 => "CODE",
        ),
        "FILTER_NAME" => "",
        "IBLOCK_ID" => $GLOBALS["WEBCOMP"]["IBLOCKS"]["content"]["webcomp_market_content_projects"],
        "IBLOCK_TYPE" => "content",
        "LINK_LINK" => "/company/projects/",
        "LINK_TITLE" => "Все проекты",
        "PAGINATION" => "Y",
        "PROPERTY_CODE" => array(
          0 => "",
          1 => "",
        ),
        "SHOW_ONLY_ACTIVE" => "Y",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_BY2" => "SORT",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC",
        "TITLE" => "Сданные проекты"
      ),
        false,
        array(
          "ACTIVE_COMPONENT" => "N"
        )
      ); ?>
    </section>
  <? endif; ?>

  <? if (in_array("popular", $sections)): ?>
    <section class="index__popular"
             data-order="<?= array_search("popular", $sections) ?>"
    >

      <? $APPLICATION->IncludeComponent("webcomp:element.getList", "works_on_main", array(
        "CACHE_FILTER" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => "works_on_main",
        "ELEMENTS_COUNT" => "4",
        "FIELD_CODE" => array(
          0 => "ID",
          1 => "NAME",
          2 => "PREVIEW_PICTURE",
          3 => "CODE",
        ),
        "FILTER_NAME" => "",
        "IBLOCK_ID" => $GLOBALS["WEBCOMP"]["IBLOCKS"]["content"]["webcomp_market_content_works"],
        "IBLOCK_TYPE" => "content",
        "LINK_LINK" => "/works/",
        "LINK_TITLE" => "Все материалы",
        "PAGINATION" => "Y",
        "PROPERTY_CODE" => array(
          0 => "PRICE",
          1 => "OLD_PRICE",
          2 => "",
        ),
        "SHOW_ONLY_ACTIVE" => "Y",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "ACTIVE_FROM",
        "SORT_ORDER1" => "DESC",
        "SORT_ORDER2" => "ASC",
        "TITLE" => "Популярные виды работ",
        "USE_FILTER" => "N"
      ),
        false,
        array(
          "ACTIVE_COMPONENT" => "N"
        )
      ); ?>

    </section>
  <? endif; ?>

  <? if (in_array("reccomended", $sections)): ?>
    <section class="index__reccomended"
             data-order="<?= array_search("reccomended", $sections) ?>"
    >
      <? $APPLICATION->IncludeComponent(
        "webcomp:element.tabs",
        "main_tabs",
        array(
          "CACHE_FILTER" => "N",
          "CACHE_TIME" => "36000000",
          "CACHE_TYPE" => "A",
          "DONT_INCLUDE_TEMPLATE" => "N",
          "ELEMENTS_COUNT" => "8",
          "FIELD_CODE" => array(
            0 => "ID",
            1 => "NAME",
            2 => "CODE",
            3 => "PREVIEW_PICTURE",
          ),
          "FILTER_NAME" => "",
          "IBLOCK_ID" => $GLOBALS["WEBCOMP"]["IBLOCKS"]["catalog"]["catalog_webcomp"],
          "IBLOCK_TYPE" => "catalog",
          "PROPERTY_CODE" => array(
            0 => "OLD_PRICE",
            1 => "PRICE",
            2 => "STICKERS",
            3 => "AVAILABLE",
          ),
          "SHOW_ONLY_ACTIVE" => "Y",
          "SORT_BY1" => "ID",
          "SORT_BY2" => "ID",
          "SORT_ORDER1" => "ASC",
          "SORT_ORDER2" => "ASC",
          "USE_FILTER" => "N",
          "COMPONENT_TEMPLATE" => "main_tabs",
          "PROPERTY_ENUM" => array(
            0 => "1",
            1 => "2",
            2 => "3",
            3 => "4",
          ),
          "PROPERTY_ID" => "3",
          "TITLE" => "Стоит приглядеться",
          "LINK" => "/catalog/"
        ),
        false,
        array(
          "ACTIVE_COMPONENT" => "Y"
        )
      ); ?>
    </section>
  <? endif; ?>

  <? if (in_array("promo", $sections)): ?>
    <section class="index__promo"
             data-order="<?= array_search("promo", $sections) ?>"
    >
      <? $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "banner_on_main",
        [
          "CACHE_FILTER" => "N",
          "CACHE_TIME" => "36000000",
          "CACHE_TYPE" => "A",
          "COMPONENT_TEMPLATE" => "banner_on_main",
          "ELEMENTS_COUNT" => "1",
          "FIELD_CODE" => [
            0 => "ID",
            1 => "NAME",
            2 => "PREVIEW_PICTURE",
            3 => "PREVIEW_TEXT",
            4 => "DETAIL_PICTURE",
            5 => "CODE",
          ],
          "FILTER_NAME" => "",
          "IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['marketing']['webcomp_market_marketing_banner_on_main'],
          "IBLOCK_TYPE" => "marketing",
          "PROPERTY_CODE" => [
            0 => "link",
            1 => "",
          ],
          "SHOW_ONLY_ACTIVE" => "Y",
          "SORT_BY1" => "ACTIVE_FROM",
          "SORT_BY2" => "SORT",
          "SORT_ORDER1" => "DESC",
          "SORT_ORDER2" => "ASC",
        ],
        false
      ); ?>
    </section>
  <? endif; ?>

  <? if (in_array("categories", $sections)): ?>
    <section class="index__cats"
             data-order="<?= array_search("categories", $sections) ?>"
    >

      <?
      global $arrFilter;
      $arrFilter = ["UF_POPULAR" => "1"];
      ?>

      <?
      $APPLICATION->IncludeComponent("webcomp:section.getList", "category_on_main", array(
        "CACHE_FILTER" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "N",
        "COMPONENT_TEMPLATE" => "category_on_main",
        "ELEMENTS_COUNT" => "20",
        "FIELD_CODE" => [
          0 => "ID",
          1 => "PICTURE",
          2 => "NAME",
          3 => "UF_POPULAR",
        ],
        "FILTER_NAME" => "arrFilter",
        "IBLOCK_ID" => $GLOBALS["WEBCOMP"]["IBLOCKS"]["catalog"]["catalog_webcomp"],
        "IBLOCK_TYPE" => "catalog",
        "PROPERTY_CODE" => [
          0 => "",
          1 => "",
        ],
        "SHOW_ONLY_ACTIVE" => "Y",
        "SORT_BY1" => "ID",
        "SORT_BY2" => "ID",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "ASC",
        "TITLE" => "Популярные категории",
        "LINK_TITLE" => "Каталог",
        "LINK_LINK" => "/catalog/",
        "MAX_DEPTH" => "4",
        "USE_FILTER" => "Y"
      )); ?>

    </section>
  <? endif; ?>

  <? if (in_array("actions", $sections)): ?>
    <section class="index__actions"
             data-order="<?= array_search("actions", $sections) ?>"
    >
      <? $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "actions_on_main",
        array(
          "AUTO_PLAY" => "Y",
          "AUTO_PLAY_DELAY_SPEED" => "7000",
          "AUTO_PLAY_SPEED" => "500",
          "CACHE_FILTER" => "N",
          "CACHE_TIME" => "36000000",
          "CACHE_TYPE" => "A",
          "COMPONENT_TEMPLATE" => "actions_on_main",
          "ELEMENTS_COUNT" => "4",
          "FIELD_CODE" => array(
            0 => "ID",
            1 => "ACTIVE_FROM",
            2 => "NAME",
            3 => "PREVIEW_PICTURE",
            4 => "CODE",
          ),
          "FILTER_NAME" => "",
          "IBLOCK_ID" => $GLOBALS["WEBCOMP"]["IBLOCKS"]["content"]["webcomp_market_content_offers"],
          "IBLOCK_TYPE" => "content",
          "LINK_LINK" => "/company/offers/",
          "LINK_TITLE" => "Все акции",
          "PAGINATION" => "Y",
          "PROPERTY_CODE" => array(
            0 => "",
            1 => "",
          ),
          "SHOW_ONLY_ACTIVE" => "Y",
          "SORT_BY1" => "ACTIVE_FROM",
          "SORT_BY2" => "SORT",
          "SORT_ORDER1" => "DESC",
          "SORT_ORDER2" => "ASC",
          "TITLE" => "Акции",
          "DONT_INCLUDE_TEMPLATE" => "N",
          "USE_FILTER" => "N"
        ),
        false
      ); ?>
    </section>
  <? endif; ?>

  <? if (in_array("news", $sections)): ?>
    <section class="index__news"
             data-order="<?= array_search("news", $sections) ?>"
    >
      <? $APPLICATION->IncludeComponent(
	"webcomp:element.getList", 
	"news_on_main", 
	array(
		"CACHE_FILTER" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "news_on_main",
		"ELEMENTS_COUNT" => "4",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "ACTIVE_FROM",
			2 => "NAME",
			3 => "PREVIEW_PICTURE",
			4 => "PREVIEW_TEXT",
			5 => "CODE",
		),
		"FILTER_NAME" => "",
		"IBLOCK_ID" => $GLOBALS["WEBCOMP"]["IBLOCKS"]["content"]["webcomp_market_content_news"],
		"IBLOCK_TYPE" => "content",
		"LINK_LINK" => "/company/news/",
		"LINK_TITLE" => "Все новости",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"SHOW_ONLY_ACTIVE" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"TITLE" => "Новости и обзоры",
		"DONT_INCLUDE_TEMPLATE" => "N",
		"USE_FILTER" => "N"
	),
	false
); ?>
    </section>
  <? endif; ?>

  <? if (in_array("about", $sections)): ?>
    <section class="index__about"
             data-order="<?= array_search("about", $sections) ?>"
    >
      <? $APPLICATION->IncludeComponent(
	"webcomp:element.getList", 
	"about_on_main", 
	array(
		"CACHE_FILTER" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "about_on_main",
		"ELEMENTS_COUNT" => "1",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "PREVIEW_PICTURE",
			3 => "PREVIEW_TEXT",
			4 => "CODE",
		),
		"FILTER_NAME" => "",
		"IBLOCK_ID" => $GLOBALS["WEBCOMP"]["IBLOCKS"]["content"]["webcomp_market_content_about"],
		"IBLOCK_TYPE" => "content",
		"LINK" => "Y",
		"LINK2" => "Y",
		"LINK2_LINK" => "/catalog/",
		"LINK2_TITLE" => "ПЕРЕЙТИ В КАТАЛОГ",
		"LINK_LINK" => "/company/",
		"LINK_TITLE" => "ПОДРОБНЕЕ",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "subtitle",
			2 => "",
		),
		"SHOW_ONLY_ACTIVE" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"DONT_INCLUDE_TEMPLATE" => "N",
		"USE_FILTER" => "N"
	),
	false
); ?>
    </section>
  <? endif; ?>

  <?
  global $arrFilterReviews;
  $arrFilterReviews = ["PROPERTY_SHOW_ON_MAIN_VALUE" => "Y"];
  ?>

  <? if (in_array("reviews", $sections)): ?>
    <section class="index__revs"
             data-order="<?= array_search("reviews", $sections) ?>"
    >
      <? $APPLICATION->IncludeComponent(
        "webcomp:element.getList",
        "revs_on_main",
        array(
          "AUTO_PLAY" => "Y",
          "AUTO_PLAY_DELAY_SPEED" => "7000",
          "AUTO_PLAY_SPEED" => "500",
          "CACHE_FILTER" => "N",
          "CACHE_TIME" => "36000000",
          "CACHE_TYPE" => "A",
          "COMPONENT_TEMPLATE" => "revs_on_main",
          "ELEMENTS_COUNT" => "10",
          "FIELD_CODE" => array(
            0 => "ID",
            1 => "ACTIVE_FROM",
            2 => "NAME",
            3 => "CODE",
          ),
          "FILTER_NAME" => "arrFilterReviews",
          "IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_reviews'],
          "IBLOCK_TYPE" => "content",
          "LINK_LINK" => "/company/reviews/",
          "LINK_TITLE" => "Все отзывы",
          "PAGINATION" => "Y",
          "PROPERTY_CODE" => array(
            0 => "POSITION",
            1 => "PHOTO",
            2 => "MESSAGE",
            3 => "ELEMENT",
            4 => "RATING",
            5 => "",
          ),
          "SHOW_ONLY_ACTIVE" => "Y",
          "SORT_BY1" => "ACTIVE_FROM",
          "SORT_BY2" => "SORT",
          "SORT_ORDER1" => "DESC",
          "SORT_ORDER2" => "ASC",
          "TITLE" => "Отзывы",
          "USE_FILTER" => "Y",
          "DONT_INCLUDE_TEMPLATE" => "N"
        ),
        false
      ); ?>
    </section>
  <? endif; ?>

  <? if (in_array("brands", $sections)): ?>
    <section class="index__brands"
             data-order="<?= array_search("brands", $sections) ?>"
    >
      <? $APPLICATION->IncludeComponent(
	"webcomp:element.getList", 
	"brands_on_main", 
	array(
		"AUTO_PLAY" => "Y",
		"AUTO_PLAY_DELAY_SPEED" => "7000",
		"AUTO_PLAY_SPEED" => "500",
		"CACHE_FILTER" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "brands_on_main",
		"ELEMENTS_COUNT" => "10",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "ACTIVE_FROM",
			2 => "NAME",
			3 => "PREVIEW_PICTURE",
			4 => "PREVIEW_TEXT",
			5 => "CODE",
		),
		"FILTER_NAME" => "",
		"IBLOCK_ID" => $GLOBALS["WEBCOMP"]["IBLOCKS"]["content"]["webcomp_market_content_brands"],
		"IBLOCK_TYPE" => "catalog",
		"LINK_LINK" => "/company/brands/",
		"LINK_TITLE" => "Все бренды",
		"PAGINATION" => "Y",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"SHOW_ONLY_ACTIVE" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"TITLE" => "Патрнёры",
		"DONT_INCLUDE_TEMPLATE" => "N",
		"USE_FILTER" => "N"
	),
	false
); ?>
    </section>
  <? endif; ?>

</main>
