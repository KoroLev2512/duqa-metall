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

class OrderPage extends CBitrixComponent
{
    private $APPLICATION;

    public function onPrepareComponentParams($arParams)
    {
        global $APPLICATION;
        $this->APPLICATION = $APPLICATION;

        if (empty($this->arParams)) {
            $this->arParams = $arParams;
        }

        if(empty($this->arParams["EMAIL_TEMPLATE"])) {
            $this->arParams["EMAIL_TEMPLATE"] = 0;
        }

        return $this->arParams;
    }

    #<editor-fold desc="selectPropertiesAndAddToItems">

    public function executeComponent()
    {
        if ($this->startResultCache()) {
            $this->includeComponentTemplate();
            return $this->arResult;
        }
    }

}