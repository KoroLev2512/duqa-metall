<?
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if ( ! defined("WIZARD_SITE_ID")) {
    return;
}

if ( ! defined("WIZARD_SITE_DIR")) {
    return;
}

if (WIZARD_INSTALL_DEMO_DATA || true) {
    $path = str_replace("//", "/",
        WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/");

    $handle = @opendir($path);
    if ($handle) {
        while ($file = readdir($handle)) {
            if (in_array($file, [".", ".."])) {
                continue;
            }
            /*			elseif (
                            is_file($path.$file)
                            &&
                            (
                                ($file == "index.php"  && trim(WIZARD_SITE_PATH, " /") == trim(WIZARD_SITE_ROOT_PATH, " /"))
                                ||
                                ($file == "_index.php" && trim(WIZARD_SITE_PATH, " /") != trim(WIZARD_SITE_ROOT_PATH, " /"))
                            )
                        )
                            continue;
            */
            CopyDirFiles(
                $path.$file,
                WIZARD_SITE_PATH."/".$file,
                $rewrite = true,
                $recursive = true,
                $delete_after_copy = false
            );
        }

        if (WIZARD_SITE_DIR != '/') {
            if (CheckDirPath(WIZARD_SITE_ROOT_PATH.'/include/')
                && CheckDirPath(WIZARD_SITE_ROOT_PATH.'/images/')
                && CheckDirPath(WIZARD_SITE_ROOT_PATH.'/images/favicon/')
            ) {
                $fileList = [
                    'include/policy_form_text.php',
                    'include/policy_page_text.php',
                    'favicon.ico',
                    '404.php',
                    'images/favicon/apple-touch-icon.png',
                    'images/favicon/favicon-16x16.png',
                    'images/favicon/favicon-32x32.png',
                    'images/favicon/favicon-original.png',
                    'bitrix/tools/webcomp_get_element_json.php',
                ];
                foreach ($fileList as $file) {
                    $path_from = $path.$file;
                    $path_to = WIZARD_SITE_ROOT_PATH."/".$file;
                    @copy($path_from, $path_to);
                    if (is_file($path_to)) {
                        @chmod($path_to, BX_FILE_PERMISSIONS);
                        CWizardUtil::ReplaceMacros($path_to,
                            ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
                    }
                }

            }
        }
        CModule::IncludeModule("search");
        CSearch::ReIndexAll(false, 0, [WIZARD_SITE_ID, WIZARD_SITE_DIR]);
    }

    WizardServices::PatchHtaccess(WIZARD_SITE_PATH);

    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH,
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    /*
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."ajax/",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."cart/",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."catalog/",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."company/",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."include/",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."local/",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."personal/",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."search/",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."services/",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."works/",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."_index.php",
        ["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);

    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".bottom_center.menu.php",["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".bottom_left.menu.php",["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".bottom_right.menu.php",["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".top.menu.php",["WIZARD_SITE_DIR" => WIZARD_SITE_DIR]);
*/
    $arUrlRewrite = [];
    if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php")) {
        include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
    }

    $arNewUrlRewrite = [
        [
            "CONDITION" => "#^".WIZARD_SITE_DIR."catalog/#",
            "RULE"      => "",
            "ID"        => "bitrix:catalog",
            "PATH"      => WIZARD_SITE_DIR."catalog/index.php",
        ],
        [
            "CONDITION" => "#^".WIZARD_SITE_DIR."company/projects/#",
            "RULE"      => "",
            "ID"        => "bitrix:news",
            "PATH"      => WIZARD_SITE_DIR."company/projects/index.php",
        ],
        [
            "CONDITION" => "#^".WIZARD_SITE_DIR."company/articles/#",
            "RULE"      => "",
            "ID"        => "bitrix:news",
            "PATH"      => WIZARD_SITE_DIR."company/articles/index.php",
        ],
        [
            "CONDITION" => "#^".WIZARD_SITE_DIR."company/reviews/#",
            "RULE"      => "",
            "ID"        => "bitrix:news",
            "PATH"      => WIZARD_SITE_DIR."company/reviews/index.php",
        ],
        [
            "CONDITION" => "#^".WIZARD_SITE_DIR."company/brands/#",
            "RULE"      => "",
            "ID"        => "bitrix:news",
            "PATH"      => WIZARD_SITE_DIR."company/brands/index.php",
        ],
        [
            "CONDITION" => "#^".WIZARD_SITE_DIR."company/offers/#",
            "RULE"      => "",
            "ID"        => "bitrix:news",
            "PATH"      => WIZARD_SITE_DIR."company/offers/index.php",
        ],
        [
            "CONDITION" => "#^".WIZARD_SITE_DIR."company/news/#",
            "RULE"      => "",
            "ID"        => "bitrix:news",
            "PATH"      => WIZARD_SITE_DIR."company/news/index.php",
        ],
        [
            "CONDITION" => "#^".WIZARD_SITE_DIR."services/#",
            "RULE"      => "",
            "ID"        => "bitrix:catalog",
            "PATH"      => WIZARD_SITE_DIR."services/index.php",
        ],
        [
            "CONDITION" => "#^".WIZARD_SITE_DIR."works/#",
            "RULE"      => "",
            "ID"        => "bitrix:news",
            "PATH"      => WIZARD_SITE_DIR."works/index.php",
        ],

    ];

    foreach ($arNewUrlRewrite as $arUrl) {
        if ( ! in_array($arUrl, $arUrlRewrite)) {
            CUrlRewriter::Add($arUrl);
        }
    }
}

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

CheckDirPath(WIZARD_SITE_PATH."include/");

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/_index.php",
    ["COMPANY_NAME" => htmlspecialcharsbx($wizard->GetVar("companyName"))]);
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/_index.php",
    ["SITE_DESCRIPTION" => htmlspecialcharsbx($wizard->GetVar("siteMetaDescription"))]);
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/_index.php",
    ["SITE_KEYWORDS" => htmlspecialcharsbx($wizard->GetVar("siteMetaKeywords"))]);

?>