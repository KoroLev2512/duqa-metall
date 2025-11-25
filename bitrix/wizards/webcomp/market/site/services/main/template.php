<?
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

//echo "WIZARD_SITE_ID=".WIZARD_SITE_ID." | ";
//echo "WIZARD_SITE_PATH=".WIZARD_SITE_PATH." | ";
//echo "WIZARD_RELATIVE_PATH=".WIZARD_RELATIVE_PATH." | ";
//echo "WIZARD_ABSOLUTE_PATH=".WIZARD_ABSOLUTE_PATH." | ";
//echo "WIZARD_TEMPLATE_ID=".WIZARD_TEMPLATE_ID." | ";
//echo "WIZARD_TEMPLATE_RELATIVE_PATH=".WIZARD_TEMPLATE_RELATIVE_PATH." | ";
//echo "WIZARD_TEMPLATE_ABSOLUTE_PATH=".WIZARD_TEMPLATE_ABSOLUTE_PATH." | ";
//echo "WIZARD_THEME_ID=".WIZARD_THEME_ID." | ";
//echo "WIZARD_THEME_RELATIVE_PATH=".WIZARD_THEME_RELATIVE_PATH." | ";
//echo "WIZARD_THEME_ABSOLUTE_PATH=".WIZARD_THEME_ABSOLUTE_PATH." | ";
//echo "WIZARD_SERVICE_RELATIVE_PATH=".WIZARD_SERVICE_RELATIVE_PATH." | ";
//echo "WIZARD_SERVICE_ABSOLUTE_PATH=".WIZARD_SERVICE_ABSOLUTE_PATH." | ";
//echo "WIZARD_IS_RERUN=".WIZARD_IS_RERUN." | ";
//die();

if ( ! defined("WIZARD_TEMPLATE_ID")) {
    return;
}

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/"
    .WIZARD_TEMPLATE_ID."_".WIZARD_THEME_ID.'/';

CopyDirFiles(
    $_SERVER["DOCUMENT_ROOT"]
    .WizardServices::GetTemplatesPath(WIZARD_RELATIVE_PATH."/site")."/"
    .WIZARD_TEMPLATE_ID,
    $bitrixTemplateDir,
    $rewrite = true,
    $recursive = true,
    $delete_after_copy = false,
    $exclude = "themes"
);

//Attach template to default site
$obSite = CSite::GetList($by = "def", $order = "desc",
    ["LID" => WIZARD_SITE_ID]);
if ($arSite = $obSite->Fetch()) {
    $arTemplates = [];
    $found = false;
    $foundEmpty = false;
    $obTemplate = CSite::GetTemplateList($arSite["LID"]);
    while ($arTemplate = $obTemplate->Fetch()) {
        if ( ! $found && strlen(trim($arTemplate["CONDITION"])) <= 0) {
            $arTemplate["TEMPLATE"] = WIZARD_TEMPLATE_ID."_".WIZARD_THEME_ID;
            $found = true;
        }
        if ($arTemplate["TEMPLATE"] == "empty") {
            $foundEmpty = true;
            continue;
        }
        $arTemplates[] = $arTemplate;
    }

    if ( ! $found) {
        $arTemplates[] = [
            "CONDITION" => "",
            "SORT"      => 150,
            "TEMPLATE"  => WIZARD_TEMPLATE_ID."_".WIZARD_THEME_ID,
        ];
    }

    $arFields = [
        "TEMPLATE" => $arTemplates,
        "NAME"     => $arSite["NAME"],
    ];

    $obSite = new CSite();
    $obSite->Update($arSite["LID"], $arFields);
}
COption::SetOptionString("main", "wizard_template_id", WIZARD_TEMPLATE_ID,
    false, WIZARD_SITE_ID);

function ___writeToAreasFile($fn, $text)
{
    if (file_exists($fn) && ! is_writable($abs_path)
        && defined("BX_FILE_PERMISSIONS")
    ) {
        @chmod($abs_path, BX_FILE_PERMISSIONS);
    }

    $fd = @fopen($fn, "wb");
    if ( ! $fd) {
        return false;
    }

    if (false === fwrite($fd, $text)) {
        fclose($fd);

        return false;
    }

    fclose($fd);

    if (defined("BX_FILE_PERMISSIONS")) {
        @chmod($fn, BX_FILE_PERMISSIONS);
    }
}

COption::SetOptionString("webcomp.market", "WEBCOMP_STRING_SLOGAN",
    nl2br($wizard->GetVar("companySlogan")));

___writeToAreasFile($bitrixTemplateDir."include/worktime.php",
    nl2br($wizard->GetVar("companyTimeWork")));

CWizardUtil::ReplaceMacros($bitrixTemplateDir."include/email.php",
    ["EMAIL" => $wizard->GetVar("companyEmail")]);
CWizardUtil::ReplaceMacros($bitrixTemplateDir."include/footer/email.php",
    ["EMAIL" => $wizard->GetVar("companyEmail")]);
CWizardUtil::ReplaceMacros($bitrixTemplateDir."include/address.php",
    ["ADDRESS" => $wizard->GetVar("companyAddress")]);
CWizardUtil::ReplaceMacros($bitrixTemplateDir."include/footer/addr.php",
    ["ADDRESS" => $wizard->GetVar("companyAddress")]);
CWizardUtil::ReplaceMacros($bitrixTemplateDir."include/footer/copy.php",
    ["COMPANY_COPY" => $wizard->GetVar("companyCopy")]);

CWizardUtil::ReplaceMacros($bitrixTemplateDir."js/custom.js",
    ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);

WizardServices::ReplaceMacrosRecursive($bitrixTemplateDir,
    ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
?>
