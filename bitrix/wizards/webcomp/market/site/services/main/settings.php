<?
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

COption::SetOptionString("fileman", "propstypes", serialize([
    "description"    => GetMessage("MAIN_OPT_DESCRIPTION"),
    "keywords"       => GetMessage("MAIN_OPT_KEYWORDS"),
    "title"          => GetMessage("MAIN_OPT_TITLE"),
    "keywords_inner" => GetMessage("MAIN_OPT_KEYWORDS_INNER"),
]), false, $siteID);
COption::SetOptionInt("search", "suggest_save_days", 250);
COption::SetOptionString("search", "use_tf_cache", "Y");
COption::SetOptionString("search", "use_word_distance", "Y");
COption::SetOptionString("search", "use_social_rating", "Y");
COption::SetOptionString("iblock", "use_htmledit", "Y");

$arrDefaults = [
    "WEBCOMP_SELECT_SITE_THEME_COLOR" => "1",
    "WEBCOMP_CHECKBOX_E-SHOP" => "Y",
    "WEBCOMP_CHECKBOX_LK"=>	"Y",
    "WEBCOMP_CHECKBOX_USE_POLICY"=>	"Y",
    "WEBCOMP_CHECKBOX_DEFAULT_CHECK" => "Y",
    "WEBCOMP_EDITOR_FORM_POLICY_TEXT"=>	"policy_form_text.php",
    "WEBCOMP_EDITOR_PAGE_POLICY_TEXT"          =>	"policy_page_text.php",
    "WEBCOMP_STRING_PHONE_MASK"                =>	"+7 (999) 999-99-99",
    "WEBCOMP_CHECKBOX_PAGE_SPEED_OPTIMIZATION" =>"Y",
    "WEBCOMP_STRING_DECIMAL"                   =>	"0",
    "WEBCOMP_STRING_DECIMAL_POINT"             =>	".",
    "WEBCOMP_STRING_DECIMAL_SEPORATOR"         =>	",",
    "WEBCOMP_STRING_THOUSANDTH_SEPORATOR"      => " ",
    "WEBCOMP_SEO_YANDEX_CHECKBOX"              =>	"N",
    "WEBCOMP_SEO_YANDEX_CODE"                  =>	"yandex_metrica.php",
    "WEBCOMP_RECAPTCHA_CHECKBOX"               =>	"N",
    "WEBCOMP_RECAPTCHA_SCORE"                  => "0.5",
    "WEBCOMP_CHECKBOX_SERVICES_WITH_CATEGORY"  => "N",
    "WEBCOMP_VIEW_FOOTER"  => "v1",
];

foreach ($arrDefaults as $k => $val) {
    COption::SetOptionString("webcomp.market", "$k", $val);
}

COption::SetOptionString("webcomp.market", "WEBCOMP_CHECKBOX_SECTIONS",
    'a:2:{s:2:"ID";a:12:{i:0;s:10:"advantages";i:1;s:8:"services";i:2;s:8:"projects";i:3;s:7:"popular";i:4;s:11:"reccomended";i:5;s:5:"promo";i:6;s:10:"categories";i:7;s:7:"actions";i:8;s:4:"news";i:9;s:5:"about";i:10;s:7:"reviews";i:11;s:6:"brands";}s:5:"ORDER";a:12:{i:0;s:10:"advantages";i:1;s:8:"services";i:2;s:8:"projects";i:3;s:7:"popular";i:4;s:11:"reccomended";i:5;s:5:"promo";i:6;s:10:"categories";i:7;s:7:"actions";i:8;s:4:"news";i:9;s:5:"about";i:10;s:7:"reviews";i:11;s:6:"brands";}}');

//images
$faviconArFile = CFile::MakeFileArray(WIZARD_SITE_DIR
    ."/images/favicon/favicon-original.png");
$logoDarkArFile = CFile::MakeFileArray(WIZARD_SITE_DIR."/images/logo_dark.png");
$logoLightArFile = CFile::MakeFileArray(WIZARD_SITE_DIR
    ."/images/logo_light.png");

if ($fileID = CFile::SaveFile($faviconArFile, "webcomp.market")) {
    COption::SetOptionString("webcomp.market", "WEBCOMP_FILE_SITE_FAVICON",
        serialize([$fileID]));
}
if ($fileID = CFile::SaveFile($logoDarkArFile, "webcomp.market")) {
    COption::SetOptionString("webcomp.market", "WEBCOMP_FILE_SITE_LOGO_DARK",
        serialize([$fileID]));
}
if ($fileID = CFile::SaveFile($logoLightArFile, "webcomp.market")) {
    COption::SetOptionString("webcomp.market", "WEBCOMP_FILE_SITE_LOGO_LIGHT",
        serialize([$fileID]));
}


CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT
    ."/modules/webcomp.market/classes/general/CMarketCatalog.php",
    ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);


?>


