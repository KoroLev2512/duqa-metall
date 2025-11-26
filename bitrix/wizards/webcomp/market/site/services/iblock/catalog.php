<? if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if ( ! CModule::IncludeModule("iblock")) {
    return;
}

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID
    ."/catalog.xml";
$iblockCode = "catalog_webcomp";
$iblockType = "catalog";
$iblockApiCode = "catalogWebcomp";


$rsIBlock = CIBlock::GetList([],
    ["CODE" => $iblockCode, "TYPE" => $iblockType]);
$iblockID = false;
if ($arIBlock = $rsIBlock->Fetch()) {
    $iblockID = $arIBlock["ID"];
    if (WIZARD_INSTALL_DEMO_DATA || true) {
        CIBlock::Delete($arIBlock["ID"]);
        $iblockID = false;
    }
}

if ($iblockID == false) {
    $permissions = [
        "1" => "X",
        "2" => "R",
    ];
    $dbGroup = CGroup::GetList($by = "", $order = "",
        ["STRING_ID" => "content_editor"]);
    if ($arGroup = $dbGroup->Fetch()) {
        $permissions[$arGroup["ID"]] = 'W';
    };
    $iblockID = WizardServices::ImportIBlockFromXML(
        $iblockXMLFile,
        $iblockCode,
        $iblockType,
        WIZARD_SITE_ID,
        $permissions
    );

    if ($iblockID < 1) {
        return;
    }

    //WizardServices::SetIBlockFormSettings($iblockID, Array ( 'tabs' => GetMessage("W_IB_GROUP_PHOTOG_TAB1").$REAL_PICTURE_PROPERTY_ID.GetMessage("W_IB_GROUP_PHOTOG_TAB2").$rating_PROPERTY_ID.GetMessage("W_IB_GROUP_PHOTOG_TAB3").$vote_count_PROPERTY_ID.GetMessage("W_IB_GROUP_PHOTOG_TAB4").$vote_sum_PROPERTY_ID.GetMessage("W_IB_GROUP_PHOTOG_TAB5").$APPROVE_ELEMENT_PROPERTY_ID.GetMessage("W_IB_GROUP_PHOTOG_TAB6").$PUBLIC_ELEMENT_PROPERTY_ID.GetMessage("W_IB_GROUP_PHOTOG_TAB7"), ));

    //IBlock fields
    $iblock = new CIBlock;
    $arFields = [
        "ACTIVE" => "Y",
        "FIELDS" => [
            'IBLOCK_SECTION'    => [
                'IS_REQUIRED'   => 'N',
                'DEFAULT_VALUE' => '',
            ],
            'ACTIVE'            => [
                'IS_REQUIRED'   => 'Y',
                'DEFAULT_VALUE' => 'Y',
            ],
            'ACTIVE_FROM'       => [
                'IS_REQUIRED'   => 'N',
                'DEFAULT_VALUE' => '=today',
            ],
            'ACTIVE_TO'         => [
                'IS_REQUIRED'   => 'N',
                'DEFAULT_VALUE' => '',
            ],
            'SORT'              => [
                'IS_REQUIRED'   => 'N',
                'DEFAULT_VALUE' => '',
            ],
            'NAME'              => [
                'IS_REQUIRED'   => 'Y',
                'DEFAULT_VALUE' => '',
            ],
            "PREVIEW_PICTURE" => [
                "IS_REQUIRED"   => "N",
                "DEFAULT_VALUE" => [
                    "FROM_DETAIL"        => "Y",
                    "SCALE"              => "N",
                    "WIDTH"              => "0",
                    "HEIGHT"             => "0",
                    "IGNORE_ERRORS"      => "N",
                    "METHOD"             => "resample",
                    "COMPRESSION"        => 75,
                    "DELETE_WITH_DETAIL" => "Y",
                    "UPDATE_WITH_DETAIL" => "Y",
                ],
            ],
            'PREVIEW_TEXT_TYPE' => [
                'IS_REQUIRED'   => 'Y',
                'DEFAULT_VALUE' => 'text',
            ],
            'PREVIEW_TEXT'      => [
                'IS_REQUIRED'   => 'N',
                'DEFAULT_VALUE' => '',
            ],
            "DETAIL_PICTURE" => [
                "IS_REQUIRED"   => "N",
                "DEFAULT_VALUE" => [
                    "SCALE"         => "Y",
                    "WIDTH"         => "2000",
                    "HEIGHT"        => "2000",
                    "IGNORE_ERRORS" => "N",
                    "METHOD"        => "resample",
                    "COMPRESSION"   => 75,
                ],
            ],
            'DETAIL_TEXT_TYPE'  => [
                'IS_REQUIRED'   => 'Y',
                'DEFAULT_VALUE' => 'text',
            ],
            'DETAIL_TEXT'       => [
                'IS_REQUIRED'   => 'N',
                'DEFAULT_VALUE' => '',
            ],
            'XML_ID' => [
                'IS_REQUIRED'   => 'N',
                'DEFAULT_VALUE' => '',
            ],
            "CODE" => array(
                "IS_REQUIRED"   => "N",
                "DEFAULT_VALUE" => array(
                    "UNIQUE"          => "Y",
                    "TRANSLITERATION" => "Y",
                    "TRANS_LEN"       => 100,
                    "TRANS_CASE"      => "L",
                    "TRANS_SPACE"     => "_",
                    "TRANS_OTHER"     => "_",
                    "TRANS_EAT"       => "Y",
                    "USE_GOOGLE"      => "N",
                ),
            ),
            'TAGS'              => [
                'IS_REQUIRED'   => 'N',
                'DEFAULT_VALUE' => '',
            ],

            "SECTION_NAME"             => array(
                "IS_REQUIRED"   => "Y",
                "DEFAULT_VALUE" => "",
            ),
            "SECTION_PICTURE"          => array(
                "IS_REQUIRED"   => "N",
                "DEFAULT_VALUE" => array(
                    "FROM_DETAIL"        => "Y",
                    "SCALE"              => "Y",
                    "WIDTH"              => "120",
                    "HEIGHT"             => "120",
                    "IGNORE_ERRORS"      => "N",
                    "METHOD"             => "resample",
                    "COMPRESSION"        => 75,
                    "DELETE_WITH_DETAIL" => "Y",
                    "UPDATE_WITH_DETAIL" => "Y",
                ),
            ),
            "SECTION_DESCRIPTION_TYPE" => array(
                "IS_REQUIRED"   => "Y",
                "DEFAULT_VALUE" => "text",
            ),
            "SECTION_DESCRIPTION"      => array(
                "IS_REQUIRED"   => "N",
                "DEFAULT_VALUE" => "",
            ),
            "SECTION_DETAIL_PICTURE"   => array(
                "IS_REQUIRED"   => "N",
                "DEFAULT_VALUE" => array(
                    "SCALE"         => "Y",
                    "WIDTH"         => "2000",
                    "HEIGHT"        => "2000",
                    "IGNORE_ERRORS" => "N",
                    "METHOD"        => "resample",
                    "COMPRESSION"   => 75,
                ),
            ),
            "SECTION_XML_ID"           => array(
                "IS_REQUIRED"   => "N",
                "DEFAULT_VALUE" => "",
            ),
            "SECTION_CODE"             => array(
                "IS_REQUIRED"   => "Y",
                "DEFAULT_VALUE" => array(
                    "UNIQUE"          => "Y",
                    "TRANSLITERATION" => "Y",
                    "TRANS_LEN"       => 100,
                    "TRANS_CASE"      => "L",
                    "TRANS_SPACE"     => "_",
                    "TRANS_OTHER"     => "_",
                    "TRANS_EAT"       => "Y",
                    "USE_GOOGLE"      => "N",
                ),
            ),
        ],
        "CODE"   => $iblockCode,
        "API_CODE" => $iblockApiCode,
        "XML_ID" => $iblockCode,
        "NAME" => $iblock->GetArrayByID($iblockID,
            "NAME"),
    ];

    $iblock->Update($iblockID, $arFields);
} else {
    $arSites = [];
    $db_res = CIBlock::GetSite($iblockID);
    while ($res = $db_res->Fetch()) {
        $arSites[] = $res["LID"];
    }
    if ( ! in_array(WIZARD_SITE_ID, $arSites)) {
        $arSites[] = WIZARD_SITE_ID;
        $iblock = new CIBlock;
        $iblock->Update($iblockID, ["LID" => $arSites]);
    }
}

/*
$pathToTemplate = WIZARD_SITE_ROOT_PATH.BX_ROOT.'/templates/'.WIZARD_TEMPLATE_ID
    .'_'.WIZARD_THEME_ID;
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/index_blocks.php",
    ["CATALOG_IBLOCK_ID" => $iblockID]);
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/catalog/index.php",
    ["CATALOG_IBLOCK_ID" => $iblockID]);
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/search/index.php",
    ["CATALOG_IBLOCK_ID" => $iblockID]);
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/cart/compare/index.php",
    ["CATALOG_IBLOCK_ID" => $iblockID]);

CWizardUtil::ReplaceMacros($pathToTemplate."/header.php",
    ["CATALOG_IBLOCK_ID" => $iblockID]);
CWizardUtil::ReplaceMacros($pathToTemplate."/footer.php",
    ["CATALOG_IBLOCK_ID" => $iblockID]);*/
?>
