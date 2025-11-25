<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if ( ! empty($arResult['ERROR'])) {
    ShowError($arResult['ERROR']);

    return false;
}

global $USER_FIELD_MANAGER;

//$GLOBALS['APPLICATION']->SetTitle('Highloadblock Row');

$listUrl = str_replace('#BLOCK_ID#', intval($arParams['BLOCK_ID']),
    $arParams['LIST_URL']);
$fields = $arResult['row'];

?>

<a class="catalog__banner" href="<?= $fields['UF_LINK']; ?>">
    <span class="catalog__banner-image">
        <img class="catalog__banner-img"
             src="<?= CFile::GetPath($fields['UF_IMG']); ?>" alt="lorem"/>
    </span>
    <span class="catalog__banner-title"><?= $fields['UF_TITLE']; ?></span>
</a>