<?
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if ( ! CModule::IncludeModule("iblock")) {
    return;
}

$arTypes = [
    [
        "ID"       => "catalog",
        "SECTIONS" => "Y",
        "IN_RSS"   => "N",
        "SORT"     => 500,
        "LANG"     => [],
    ],
    [
        "ID"       => "forms",
        "SECTIONS" => "N",
        "IN_RSS"   => "N",
        "SORT"     => 500,
        "LANG"     => [],
    ],
    [
        "ID"       => "content",
        "SECTIONS" => "Y",
        "IN_RSS"   => "N",
        "SORT"     => 500,
        "LANG"     => [],
    ],
    [
        "ID"       => "marketing",
        "SECTIONS" => "Y",
        "IN_RSS"   => "N",
        "SORT"     => 500,
        "LANG"     => [],
    ],
];

$arLanguages = [];
$rsLanguage = CLanguage::GetList($by, $order, []);
while ($arLanguage = $rsLanguage->Fetch()) {
    $arLanguages[] = $arLanguage["LID"];
}

$iblockType = new CIBlockType;
foreach ($arTypes as $arType) {
    $dbType = CIBlockType::GetList([], ["=ID" => $arType["ID"]]);
    if ($dbType->Fetch()) {
        continue;
    }

    foreach ($arLanguages as $languageID) {
        WizardServices::IncludeServiceLang("type.php", $languageID);

        $code = strtoupper($arType["ID"]);
        $arType["LANG"][$languageID]["NAME"] = GetMessage($code
            ."_TYPE_NAME");
        $arType["LANG"][$languageID]["ELEMENT_NAME"] = GetMessage($code
            ."_ELEMENT_NAME");

        if ($arType["SECTIONS"] == "Y") {
            $arType["LANG"][$languageID]["SECTION_NAME"] = GetMessage($code
                ."_SECTION_NAME");
        }
    }

    $iblockType->Add($arType);
}
?>