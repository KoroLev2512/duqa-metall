<?
global $APPLICATION,
    $isMainPage, $is404, $isCatalog, $isCartPage, $isFavoritePage, $isComparePage, $isSearchPage, $isMainNews, $isDetailNews,
    $isShowLeftMenu, $typeLeftMenu, $depthLevelLeftMenu, $cartCount;

$is404 = (defined("ERROR_404") && ERROR_404 === "Y");
$isMainPage = ($APPLICATION->GetCurPage(false) == SITE_DIR);
$isCatalog = (strpos($APPLICATION->GetCurPage(false), '/catalog/') !== false);
$isCartPage = (strpos($APPLICATION->GetCurPage(true), '/cart/index.php') !== false);
$isFavoritePage = (strpos($APPLICATION->GetCurPage(true), '/cart/favorite/index.php') !== false);
$isComparePage = (strpos($APPLICATION->GetCurPage(true), '/cart/compare/index.php') !== false);
$isSearchPage = (strpos($APPLICATION->GetCurPage(true), '/search/') !== false);

$isMainNews = (strpos($APPLICATION->GetCurPage(true), '/company/news/index.php')
    !== false);
$isDetailNews = (strpos($APPLICATION->GetCurPage(true), '/company/news/')
    !== false
    && ! $isMainNews);
$isEshop = ($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_CHECKBOX_E-SHOP"]==="Y")? true: false;

$isShowLeftMenu = $APPLICATION->GetProperty("SHOW_LEFT_MENU") == "Y";
$typeLeftMenu = $APPLICATION->GetProperty("TYPE_LEFT_MENU") ?: "top";
$depthLevelLeftMenu = $APPLICATION->GetProperty("DEPTH_LEFT_MENU") ?: "1";

$cartCount = 0;

if(isset($_SESSION["CART"])) {
    foreach ($_SESSION["CART"] as $item) {
        $cartCount += $item;
    }
}

