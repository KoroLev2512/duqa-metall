<? if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @param CBitrixComponent         $component
 * @param CBitrixComponentTemplate $this
 *
 * @var string                     $strErrorMessage
 * @var array                      $arParams
 * @var array                      $arResult
 * @global CMain                   $APPLICATION
 */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if ($arParams['AJAX_POST'] == 'Y'
    && ($_REQUEST["save_product_review"] == "Y")
) {
    $response = ob_get_clean();
    $JSResult = [];
    $FHParser = new CForumSimpleHTMLParser($response);

    $statusMessage = $FHParser->getTagHTML('div[class=reviews-note-box]');
    $JSResult['statusMessage'] = $statusMessage;

    if ((empty($_REQUEST["preview_comment"])
        || $_REQUEST["preview_comment"] == "N")
    ) // message added
    {
        $result = intval($arResult['RESULT']);

        if (
            (
                (isset($_REQUEST['pageNumber'])
                    && intval($_REQUEST['pageNumber'])
                    != $arResult['PAGE_NUMBER'])
                || (isset($_REQUEST['pageCount'])
                    && intval($_REQUEST['pageCount'])
                    != $arResult['PAGE_COUNT'])
            )
            && $result > 0
        ) {
            $messagePost
                = $FHParser->getTagHTML('div[class=reviews-block-inner]');
            $messageNavigation
                = $FHParser->getTagHTML('div[class=reviews-navigation-box]');

            $JSResult += [
                'status'      => true,
                'allMessages' => true,
                'message'     => $messagePost,
                'messageID'   => $result,
                'messagesID'  => array_keys($arResult["MESSAGES"]),
                'navigation'  => $messageNavigation,
                'pageNumber'  => $arResult['PAGE_NUMBER'],
                'pageCount'   => $arResult['PAGE_COUNT'],
            ];

            if (strlen($messagePost) < 1
                && ! ($arResult["USER"]["RIGHTS"]["MODERATE"] != "Y"
                    && $arResult["FORUM"]["MODERATION"] == "Y")
            ) {
                $JSResult += ['reload' => true];
            }
        } else {
            $JSResult['allMessages'] = false;
            if ($result == false) {
                $JSResult += [
                    'status' => false,
                    'error'  => $arError[0]['title'],
                ];
            } else {
                $messagePost = $FHParser->getTagHTML('table[id=message'.$result
                    .']');
                $JSResult += [
                    'status'    => true,
                    'messageID' => $result,
                    'message'   => $messagePost,
                ];
                if (strlen($messagePost) < 1
                    && ! ($result > 0
                        && $arResult["USER"]["RIGHTS"]["MODERATE"] != "Y"
                        && $arResult["FORUM"]["MODERATION"] == "Y")
                ) {
                    $JSResult += ['reload' => true];
                }

                if (strpos($JSResult['message'], "onForumImageLoad")
                    !== false
                ) {
                    $SHParser
                        = new CForumSimpleHTMLParser($APPLICATION->GetHeadStrings());
                    $scripts = $SHParser->getInnerHTML('<!--LOAD_SCRIPT-->',
                        '<!--END_LOAD_SCRIPT-->');

                    if ($scripts !== "") {
                        $JSResult['message'] = $scripts."\n"
                            .$JSResult['message'];
                    }
                }
            }
        }
    } else // preview
    {
        if (empty($arError)) {
            $messagePreview
                = $FHParser->getTagHTML('div[class=reviews-preview]');
            $JSResult += [
                'status'         => true,
                'previewMessage' => $messagePreview,
            ];
            if (strpos($JSResult['previewMessage'], "onForumImageLoad")
                !== false
            ) {
                $SHParser
                    = new CForumSimpleHTMLParser($APPLICATION->GetHeadStrings());
                $scripts = $SHParser->getInnerHTML('<!--LOAD_SCRIPT-->',
                    '<!--END_LOAD_SCRIPT-->');

                if ($scripts !== "") {
                    $JSResult['previewMessage'] = $scripts."\n"
                        .$JSResult['previewMessage'];
                }
            }
        } else {
            $JSResult += [
                'status' => false,
                'error'  => $arError[0]['title'],
            ];
        }
    }

    $APPLICATION->RestartBuffer();
    while (ob_end_clean()) {
        ;
    }

    if ($request->getPost("dataType") == "json") {
        header('Content-Type:application/json; charset=UTF-8');
        echo \Bitrix\Main\Web\Json::encode($JSResult);

    } else {
        echo "<script>top.SetReviewsAjaxPostTmp("
            .CUtil::PhpToJSObject($JSResult).");</script>";
    }

    \CMain::FinalActions();
    die();
}
?>
