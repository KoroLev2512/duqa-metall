<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (!defined('ERROR_404')) {
    $asset = \Bitrix\Main\Page\Asset::getInstance();
    $domen = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER['HTTP_HOST'];
    $canonical = "";
    $prevPage = ($arResult["NavPageNomer"] > 1) ? $arResult["NavPageNomer"] - 1 : false;
    if ($arResult["NavPageNomer"] > 1) $canonicalPage = $domen . $arResult["sUrlPath"];
    $nextPage = ($arResult["NavPageNomer"] < $arResult["NavPageCount"]) ? $arResult["NavPageNomer"] + 1 : false;


    if ($prevPage) {
        if ($arResult["NavPageNomer"] > 2)
            $prevUrlPath = $domen . $arResult["sUrlPath"] . "?" . "PAGEN_" . $arResult["NavNum"] . "=" . $prevPage;
        else
            $prevUrlPath = $domen . $arResult["sUrlPath"];
    }

    if ($nextPage) $nextUrlPath = $domen . $arResult["sUrlPath"] . "?" . "PAGEN_" . $arResult["NavNum"] . "=" . $nextPage;

    if (isset($prevUrlPath)) {
        $asset->addString( '<link rel="prev" href="' . $prevUrlPath . '">', true);
    }

    if (isset($canonicalPage)) {
        $asset->addString( '<link rel="canonical" href="' . $canonicalPage . '">', true);
    }

    if (isset($nextUrlPath)) {
        $asset->addString( '<link rel="next" href="' . $nextUrlPath . '">', true);
    }

}