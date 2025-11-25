<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag;

foreach ($arResult as $key => $tab) {
    foreach ($tab["ITEMS"] as $item) {
        print_r($tab["NAME"] ."=>". $item["NAME"]."<br>");
    }
}



