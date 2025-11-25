<?
namespace Webcomp\Market\Property;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class FormPhone {

    const PROP_CODE = 'FORM_PHONE';
    const IBLOCK_TYPE = "forms";

    /**
    * @return array
    */
    static function OnIBlockPropertyBuildList() {

      // show this field only in current type
     if(!isset($_REQUEST["propedit"]) && $_REQUEST["type"] !== self::IBLOCK_TYPE)
        return [];

      return [
	      "PROPERTY_TYPE"         => "S",
	      "USER_TYPE"             => self::PROP_CODE,
	      "DESCRIPTION"           => Loc::getMessage('PROPERTY_PHONE_NAME'),
	      "GetSettingsHTML"       => [__CLASS__, "GetSettingsHTML"],
	      "PrepareSettings"       => [__CLASS__, "PrepareSettings"],
	      "ConvertToDB"           => [__CLASS__, "ConvertToDB"],
	      "GetPropertyFieldHtml"  => [__CLASS__, "GetPropertyFieldHtml"],
	      "GetPublicEditHTML"     => [__CLASS__, "GetPublicEditHTML"],
	      "GetPublicViewHTML"     => [__CLASS__, "GetPublicViewHTML"],
      ];
    }

    /**
    * @param $arProperty
    * @param $value
    * @return mixed
    */
    static function ConvertToDB($arProperty, $value) {
      # modification value before saving to the database
      $value = trim(strip_tags($value["VALUE"]));
      return $value;
    }

    /**
    * @param $arProp
    * @param $value
    * @param $control
    * @return string
    */
    static function GetPublicViewHTML($arProp, $value, $control) {
  		# Show in public view
      return '';
    }

    /**
    * @param $arProp
    * @param $value
    * @param $control
    * @return string
    */
    static function GetPublicEditHTML($arProp, $value, $control) {
  		# Show in public editor html
        return  self::GetPropertyFieldHtml($arProp, $value, $control);
    }

    /**
    * @param $arProp
    * @param $value
    * @param $control
    * @return string
    */
    static function GetPropertyFieldHtml($arProp, $value, $control) {
  		#  Show field in administration panel
      $html = '<input type="text" name="'.$control["VALUE"].'" size="30" value="'.$value["VALUE"].'">';
      return  $html;
    }

    /**
    * @param $arFields
    * @return mixed
    */

    static function PrepareSettings($arFields) {
      # option fields
      if(empty($arFields["USER_TYPE_SETTINGS"]["PROPERTY_MSG_ERROR_VALIDATE"])) {
        $arFields["USER_TYPE_SETTINGS"]["PROPERTY_MSG_ERROR_VALIDATE"] = Loc::getMessage("PROPERTY_PHONE_MSG_ERROR_VALIDATE_DEFAULT");
      }
      return $arFields["USER_TYPE_SETTINGS"];
    }

    /**
    * @param $arProp
    * @param $control
    * @param $arPropertyFields
    * @return string
    */
    static function GetSettingsHTML($arProp, $control, &$arPropertyFields) {
      
    	$arProp['USER_TYPE_SETTINGS'] = self::PrepareSettings($arProp);
        $prop = $arProp['USER_TYPE_SETTINGS'];

    	# 'HIDE' - MULTIPLE, SEARCHABLE, FILTRABLE, WITH_DESCRIPTION, MULTIPLE_CNT, ROW_COUNT, COL_COUNT и DEFAULT_VALUE.
    	# 'SHOW' - MULTIPLE, SEARCHABLE, FILTRABLE, WITH_DESCRIPTION, MULTIPLE_CNT, ROW_COUNT и COL_COUNT.
    	# 'SET'  - MULTIPLE, SEARCHABLE, FILTRABLE, WITH_DESCRIPTION, MULTIPLE_CNT, ROW_COUNT и COL_COUNT.
    	# 'USER_TYPE_SETTINGS_TITLE' - string user heading 

      $arPropertyFields = [
	      'HIDE' => [
	      	'SMART_FILTER',
	      	'SEARCHABLE',
	      	'COL_COUNT',
	      	'ROW_COUNT',
	      	'FILTER_HINT',
	      	'MULTIPLE',
	      	'WITH_DESCRIPTION',
	      	'DEFAULT_VALUE',
	      	'MULTIPLE_CNT',
	      	'FILTRABLE',
	    	],
	    	'SET' => [],
	    	'SHOW' => [],
	    	'USER_TYPE_SETTINGS_TITLE' => Loc::getMessage("PROPERTY_PHONE_CUSTOM_BLOCK")
      ];

    	# create html code for draw custom fields 
    	ob_start();
			?>

    	<tr>
				<td width="40%" class="adm-detail-content-cell-l">
					<label for="MSG_ERROR_VALIDATE"><?=Loc::getMessage("PROPERTY_PHONE_MSG_ERROR_VALIDATE")?></label>
				</td>
				<td class="adm-detail-content-cell-r">
					<input  id="PROPERTY_PHONE_MSG_ERROR_VALIDATE" 
                  size="50" 
                  type="text" 
                  value="<?=$prop["PROPERTY_MSG_ERROR_VALIDATE"]?>" 
                  name="<?=$control["NAME"]?>[PROPERTY_MSG_ERROR_VALIDATE]"
          >
				</td>
			</tr>

      <tr>
        <td width="40%" class="adm-detail-content-cell-l">
          <label for="PROPERTY_PHONE_DISABLED_FIELD"><?=Loc::getMessage("PROPERTY_PHONE_DISABLED_FIELD")?></label>
        </td>
        <td class="adm-detail-content-cell-r">
          <input id="PROPERTY_PHONE_DISABLED_FIELD" 
                type="checkbox" 
                value="Y" 
                name="<?=$control["NAME"]?>[PROPERTY_DISABLED_FIELD]" 
              <?=($prop["PROPERTY_DISABLED_FIELD"]) == "Y" ? "checked" : ""?>
          >
        </td>
      </tr>

      <tr>
        <td width="40%" class="adm-detail-content-cell-l">
          <label for="PROPERTY_PHONE_HIDDEN_FIELD"><?=Loc::getMessage("PROPERTY_PHONE_HIDDEN_FIELD")?></label>
        </td>
        <td class="adm-detail-content-cell-r">
          <input id="PROPERTY_PHONE_HIDDEN_FIELD" 
                type="checkbox" 
                value="Y" 
                name="<?=$control["NAME"]?>[PROPERTY_HIDDEN_FIELD]" 
              <?=($prop["PROPERTY_HIDDEN_FIELD"]) == "Y" ? "checked" : ""?>
          >
        </td>
      </tr>

			<?

			$html = ob_get_contents();
			ob_end_clean();

			return $html;
    }
}
