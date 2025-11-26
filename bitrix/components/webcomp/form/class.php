<?php
if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

#<editor-fold desc="Namespaces">
use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Diag,
    Bitrix\Main\Application,
    Bitrix\Main\Entity;
#Namespaces </editor-fold>

class FormFieldsGetList extends CBitrixComponent
{
    private $APPLICATION;
    /**
     * @var int
     */
    private $iblock;
    /**
     * @var array
     */
    private $arProperties;
    /**
     * @var array
     */
    private $orderSelect;
    /**
     * @var array
     */
    private $filterSelect;

    private $dbElements;

    private $arPropertiesQuery;

    public function onPrepareComponentParams($arParams)
    {
        global $APPLICATION;
        $this->APPLICATION = $APPLICATION;

        $this->arProperties = [];

        if (empty($this->arParams)) {
            $this->arParams = $arParams;
        }

        Loader::includeModule("iblock");
      
        $this->iblock
            = \Bitrix\Iblock\Iblock::wakeUp($this->arParams['IBLOCK_ID']);

        if(empty($this->arParams["EMAIL_TEMPLATE"])) {
            $this->arParams["EMAIL_TEMPLATE"] = 0;
        }

        if (empty($this->arParams['SORT_BY1'])) {
            $this->arParams['SORT_BY1'] = 'ID';
        }

        if (empty($this->arParams['SORT_ORDER1'])) {
            $this->arParams['SORT_ORDER1'] = 'DESC';
        }

        if ( ! empty($this->arParams['SORT_BY2'])
            && ! empty($this->arParams['SORT_ORDER2'])
        ) {
            $this->orderSelect = [
                $this->arParams['SORT_BY1'] => $this->arParams['SORT_ORDER1'],
                $this->arParams['SORT_BY2'] => $this->arParams['SORT_ORDER2'],
            ];
        } else {
            $this->orderSelect = [
                $this->arParams['SORT_BY1'] => $this->arParams['SORT_ORDER1'],
            ];
        }

        if(!empty($this->arParams['IBLOCK_ID'])) {
            $this->filterSelect = [
                "IBLOCK_ID" => $this->arParams['IBLOCK_ID']
            ];

            if($this->arParams["SHOW_ONLY_ACTIVE"] === 'Y') {
                $this->filterSelect = array_merge($this->filterSelect, ["ACTIVE" => "Y"]);
            }
        }
      

        return $this->arParams;
    }

    #<editor-fold desc="selectElementsFromDB">

    public function executeComponent()
    {

        if ($this->startResultCache()) {
            $this->selectPropertiesForIB();
            if ($this->arParams['DONT_INCLUDE_TEMPLATE'] != 'Y') {
                $this->includeComponentTemplate();
            }

            return $this->arResult;
        }
    }
    #selectElementsFromDB </editor-fold>

    #<editor-fold desc="replaceMacros">

    private function replaceMacros($arProperty, $string) {

        preg_match_all('/\{(.+)\}/U', $string, $matches, PREG_SET_ORDER);

        if(empty($matches))
            return $string;

        foreach ($matches as $key => $val) {
            $replaceString = current($val);
            $replaceField = end($val);
            $replaceField = end(explode("this.", $replaceField));

            $replaceValue = $arProperty[$replaceField] ?: "";

            $string = str_replace($replaceString, $arProperty[$replaceField], $string);                
        }

        return $string;
    }

    #replaceMacros </editor-fold>

    #<editor-fold desc="selectPropertiesAndAddToItems">

    protected function selectPropertiesForIB()
    {
        //Выборка свойств инфоблока (с типами данных для распределения потом у кого какой тип данных)
        $iblock = \Bitrix\Iblock\IblockTable::getList([
            'select' => array('NAME','DESCRIPTION'),
            'filter' => array('ID' =>  $this->arParams["IBLOCK_ID"])
        ])->fetch();

        $this->arResult["IBLOCK"] = [
            "ID" => $this->arParams["IBLOCK_ID"],
            "NAME" => $iblock["NAME"],
            "DESCRIPTION" => $iblock["DESCRIPTION"],
        ];

        $this->arResult["EMAIL_EVENT_ID"] = $this->arParams["EMAIL_EVENT_ID"];
        $this->arResult["FORM_NAME"] = $this->arParams["FORM_NAME"];

        $rsProperty = \Bitrix\Iblock\PropertyTable::getList([
            'select' => [
                'ID',
                'NAME',
                'CODE',
                'LIST_TYPE',
                'PROPERTY_TYPE',
                'MULTIPLE',
                'XML_ID',
                'IS_REQUIRED',
                'USER_TYPE',
                'USER_TYPE_SETTINGS',
            ],
            'filter' => $this->filterSelect,
            'order' => $this->orderSelect,

        ]);

        while ($arProperty = $rsProperty->fetch()) {

            $this->arProperties[$arProperty['CODE']] = $arProperty;

            $userProp = unserialize($arProperty["USER_TYPE_SETTINGS"]);

            // switch property_type
            switch ($arProperty["USER_TYPE"]) {
                case 'FORM_ADDRESS':
                    $this->arProperties[$arProperty['CODE']]["PROPERTY_TYPE"] = "ADDRESS";
                    break;
                case 'FORM_PHONE':
                    $this->arProperties[$arProperty['CODE']]["PROPERTY_TYPE"] = "PHONE";
                    break;
                case 'FORM_EMAIL':
                    $this->arProperties[$arProperty['CODE']]["PROPERTY_TYPE"] = "EMAIL";
                    break;
                case 'FORM_TEXT':
                    $this->arProperties[$arProperty['CODE']]["PROPERTY_TYPE"] = "TEXT";
                    break;
                case 'FORM_BIND':
                    $this->arProperties[$arProperty['CODE']]["PROPERTY_TYPE"] = "BIND";
                    // add elements id
                    if (isset($this->arParams["BIND_ELEMENTS"]) && !empty($this->arParams["BIND_ELEMENTS"])) {

                        if(!is_array($this->arParams["BIND_ELEMENTS"]))
                            $this->arParams["BIND_ELEMENTS"] = [$this->arParams["BIND_ELEMENTS"] => 1];

                        $this->dbElements = \Bitrix\Iblock\ElementTable::getList([
                            'select' => ["ID", "NAME"],
                            'filter' => ["ID" => array_keys($this->arParams["BIND_ELEMENTS"])],
                        ])->fetchAll();

                        foreach($this->dbElements as $key => $item) {
                            $this->arProperties[$arProperty['CODE']]["ELEMENTS"][$item["ID"]] = [
                                "ID" => $item["ID"],
                                "NAME" => $item["NAME"],
                                "QUANTITY" => $this->arParams["BIND_ELEMENTS"][$item["ID"]]
                            ];
                        }
                    }
                    break;
                case 'FORM_FILE':
                    $this->arProperties[$arProperty['CODE']]["PROPERTY_TYPE"] = "FILE";
                    break;
                case 'FORM_RATING':
                    $this->arProperties[$arProperty['CODE']]["PROPERTY_TYPE"] = "RATING";
                    $this->arProperties[$arProperty['CODE']]["MIN_VALUE"] = "0";
                    $this->arProperties[$arProperty['CODE']]["MAX_VALUE"] = $userProp["PROPERTY_MAX_VALUE"] ?: 5;
                    break;
                default:
                    $this->arProperties[$arProperty['CODE']]["PROPERTY_TYPE"] = "STRING";
                    break;
            }

            // user property
            $this->arProperties[$arProperty['CODE']]["IS_DISABLED"] = $userProp["PROPERTY_DISABLED_FIELD"] ?: "N";
            $this->arProperties[$arProperty['CODE']]["IS_HIDDEN"] = $userProp["PROPERTY_HIDDEN_FIELD"] ?: "N";
            $this->arProperties[$arProperty['CODE']]["ERROR_MSG"] = $this->replaceMacros($arProperty, $userProp["PROPERTY_MSG_ERROR_VALIDATE"]) ?: Loc::getMessage("PROPERTY_MSG_ERROR_VALIDATE_DEFAULT");

            // delete fields if empty element
            if($arProperty["USER_TYPE"] === "FORM_BIND" && !isset($this->arProperties[$arProperty['CODE']]["ELEMENTS"])) {
                unset($this->arProperties[$arProperty['CODE']]);
            }
        }

        $this->arResult["FIELDS"] = $this->arProperties;           
    }

}