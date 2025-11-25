<?
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arTypes = [
    "WEBCOMP_ASK_QUESTION",
    "WEBCOMP_CALLORDER",
    "WEBCOMP_FEEDBACK",
    "WEBCOMP_NEW_ORDER",
    "WEBCOMP_ONE_CLICK_BUY",
    "WEBCOMP_ORDER_PROJECT",
    "WEBCOMP_ORDER_SERVICE",
    "WEBCOMP_REVIEWS",
];
$obEventType = new CEventType;
foreach ($arTypes as $type) {
    $obEventType->Add([
        "EVENT_NAME"  => $type,
        "NAME"        => GetMessage($type),
        "LID"         => "ru",
        "DESCRIPTION" => ""
    ]);
}
?>