<? if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
if ( ! CModule::IncludeModule("iblock")) {
    return;
}
function snakeToCamel($input)
{
    return lcfirst(str_replace(' ', '',
        ucwords(str_replace('_', ' ', $input))));
}

$forms = [
    'callorder',
    'fastorder',
    'feedback',
    'oneclick',
    'order_form',
    'order_project',
    'order_service',
    'question',
    'reviews',
];

foreach ($forms as $form) {
    $iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID
        ."/forms/webcomp_market_$form.xml";
    $iblockType = "forms";
    $iblockCode = "webcomp_market_{$iblockType}_$form";
    $iblockApiCode = snakeToCamel($iblockCode);

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

        //IBlock fields
        $iblock = new CIBlock;
        $arFields = [
            "ACTIVE"   => "Y",
            "FIELDS"   => [
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
                'PREVIEW_PICTURE'   => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => [
                        'FROM_DETAIL'   => 'N',
                        'SCALE'         => 'N',
                        'WIDTH'         => '',
                        'HEIGHT'        => '',
                        'IGNORE_ERRORS' => 'N',
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
                'DETAIL_PICTURE'    => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => [
                        'SCALE'         => 'N',
                        'WIDTH'         => '',
                        'HEIGHT'        => '',
                        'IGNORE_ERRORS' => 'N',
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
                'XML_ID'            => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'CODE'              => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
                'TAGS'              => [
                    'IS_REQUIRED'   => 'N',
                    'DEFAULT_VALUE' => '',
                ],
            ],
            "CODE"     => $iblockCode,
            "API_CODE" => $iblockApiCode,
            "XML_ID"   => $iblockCode,
            "NAME"     => $iblock->GetArrayByID($iblockID,
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
}

?>
