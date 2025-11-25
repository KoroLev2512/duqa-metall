<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
session_start();
header('Content-Type: application/json');
CModule::IncludeModule("iblock");
\Webcomp\Market\Constants::getAllIblocks();

use Bitrix\Main\Loader;
use Webcomp\Market\Tools as Tools;

if (!Loader::includeSharewareModule("webcomp.market")) {
    die('required module webcomp.market');
}

if(!function_exists('mb_ucfirst')) {
    function mb_ucfirst($string, $enc = 'UTF-8') {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) .
            mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }
}

$data = $_POST;
$action = $data['action'];

const MAX_ELEMENT_COUNT = 3;

switch ($action) {
    case 'search':
        echo json_encode(getSearchResult());
        exit();
    default:
        echo json_encode([
            'status' => false,
        ]);
        exit();
}

function getSearchResult() {
    $query = $_POST["q"];
    $status = $issetResult = false;

    if (empty($query)) {
        return [
            "html" => "",
            "status" => $status
        ];
    }

    $arResult = [
        "catalogElement" => ["NAME" => getMessage("SEARCH_PRODUCTS_TITLE"), "ITEMS" => []],
        "catalogSection" => ["NAME" => getMessage("SEARCH_SECTIONS_TITLE"), "ITEMS" => []],
        "servicesElement" => ["NAME" => getMessage("SEARCH_SERVICES_TITLE"), "ITEMS" => []],
    ];

    // get only catalog element, catalog section, service element
    $arSelect = ["ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE", "DETAIL_PAGE_URL", "PREVIEW_TEXT", "DETAIL_TEXT", "PROPERTY_*"];
    $arFilter = ["IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "NAME" => '%' . $query . "%"];
    $res = CIBlockElement::GetList([], $arFilter, false, ["nPageSize" => MAX_ELEMENT_COUNT], $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();

        $arResult["catalogElement"]["ITEMS"][] = [
            "ID" => $arFields["ID"],
            "NAME" => $arFields["NAME"],
            "PICTURE" => ((!empty($arFields["PREVIEW_PICTURE"])) ? $arFields["PREVIEW_PICTURE"]
                : (!empty($arFields["DETAIL_PICTURE"]))) ? $arFields["DETAIL_PICTURE"] : false,
            "URL" => $arFields["DETAIL_PAGE_URL"],
            "DESCRIPTION" => (!empty($arFields["PREVIEW_TEXT"])) ? $arFields["PREVIEW_TEXT"] : $arFields["DETAIL_TEXT"],
            "PRICE" => CMarketCatalog::getPrice($arProps["PRICE"]["VALUE"]),
        ];

        $issetResult = true;
    }

    $arSelect = ["ID", "IBLOCK_ID", "NAME", "PICTURE", "SECTION_PAGE_URL", "DESCRIPTION"];
    $arFilter = ["IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['catalog']['catalog_webcomp'], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "NAME" => '%' . $query . "%"];
    $res = CIBlockSection::GetList([], $arFilter, false, $arSelect, ["nPageSize" => MAX_ELEMENT_COUNT]);
    while ($ob = $res->GetNext()) {

        $arResult["catalogSection"]["ITEMS"][] = [
            "ID" => $ob["ID"],
            "NAME" => $ob["NAME"],
            "PICTURE" => $ob["PICTURE"],
            "URL" => $ob["SECTION_PAGE_URL"],
            "DESCRIPTION" => $ob["DESCRIPTION"]
        ];

        $issetResult = true;
    }

    $arSelect = ["ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE", "DETAIL_PAGE_URL", "PREVIEW_TEXT", "DETAIL_TEXT"];
    $arFilter = ["IBLOCK_ID" => $GLOBALS['WEBCOMP']['IBLOCKS']['content']['webcomp_market_content_services'], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "NAME" => '%' . $query . "%"];
    $res = CIBlockElement::GetList([], $arFilter, false, ["nPageSize" => MAX_ELEMENT_COUNT], $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();

        $arResult["servicesElement"]["ITEMS"][] = [
            "ID" => $arFields["ID"],
            "NAME" => $arFields["NAME"],
            "PICTURE" => ((!empty($arFields["PREVIEW_PICTURE"])) ? $arFields["PREVIEW_PICTURE"]
                : (!empty($arFields["DETAIL_PICTURE"]))) ? $arFields["DETAIL_PICTURE"] : false,
            "DESCRIPTION" => (!empty($arFields["PREVIEW_TEXT"])) ? $arFields["PREVIEW_TEXT"] : $arFields["DETAIL_TEXT"],
            "URL" => $arFields["DETAIL_PAGE_URL"],
        ];

        $issetResult = true;
    }

    ob_start();
    ?>

    <? if ($issetResult): ?>
        <table class="hsearch__table">
            <tbody>
            <? foreach ($arResult as $section): ?>

                <? if (empty($section["ITEMS"])) continue; ?>

                <tr>
                    <td class="hsearch__left">
                        <div class="hsearch__title"><?= $section["NAME"] ?></div>
                    </td>
                    <td class="hsearch__right">
                        <div class="hsearch__list">
                            <? foreach ($section["ITEMS"] as $item): ?>
                                <?
                                $lowerText = mb_strtolower($item["NAME"]);
                                $lowerQuery = mb_strtolower($query);
                                $photo = !empty($item['PICTURE']) ? CFile::ResizeImageGet(
                                    $item['PICTURE'],
                                    array("width" => 70, "height" => 70),
                                    BX_RESIZE_IMAGE_PROPORTIONAL
                                ) : '/images/no-photo.jpg';

                                ?>

                                <? $name = str_replace($lowerQuery, '<b>' . $lowerQuery . '</b>', mb_ucfirst($lowerText)) ?>
                                <div class="hsearch__item">
                                    <div class="hsearch__image">
                                        <img src="<?= $photo["src"] ?>" alt="<?= $name ?>">
                                    </div>

                                    <div class="hsearch__info">
                                        <a class="hsearch__link" href="<?= $item["URL"] ?>"><?= $name ?></a>

                                        <? if (!empty($item['DESCRIPTION'])): ?>
                                            <span class="hsearch__description"><?= Tools::cutString($item['DESCRIPTION'], 100) ?></span>
                                        <? endif ?>

                                        <? if (isset($item['PRICE']) && !empty($item['PRICE'])): ?>
                                            <span class="hsearch__price"><?= $item['PRICE'] ?></span>
                                        <? endif ?>
                                    </div>
                                </div>
                            <? endforeach ?>
                        </div>
                    </td>
                </tr>
            <? endforeach ?>

            </tbody>
        </table>


        <? $status = true; ?>
    <? endif ?>


    <?
    $html = ob_get_contents();
    ob_end_clean();
    return [
        "html" => $html,
        "status" => $status
    ];
}

?>
