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

<div class="catalog__consult">
    <div class="catalog__consult-top">
        <div class="catalog__consult-image">
            <? if ( ! empty($fields['UF_IMG'])): ?>
                <img class="catalog__consult-img"
                     src="<?= CFile::GetPath($fields['UF_IMG']); ?>"
                     alt="lorem"/>
            <? endif; ?>
        </div>
        <? if ( ! empty($fields['UF_TITLE'])): ?>
            <div class="catalog__consult-title">
                <?= $fields['UF_TITLE']; ?>
            </div>
        <? endif; ?>
        <? if ( ! empty($fields['UF_TXT'])): ?>
            <div class="catalog__consult-txt">
                <?= $fields['UF_TXT']; ?>
            </div>
        <? endif; ?>
    </div>
    <button class="catalog__consult-btn jsQuestion"
            data-ajax="#WIZARD_SITE_DIR#ajax.php"
            data-action="question"
            type="button"><?= $fields['UF_BTN']; ?></button>
</div>