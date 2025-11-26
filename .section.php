<?php
// Уникальные заголовки для пагинации
if (isset($_GET['PAGEN_1']) && intval($_GET['PAGEN_1']) > 1) {
    $pageNum = intval($_GET['PAGEN_1']);
    $title = $APPLICATION->GetPageProperty('title');
    $APPLICATION->SetPageProperty('title', $title . ' — страница ' . $pageNum);
    $APPLICATION->SetTitle($title . ' — страница ' . $pageNum);
}
?>