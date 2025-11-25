<?php

namespace Webcomp\Market;

use Bitrix\Main\Config\Option,
    Bitrix\Main\Localization\Loc,
    CFileInput,
    CFile,
    CMain;

Loc::loadMessages(__FILE__);

class Settings {

    public static $arParametrs;
    public static $module_id = "webcomp.market";
    public static $moduleClass = "WCMarket";
    public static $fieldPath = "/bitrix/modules/webcomp.market/include/fields/";

    public static function SortingOptions($a, $b)
    {
        if ($a["SORT"] == $b["SORT"]) {
            return 0;
        }
        return ($a["SORT"] < $b["SORT"]) ? -1 : 1;
    }

    public static function DrawAdminTabs($arTabs) {
        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."tabs.php",
            ["arTabs" => $arTabs],
            ["MODE" => "php"]
        );
    }

    public static function DrawModalDescription() {
        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."modal.php",
            [],
            ["MODE" => "php"]
        );
    }

    public static function DrawAdminOptions($arTabs) {
        self::DrawAdminTabs($arTabs);
        self::DrawModalDescription();

        echo '<div class="webcomp-settings__content-wrap">';

        $active = true;
        foreach ($arTabs as $id => $tab) {

            echo '<div class="webcomp-settings__content '.(($active) ? "active" : '') .'" data-content="'.$id.'">';
                echo '<div class="webcomp-settings__content-heading">'.$tab["TITLE"].'</div>';

            self::$arParametrs = $tab["OPTIONS"];

            // Сортируем массив по полю SORT
            usort(self::$arParametrs, "self::SortingOptions");

            if (!empty(self::$arParametrs)) {
                foreach (self::$arParametrs as $key => $arOption) {

                    // Если не массив то, продолжаем
                    if (!is_array($arOption))
                        continue;

                    switch ($arOption["TYPE"]) {
                        // Заголовок блоков
                        case 'HEADING':
                            self::DrawHeadingField($arOption);
                            break;
                        // Информация
                        case 'INFO':
                            self::DrawInfoField($arOption);
                            break;
                        // Строка
                        case 'STRING':
                            self::DrawStringField($arOption);
                            break;
                        // Строка множественная
                        case 'MULTIPLE_STRING':
                            self::DrawMultipleStringField($arOption);
                            break;
                        // Чекбокс
                        case 'CHECKBOX':
                            self::DrawCheckBoxField($arOption);
                            break;
                        // Секция
                        case 'SECTIONS':
                            self::DrawSections($arOption);
                            break;
                        // Селект
                        case 'SELECT':
                            self::DrawSelectField($arOption);
                            break;
                        // Селект theme
                        case 'SELECT_THEME':
                            self::DrawSelectThemeField($arOption);
                            break;
                        // Селект множественный
                        case 'MULTIPLE_SELECT':
                            self::DrawMultipleSelectField($arOption);
                            break;
                        // Редактор
                        case 'EDITOR':
                            self::DrawEditorField($arOption);
                            break;
                        // Файл
                        case 'FILE':
                            self::DrawFileField($arOption);
                            break;
                        case 'VIEW':
                            self::DrawView($arOption);
                            break;
                        // Не извесный тип
                        default:
                            self::DrawDefaultField($arOption);
                            break;
                    }
                }
            }

            echo '</div>';
            $active = false;
        }

        echo '</div>';
    }

    public static function GetGlobalSettings($arParams = [])
    {

        $arResult = [];

        if (!is_array($arParams)) {
            $arParams = (array)$arParams;
        }

        // get all settings if empty $arParams
        if (empty($arParams)) {
            return Option::getForModule(self::$module_id);
            // get current settings if $arParams is not empty
        } else {
            foreach ($arParams as $key => $option) {
                $arResult[$option] = Option::get(self::$module_id, $option);
            }
        }

        return $arResult;

    }

    public static function ShowFilePropertyField($name, $arOption, $values)
    {

        $values = unserialize(Option::get(self::$module_id, $arOption["NAME"], serialize(array())));

        global $bCopy, $historyId;

        if (!is_array($values)) {
            $values = array($values);
        }

        if ($bCopy || empty($values)) {
            $values = array('n0' => 0);
        }

        $optionWidth = $arOption['WIDTH'] ? $arOption['WIDTH'] : 200;
        $optionHeight = $arOption['HEIGHT'] ? $arOption['HEIGHT'] : 100;

        if ($arOption['MULTIPLE'] == 'N') {
            foreach ($values as $key => $val) {
                if (is_array($val)) {
                    $file_id = $val['VALUE'];
                } else {
                    $file_id = $val;
                }
                if ($historyId > 0) {
                    echo CFileInput::Show($name . '[' . $key . ']', $file_id,
                        array(
                            'IMAGE' => $arOption['IMAGE'],
                            'PATH' => 'Y',
                            'FILE_SIZE' => 'Y',
                            'DIMENSIONS' => 'Y',
                            'IMAGE_POPUP' => 'Y',
                            'MAX_SIZE' => array(
                                'W' => $optionWidth,
                                'H' => $optionHeight,
                            ),
                        )
                    );
                } else {
                    echo CFileInput::Show($name . '[' . $key . ']', $file_id,
                        array(
                            'IMAGE' => $arOption['IMAGE'],
                            'PATH' => 'Y',
                            'FILE_SIZE' => 'Y',
                            'DIMENSIONS' => 'Y',
                            'IMAGE_POPUP' => 'Y',
                            'MAX_SIZE' => array(
                                'W' => $optionWidth,
                                'H' => $optionHeight,
                            ),
                        ),
                        array(
                            'upload' => true,
                            'medialib' => true,
                            'file_dialog' => true,
                            'cloud' => true,
                            'del' => true,
                            'description' => $arOption['WITH_DESCRIPTION'] == 'Y',
                        )
                    );
                }
                break;
            }
        } else {
            $inputName = array();
            foreach ($values as $key => $val) {
                if (is_array($val)) {
                    $inputName[$name . '[' . $key . ']'] = $val['VALUE'];
                } else {
                    $inputName[$name . '[' . $key . ']'] = $val;
                }
            }
            if ($historyId > 0) {
                echo CFileInput::ShowMultiple($inputName, $name . '[n#IND#]',
                    array(
                        'IMAGE' => $arOption['IMAGE'],
                        'PATH' => 'Y',
                        'FILE_SIZE' => 'Y',
                        'DIMENSIONS' => 'Y',
                        'IMAGE_POPUP' => 'Y',
                        'MAX_SIZE' => array(
                            'W' => $optionWidth,
                            'H' => $optionHeight,
                        ),
                    ),
                    false);
            } else {
                echo CFileInput::ShowMultiple($inputName, $name . '[n#IND#]',
                    array(
                        'IMAGE' => $arOption['IMAGE'],
                        'PATH' => 'Y',
                        'FILE_SIZE' => 'Y',
                        'DIMENSIONS' => 'Y',
                        'IMAGE_POPUP' => 'Y',
                        'MAX_SIZE' => array(
                            'W' => $optionWidth,
                            'H' => $optionHeight,
                        ),
                    ),
                    false,
                    array(
                        'upload' => true,
                        'medialib' => true,
                        'file_dialog' => true,
                        'cloud' => true,
                        'del' => true,
                        'description' => $arOption['WITH_DESCRIPTION'] == 'Y',
                    )
                );
            }
        }
    }

    public static function SaveFileField($optionName)
    {
        $arValueDefault = serialize(array());
        $optionValue = unserialize(Option::get(self::$module_id, $optionName, serialize(array())));

        // Если надо удалить файл
        if (isset($_REQUEST[$optionName . '_del']) || (isset($_FILES[$optionName]) && strlen($_FILES[$optionName]['tmp_name']['0']))) {
            $arValues = $optionValue;
            $arValues = (array)$arValues;
            foreach ($arValues as $fileID) {
                CFile::Delete($fileID);
            }
            $optionValue = serialize(array());
        }

        if (isset($_FILES[$optionName]) && (strlen($_FILES[$optionName]['tmp_name']['n0']) || strlen($_FILES[$optionName]['tmp_name']['0']))) {
            $arValues = array();
            $absFilePath = (strlen($_FILES[$optionName]['tmp_name']['n0']) ? $_FILES[$optionName]['tmp_name']['n0'] : $_FILES[$optionName]['tmp_name']['0']);
            $arOriginalName = (strlen($_FILES[$optionName]['name']['n0']) ? $_FILES[$optionName]['name']['n0'] : $_FILES[$optionName]['name']['0']);
            if (file_exists($absFilePath)) {
                $arFile = CFile::MakeFileArray($absFilePath);
                $arFile['name'] = $arOriginalName; // for original file extension

                if ($bIsIco = strpos($arOriginalName, '.ico') !== false) {
                    $script_files = Option::get("fileman", "~script_files", "php,php3,php4,php5,php6,phtml,pl,asp,aspx,cgi,dll,exe,ico,shtm,shtml,fcg,fcgi,fpl,asmx,pht,py,psp,var");
                    $arScriptFiles = explode(',', $script_files);
                    if (($p = array_search('ico', $arScriptFiles)) !== false)
                        unset($arScriptFiles[$p]);

                    $tmp = implode(',', $arScriptFiles);
                    Option::set("fileman", "~script_files", $tmp);
                }

                if ($fileID = CFile::SaveFile($arFile, self::$moduleClass))
                    $arValues[] = $fileID;

                if ($bIsIco)
                    Option::set("fileman", "~script_files", $script_files);
            }
            $optionValue = serialize($arValues);
        }

        if (!isset($_FILES[$optionName]) || (!strlen($_FILES[$optionName]['tmp_name']['n0']) && !strlen($_FILES[$optionName]['tmp_name']['0']) && !isset($_REQUEST[$optionName . '_del']))) {
            //return;
        }

        if ($optionName === 'WEBCOMP_FILE_SITE_FAVICON') {
            // Копируем favicon в директорию сайта
            self::CopyFaviconToSite($optionValue);
        }

        return $optionValue;
    }

    public static function CopyFaviconToSite($arValue)
    {

        if (is_string($arValue) && $arValue) {
            $arValue = unserialize($arValue);
        }

        if (isset($arValue[0]) && $arValue[0]) {
            $imageSrc = $_SERVER['DOCUMENT_ROOT'] . CFile::GetPath($arValue[0]);
        }

        if (file_exists($imageSrc)) {
            $imageDest = $_SERVER['DOCUMENT_ROOT'] . '/images/favicon/favicon-original.png';

            if (file_exists($imageDest)) {
                if (sha1_file($imageSrc) == sha1_file($imageDest)) {
                    return;
                }
            }

            $path=$_SERVER['DOCUMENT_ROOT'] . '/images/favicon/apple-touch-icon.png';
            $imageApple = CFile::ResizeImageFile(
                $imageSrc,
                $path,
                array('width'=>180,'height'=>180),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                array(),
                false,
                false
            );

            $path=$_SERVER['DOCUMENT_ROOT'] . '/images/favicon/favicon-32x32.png';
            $image32 = CFile::ResizeImageFile(
                $imageSrc,
                $path,
                array('width'=>32,'height'=>32),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                array(),
                false,
                false
            );

            $path=$_SERVER['DOCUMENT_ROOT'] . '/images/favicon/favicon-16x16.png';
            $image16 = CFile::ResizeImageFile(
                $imageSrc,
                $path,
                array('width'=>16,'height'=>16),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                array(),
                false,
                false
            );

            $path=$_SERVER['DOCUMENT_ROOT'].'/favicon.ico';
            $favicon = CFile::ResizeImageFile(
                $imageSrc,
                $path,
                array('width'=>16,'height'=>16),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                array(),
                false,
                false
            );

            @unlink($imageDest);
            @copy($imageSrc, $imageDest);
        }

    }

    public static function GetDescription($arDescription) {
        $arResult = "";

        if (!empty($arDescription["TITLE"])) {

            $description = $arDescription["TITLE"];

            $optionLink = "";
            if (!empty($arDescription["LINK"])) {

                $optionLink .= ' <a target="_blank" href="' . $arDescription["LINK"]["HREF"] . '">' . $arDescription["LINK"]["TEXT"] . '</a>';
                $description .= $optionLink;
            }

            $arResult .= '<span class="webcomp-settings__field-description">';
            $arResult .= '<span class="webcomp-settings__description-symbol">?</span>';
            $arResult .= '</span>';
            $arResult .= '<span class="webcomp-settings__description-hidden">'.$description.'</span>';

        }

        return $arResult;
    }

    public static function GetOption($arOption)
    {
        $arResult = [];

        $arResult = [
            "TITLE" => $arOption["TITLE"] ?: "",
            "DEFAULT" => $arOption["DEFAULT"] ?: "",
            "NAME" => $arOption["NAME"],
            "VALUE" => Option::get(self::$module_id, $arOption["NAME"], $optionDefault),
            "CLASS" => $arOption["STYLE"]["CLASS"] ?: "",
            "DISABLED" => $arOption["STYLE"]["DISABLED"] === "Y" ? "disabled" : "",
            "INPUT_CLASS" => "field-small"
        ];

        // Обработка дата параметров
        $arResult["DATA"] = "";
        if (!empty($arOption["STYLE"]["DATA"])) {
            foreach ($arOption["STYLE"]["DATA"] as $key => $value) {
                $arResult["DATA"] .= 'data-' . strtolower($key) . '="' . $value . '" ';
            }
        }

        // Если чекбокс то проверяем на чекед
        if ($arOption["TYPE"] === "CHECKBOX")
            $arResult["CHECKED"] = $arResult["VALUE"] === "Y" ? "checked" : "";

        // Если тип просто строка
        if (($arOption["TYPE"] === "STRING" || $arOption["TYPE"] === "MULTIPLE_STRING") && $arOption["STYLE"]["WIDTH"]) {
            switch ($arOption["STYLE"]["WIDTH"]) {
                case "MINI": { $arResult["INPUT_CLASS"] = "field-mini"; break; }
                case "SMALL": { $arResult["INPUT_CLASS"] = "field-small"; break; }
                case "MEDIUM": { $arResult["INPUT_CLASS"] = "field-medium"; break; }
                case "FULL": { $arResult["INPUT_CLASS"] = "field-full"; break; }
                default: { $arResult["INPUT_CLASS"] = "field-small"; }
            }
        }

        if(!empty($arOption["INFO"])) {
            $arResult["INFO"] = $arOption["INFO"];
        }

        if($arOption["STYLE"]["BOLD"] === "Y") {
            $arResult["CLASS"] .= " field-heading--bold";
        }


        if (in_array($arOption["TYPE"], ["SELECT", "MULTIPLE_SELECT", "SELECT_THEME", "SECTIONS", "VIEW"])) {
            $arResult["arVALUES"] = $arOption["VALUES"];
            $arResult["SIZE"] = $arOption["STYLE"]["HEIGHT"];
            $arResult["ALIGN"] = intval($arResult["SIZE"]) > 1 ? "adm-detail-valign-top" : "";
        }

        // Обработка дополнительного описания
        if (isset($arOption["DESCRIPTION"]) && !empty($arOption["DESCRIPTION"])) {
            $arResult["DESCRIPTION"] = self::GetDescription($arOption["DESCRIPTION"]);

            if($arOption["TYPE"] === "CHECKBOX") {
                $arResult["TITLE"] = '<label for="'.$arResult["NAME"].'">'.$arResult["TITLE"].'</label>'.$arResult["DESCRIPTION"];
            } else {
                $arResult["TITLE"] = $arResult["TITLE"] . $arResult["DESCRIPTION"];
            }


        }

        if ($arOption["TYPE"] === "EDITOR") {
            $arResult["FILE"] = $arOption["FILE"];
            $arResult["NOT_USE_HTML"] = $arOption["NOT_USE_HTML"];
        }

        return $arResult;
    }

    public static function DrawDefaultField($arOption)
    {
        $optionClass = $arOption["STYLE"]["CLASS"] ?: "";
        echo '<div class="default-filed ' . $optionClass . '"><div colspan="2">' . $arOption["TITLE"] . '</div></div>';
    }

    public static function DrawHeadingField($arOption) {
        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."heading.php",
            ["arOption" => $arOption],
            ["MODE" => "php"]
        );
    }

    public static function DrawInfoField($arOption)
    {
        $optionClass = $arOption["STYLE"]["CLASS"] ?: "";

        echo '<tr class="' . $optionClass . '"><td colspan="2" align="center"><div class="adm-info-message-wrap" align="center">
						<div class="adm-info-message">' . $arOption["TITLE"] . '</div></div></td></tr>';
    }

    public static function DrawStringField($arOption) {
        // Собираем все необходимые поля
        $arResult = self::GetOption($arOption);

        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."string.php",
            ["arOption" => $arResult],
            ["MODE" => "php"]
        );
    }

    public static function DrawMultipleStringField($arOption)
    {

        // Собираем все необходимые поля
        $arResult = self::GetOption($arOption);
        $arResult["VALUE"] = unserialize($arResult["VALUE"]);

        echo '<tr class="' . $arResult["CLASS"] . '">
						<td class="adm-detail-valign-top adm-detail-content-cell-l" width="40%">' . $arResult["TITLE"] . '</td>
						<td width="60%" class="adm-detail-content-cell-r adm-detail-content-cell-r_multiple">';

        if (is_array($arResult["VALUE"])) {
            foreach ($arResult["VALUE"] as $key => $item) {
                echo '<div class="adm-detail-input-row">
												<input
												type="text" 
												size="' . $arResult["WIDTH"] . '" 
												maxlength="255" 
												value="' . $item . '" 
												name="' . $arResult["NAME"] . '[]"
												' . $arResult["DISABLED"] . ' 
												' . $arResult["DATA"] . '> 
												<span class="adm-multiple_remove"></span>
											</div>';
            }
        }

        echo '</td>
							<tr style="text-align:center;">
								<td colspan="2">
								<a href="javascript:;" class="adm-btn adm-btn-save adm-btn-add js-add-newLine" title="">'.Loc::getMessage("WEBCOMP_MARKET_ADD").'</a></td>
							</tr>
					</tr>';
    }

    public static function DrawCheckBoxField($arOption) {
        $arResult = self::GetOption($arOption);

        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."checkbox.php",
            ["arOption" => $arResult],
            ["MODE" => "php"]
        );
    }

    public static function DrawSelectField($arOption) {
        $arResult = self::GetOption($arOption);

        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."select.php",
            ["arOption" => $arResult],
            ["MODE" => "php"]
        );
    }

    public static function DrawSelectThemeField($arOption) {
        // Собираем все необходимые поля
        $arResult = self::GetOption($arOption);

        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."theme.php",
            ["arOption" => $arResult],
            ["MODE" => "php"]
        );
    }

    public static function DrawSections($arOption) {
        $arResult = self::GetOption($arOption);
        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."sections.php",
            ["arOption" => $arResult],
            ["MODE" => "php"]
        );
    }

    public static function DrawView($arOption) {
        $arResult = self::GetOption($arOption);
        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."view.php",
            ["arOption" => $arResult],
            ["MODE" => "php"]
        );
    }


    public static function DrawMultipleSelectField($arOption)
    {

        // Собираем все необходимые поля
        $arResult = self::GetOption($arOption);
        $arResult["VALUE"] = unserialize($arResult["VALUE"]);

        echo '<tr class="' . $arResult["CLASS"] . '">
						<td width = "40%" class="adm-detail-content-cell-l ' . $arResult["ALIGN"] . '">' . $arResult["TITLE"] . ' </td >
						<td width = "60%" class="adm-detail-content-cell-r" >
							<select multiple name = "' . $arResult["NAME"] . '[]" class="typeselect" size = "' . $arResult["SIZE"] . '" ' . $arResult["DATA"] . ' > ';

        foreach ($arResult["arVALUES"] as $key => $value) {
            $optionSelected = (in_array($key, $arResult["VALUE"])) ? "selected" : "";
            echo '<option value = "' . $key . '" ' . $optionSelected . ' > ' . $value . '</option > ';
        }

        echo '</select >
						</td >
					</tr > ';
    }

    public static function DrawEditorField($arOption) {
        $arResult = self::GetOption($arOption);

        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."editor.php",
            ["arOption" => $arResult, "editTitle" => Loc::getMessage('WEBCOMP_MARKET_EDIT')],
            ["MODE" => "php"]
        );
    }

    public static function DrawFileField($arOption) {
        $arResult = self::GetOption($arOption);

        $GLOBALS["APPLICATION"]->IncludeFile(
            self::$fieldPath."file.php",
            ["arOption" => $arOption, "arResult" => $arResult],
            ["MODE" => "php"]
        );
    }
}
