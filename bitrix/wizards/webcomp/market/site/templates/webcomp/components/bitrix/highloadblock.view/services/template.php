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
<div class="sbanner">
    <div class="sbanner__left">
        <div class="sbanner__img">
            <img class="sbanner__img-img"
                 src="<?= CFile::GetPath($fields['UF_IMG']); ?>" alt="lorem"/>
        </div>
        <div class="sbanner__txt">
            <?= $fields['UF_TITLE']; ?>
        </div>
    </div>
    <div class="sbanner__right">
        <div class="sbanner__oldprice"><?= $fields['UF_OLDPRICE']; ?></div>
        <div class="sbanner__price"><?= $fields['UF_PRICE']; ?></div>
        <button class="btn sbanner__btn jsCall"
                type="button"><?= $fields['UF_BTN']; ?></button>
    </div>
</div>