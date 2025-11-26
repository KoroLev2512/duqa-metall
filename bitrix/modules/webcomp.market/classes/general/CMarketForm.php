<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Mail\Event,
    Bitrix\Highloadblock as HL,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

/**
 * Class CMarketForm
 * Класс для управления отправкой различных форм с сайта
 */
class CMarketForm extends CMarket {
    protected static $iblock_id;
    protected static $arProperties;
    protected static $arPost;
    protected static $arFiles;
    protected static $arFields;
    protected static $orderID;
    protected static $sendMailExtension;

    /**
     * Основной метод, для формирования полного цикла отправки письма
     * @param $arPost
     * @return bool
     */
    public static function Send($arPost) {
        // check session_id
        if (!check_bitrix_sessid())
            return false;

        self::$arPost = $arPost;
        if (!isset(self::$arPost["IBLOCK_ID"]))
            die("Error send form !!");

        self::$iblock_id = self::$arPost["IBLOCK_ID"] ?: 0;

        // Получаем свойства инфоблока
        self::GetIblockProperties();

        // Добавляем новый элемент в инфоблок
        self::AddFormElement();

        // Для не стандартных форм, где надо еще записывать в разные инфоблоки
        switch (self::$arPost["FORM_NAME"]) {
            case "ORDER": CMarketFormOrder::init(); break;
            case "ONE_CLICK_BUY": CMarketFormFastBuy::init(); break;
            case "REVIEWS" : CMarketFormReviews::init(); break;
        }

        // Отправляем на почту письмо
        self::SendMail();

        if(!empty(self::$orderID))
            return self::$orderID;
        return true;
    }

    /**
     * Метод сбора свойства инфоблока
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private static function GetIblockProperties() {

        self::$arProperties = [];

        Loader::includeModule("iblock");

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
            ],
            'filter' => ["IBLOCK_ID" => self::$iblock_id],
        ]);

        while ($arProperty = $rsProperty->fetch()) {
            self::$arProperties[$arProperty["CODE"]] = $arProperty;
        }
    }

    /**
     * Метод подготовки данных для записи в инфоблок форм
     * @return array
     */
    private static function PrepareData() {
        $PROPERTY = [];

        foreach (self::$arProperties as $key => $field) {
            //prepare add for database
            if (isset(self::$arPost[$field["CODE"]])) {
                switch ($field["USER_TYPE"]) {
                    case "FORM_STRING":
                    case "FORM_ADDRESS":
                    case "FORM_PHONE":
                    case "FORM_EMAIL":
                    case "FORM_TEXT":
                        $PROPERTY[$field["ID"]] = strip_tags(trim(self::$arPost[$field["CODE"]]));
                        break;
                    case "FORM_BIND":
                    case "FORM_RATING":
                        $PROPERTY[$field["ID"]] = self::$arPost[$field["CODE"]];
                        break;
                    case "FORM_FILE":
                        // add file in upload
                        $file = self::$arPost[$field["CODE"]];

                        //todo: maybe error path at remote server
                        $arFile = CFile::MakeFileArray($file['tmp_name']);
                        $arFile['name'] = $file["name"];

                        if (!empty($arFile)) {
                            if ($fileID = CFile::SaveFile($arFile, self::MODULE_CLASS)) {
                                self::$arFiles[] = $PROPERTY[$field["ID"]] = intval($fileID);
                            }
                        }
                        break;

                }
            }
        }

        return $PROPERTY;
    }

    /**
     * Метод записи в инфоблок данных
     */
    private static function AddFormElement() {
        // На D7 походу не получится сделать, написано что метод заблокирован
        $el = new CIBlockElement;

        $PROPERTY = self::PrepareData();

        $data = [
            "IBLOCK_ID" => self::$iblock_id,
            "IBLOCK_SECTION_ID" => 0,
            "ACTIVE" => "Y",
            "NAME" => self::$arPost["NAME"] ?: Loc::getMessage("WEBCOMP_MARKET_MESSAGE"),
            "PROPERTY_VALUES" => $PROPERTY,
        ];

        // Добавление элемента
        if (!$el->Add($data)) {
            CMarketLog::Log(3, __FILE__, __LINE__, ["#IBLOCK_ID#" => self::$iblock_id]);
        }

    }

    /**
     * Метод отправки письма на почту клиенту
     */
    private static function SendMail() {

        if (empty(self::$arPost["EMAIL_EVENT_ID"])) {
            // TODO Не отправляем письмо вообще либо сделать дефолтный шаблон и отправлять на него ??
            return;
        }

        $MESSAGE = "";

        // Собираем данные для отправки
        foreach (self::$arProperties as $key => $field) {

            if (in_array($field["USER_TYPE"], ["FORM_FILE", "FORM_BIND"])) continue;

            if (!empty(self::$arPost[$field["CODE"]])) {
                $MESSAGE .= $field["NAME"] . ": " . self::$arPost[$field["CODE"]] . "<br/>";
            }
        }

        $arMail = [
            "EVENT_NAME" => self::$arPost["EMAIL_EVENT_ID"],
            "LID" => SITE_ID,
            "C_FIELDS" => [
                "MESSAGE" => $MESSAGE,
            ],
            "LANGUAGE_ID"=>'ru'
        ];

        if(!empty(self::$sendMailExtension)) {
            $arMail["C_FIELDS"]["MESSAGE"] = $arMail["C_FIELDS"]["MESSAGE"].self::$sendMailExtension;
        }

        // if need add file in send mail
        if (!empty(self::$arFiles)) {
            $arMail = array_merge($arMail, ["FILE" => self::$arFiles]);
        }

        Event::send($arMail);

    }
}

/**
 * Class CMarketFormFastBuy
 * Класс отрабатывает при попытке отправить форму ONE_CLICK_BUY
 */
class CMarketFormFastBuy extends CMarketForm {
    /**
     * Основной метод для формирования записи данных в инфоблоки
     * @throws \Bitrix\Main\LoaderException
     */
    public static function init() {
        Loader::includeModule("highloadblock");
        self::getFormInfo();
        self::addOrder();
        self::addOrderList();
        self::$sendMailExtension = self::extensionMessage();
    }

    /**
     * Метод полученя данных вводимых пользователем в форму
     */
    private static function getFormInfo() {
        foreach (self::$arProperties as $key => $field) {
            switch ($field["USER_TYPE"]) {
                case "FORM_STRING": self::$arFields["USER_INFO"]["UF_FIO"] = self::$arPost[$key]; break;
                case "FORM_PHONE": self::$arFields["USER_INFO"]["UF_PHONE"] = self::$arPost[$key]; break;
                case "FORM_EMAIL": self::$arFields["USER_INFO"]["UF_EMAIL"] = self::$arPost[$key]; break;
                case "FORM_TEXT": self::$arFields["USER_INFO"]["UF_COMMENT"] = self::$arPost[$key]; break;
                case "FORM_ADDRESS":
                    self::$arFields["USER_INFO"]["UF_ADDRESS"]
                        = self::$arPost[$key];
                    break;
                case "FORM_BIND":
                    self::$arFields["ELEMENTS"]
                        = self::getProducts(self::$arPost[$key], 1);
                    break;
                default: self::$arFields["USER_INFO"][$key] = self::$arPost[$key]; break;
            }
        }

        self::$arFields["USER_INFO"]["UF_DATE"] = date("d.m.Y H:i:s");
    }

    /**
     * Метод для получения элементов, которые заказает пользователь
     * @param $arElements
     * @param int $quantity
     * @return array
     */
    private static function getProducts($arElements, $quantity = 1) {

        if(empty($arElements)) return [];
        $elements = [];

        foreach($arElements as $element) {
            $elements[$element] = $quantity;
        }

        if(!empty($elements)) {

            self::$arFields["TOTAL_PRICE"] = 0;

            $res = CIBlockElement::getList(
                ["ID", "ASC"],
                ["ID" => array_keys($elements)],
                false,
                [],
                ["ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_PRICE"]
            );

            while ($dbElements = $res->GetNextElement()) {

                $arFields = $dbElements->GetFields();

                self::$arFields["PRODUCTS"][] = [
                    "ID" => $arFields["ID"],
                    "NAME" => $arFields["NAME"],
                    "PREVIEW_PICTURE" => $arFields["PREVIEW_PICTURE"],
                    "PRICE" => $arFields["PROPERTY_PRICE_VALUE"],
                    "QUANTITY" => $elements[$arFields["ID"]]
                ];

                self::$arFields["TOTAL_PRICE"] += $arFields["PROPERTY_PRICE_VALUE"] * $elements[$arFields["ID"]];

            }
        }

    }

    /**
     * Метод добавления заказа в highload block
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private static function addOrder() {
        $hlblock
            = HL\HighloadBlockTable::getById(CMarketTools::getIdHLByName('WebCompMarketOrders'))
            ->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        if(isset(self::$arFields["TOTAL_PRICE"]))
            $data["UF_SUM"] = self::$arFields["TOTAL_PRICE"];

        if(isset(self::$arFields["USER_INFO"])) {
            foreach (self::$arFields["USER_INFO"] as $key => $value) {
                $data[$key] = $value;
            }
        }

        $result = $entity_data_class::add($data);
        self::$orderID = $result->getId();
    }

    /**
     * Метод добавления позиций заказа в highload block
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private static function addOrderList() {
        $hlblock
            = HL\HighloadBlockTable::getById(CMarketTools::getIdHLByName('WebCompMarketOrderPosition'))
            ->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        foreach(self::$arFields["PRODUCTS"] as $item) {
            $data = [
                "UF_ORDER_ID" => self::$orderID,
                "UF_ELEMENT_ID" => $item["ID"],
                "UF_NAME" => $item["NAME"],
                "UF_PHOTO" => CFile::MakeFileArray($item["PREVIEW_PICTURE"]),
                "UF_QUANTITY" => $item["QUANTITY"],
                "UF_PRICE" => $item["PRICE"],
            ];

            $entity_data_class::add($data);
        }
    }

    /**
     * Метод расширения почтового сообщения
     * @return string
     */
    private static function extensionMessage() {
        $TOTAL_PRICE = self::$arFields["TOTAL_PRICE"];

        $html = "";

        $html .= '<table border="1" style="width: 100%;text-align: center;border-collapse: collapse;border: 1px;">';
        $html .= '<tr>';
        $html .= '<th style="padding: 5px">'.Loc::getMessage("WEBCOMP_MARKET_TABLE_HEADING").'</th>';
        $html .= '</tr>';

        foreach(self::$arFields["PRODUCTS"] as $item) {
            $html .= '<tr>';
            $html .= '<td><img src="'.$_SERVER["HTTP_ORIGIN"].CFile::getPath($item["PREVIEW_PICTURE"]).'" style="width:100px"></td>';
            $html .= '<td>'.$item["NAME"].'</td>';
            $html .= '<td>'.number_format($item["PRICE"], "0", ".", " ").' '.Loc::getMessage("WEBCOMP_MARKET_CURRENCY").'</td>';
            $html .= '<td>'.$item["QUANTITY"].'</td>';
            $html .= '<td>'.number_format($item["PRICE"] * $item["QUANTITY"], "0", ".", " ").' '.Loc::getMessage("WEBCOMP_MARKET_CURRENCY").'</td>';
            $html .= '</tr>';
        }

        $html .= '<tr>';
        $html .= '<th style="text-align: right; padding:10px;" colspan="4">'.Loc::getMessage("WEBCOMP_MARKET_TOTAL").'</th>';
        $html .= '<th>'.number_format($TOTAL_PRICE, "0", ".", " ").' '.Loc::getMessage("WEBCOMP_MARKET_CURRENCY").'</th>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }
}

/**
 * Class CMarketFormOrder
 * Класс отрабатывает при попытке отправить форму ORDER
 */
class CMarketFormOrder extends CMarketForm {
    /**
     * Основной метод для формирования записи данных в инфоблоки
     * @throws \Bitrix\Main\LoaderException
     */
    public static function init() {
        if (isset($_SESSION["CART"])) {
            Loader::includeModule("highloadblock");
            // Способ доставка
            if (isset(self::$arPost["DELIVERY"])) self::getDelivery();

            // Способ оплаты
            if (isset(self::$arPost["PAY"])) self::getPay();

            // Данные о пользователе
            self::getFormInfo();

            // Заказанные товары
            self::getProducts();

            // Регистрируем пользователя
            // TODO: Регистрация пользователя по установленной опции в админке
            self::registerUser();

            // Запись в HL_Block заказы
            self::addOrder();
            self::addOrderList();

            self::$sendMailExtension = self::extensionMessage();
        }
    }

    private static function registerUser() {
        global $USER;
        if(isset(self::$arFields["USER_INFO"]["UF_EMAIL"])) {

            $arFilter = [[["LOGIN"=> self::$arFields["USER_INFO"]["UF_EMAIL"]]]];

            $user = Bitrix\Main\UserTable::getList(Array(
                "select"=>Array("ID"),
                "filter"=> $arFilter,
            ))->fetch();

            if(empty($user)) {
                $password = randString(8);

                $USER->Register(
                    self::$arFields["USER_INFO"]["UF_EMAIL"],
                    self::$arFields["USER_INFO"]["UF_FIO"],
                    "",
                    $password,
                    $password,
                    self::$arFields["USER_INFO"]["UF_EMAIL"]
                );

                self::$arFields["USER_INFO"]["UF_USER"] = $USER->GetID();
            } else {
                self::$arFields["USER_INFO"]["UF_USER"] = $user["ID"];
            }

        }
    }

    /**
     * Метод возвращает данные о способе доставки
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private static function getDelivery() {

        $hlblock
            = HL\HighloadBlockTable::getById(CMarketTools::getIdHLByName('WebCompMarketDelivery'))
            ->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array(),
            "filter" => array("ID"=> (int) self::$arPost["DELIVERY"])
        ));

        while($arData = $rsData->Fetch()){
            self::$arFields["DELIVERY"] = [
                "ID" => $arData["ID"],
                "NAME" => $arData["UF_NAME"],
                "PRICE" => $arData["UF_PRICE"],
                "USER_PRICE" => $arData["UF_PRICE_FOR_USER"],
            ];
        }
    }

    /**
     * Метод возвращает данные о способе оплаты
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private static function getPay() {

        $hlblock
            = HL\HighloadBlockTable::getById(CMarketTools::getIdHLByName('WebCompMarketPayments'))
            ->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array(),
            "filter" => array("ID"=> (int) self::$arPost["PAY"])
        ));

        while($arData = $rsData->Fetch()){
            self::$arFields["PAY"] = [
                "ID" => $arData["ID"],
                "NAME" => $arData["UF_NAME"],
            ];
        }
    }

    /**
     * Метод полученя данных вводимых пользователем в форму
     */
    private static function getFormInfo() {
        // Данные пользователя
        foreach (self::$arProperties as $key => $field) {
            switch ($field["USER_TYPE"]) {
                case "FORM_STRING": self::$arFields["USER_INFO"]["UF_FIO"] = self::$arPost[$key]; break;
                case "FORM_PHONE": self::$arFields["USER_INFO"]["UF_PHONE"] = self::$arPost[$key]; break;
                case "FORM_EMAIL": self::$arFields["USER_INFO"]["UF_EMAIL"] = self::$arPost[$key]; break;
                case "FORM_TEXT": self::$arFields["USER_INFO"]["UF_COMMENT"] = self::$arPost[$key]; break;
                case "FORM_ADDRESS": self::$arFields["USER_INFO"]["UF_ADDRESS"] = self::$arPost[$key]; break;
                default: self::$arFields["USER_INFO"][$key] = self::$arPost[$key]; break;
            }
        }

        self::$arFields["USER_INFO"]["UF_DATE"] = date("d.m.Y H:i:s");
    }

    /**
     * Метод для получения элементов, которые заказает пользователь
     */
    private static function getProducts() {

        self::$arFields["TOTAL_PRICE"] = 0;

        $res = CIBlockElement::getList(
            ["ID", "ASC"],
            ["ID" => array_keys($_SESSION["CART"])],
            false,
            [],
            ["ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_PRICE"]
        );
        while ($dbElements = $res->GetNextElement()) {

            $arFields = $dbElements->GetFields();

            self::$arFields["PRODUCTS"][] = [
                "ID" => $arFields["ID"],
                "NAME" => $arFields["NAME"],
                "PREVIEW_PICTURE" => $arFields["PREVIEW_PICTURE"],
                "PRICE" => $arFields["PROPERTY_PRICE_VALUE"],
                "QUANTITY" => $_SESSION["CART"][$arFields["ID"]]
            ];

            self::$arFields["TOTAL_PRICE"] += $arFields["PROPERTY_PRICE_VALUE"] * $_SESSION["CART"][$arFields["ID"]];

        }
    }

    /**
     * Метод добавления заказа в highload block
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private static function addOrder() {

        $hlblock
            = HL\HighloadBlockTable::getById(CMarketTools::getIdHLByName('WebCompMarketOrders'))
            ->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        if(isset(self::$arFields["DELIVERY"])) {
            $data["UF_DELIVERY_ID"] = self::$arFields["DELIVERY"]["ID"];
            $data["UF_DELIVERY_PRICE"] = self::$arFields["DELIVERY"]["PRICE"];
        }

        if(isset(self::$arFields["PAY"]))
            $data["UF_PAYMENT_ID"] = self::$arFields["PAY"]["ID"];

        if(isset(self::$arFields["TOTAL_PRICE"]))
            $data["UF_SUM"] = self::$arFields["TOTAL_PRICE"];

        if(isset(self::$arFields["USER_INFO"])) {
            foreach (self::$arFields["USER_INFO"] as $key => $value) {
                $data[$key] = $value;
            }
        }

        $result = $entity_data_class::add($data);
        self::$orderID = $result->getId();
    }

    /**
     * Метод обновления заказа. На входе JSON с элементами заказа
     * [{"elementid":123,"lineid":35,"price":1.27,"quantity":1},{"id":9954,"lineid":0,"price":109,"quantity":2},{"elementid":10015,"lineid":0,"price":7650,"quantity":3}]
     * @param $JSONOrder
     */
    public static function updateOrder($JSONOrder){
        if($obj = json_decode($JSONOrder)) {
            self::$arFields["PRODUCTS"] = [];
            self::$orderID = $_REQUEST['ID'];
            foreach ($obj->items as $elem) {
                $element
                    = \Bitrix\Iblock\Elements\ElementCatalogWebcompTable::getByPrimary($elem->elementid,
                    [
                        'select' => ['ID', 'NAME', 'PREVIEW_PICTURE'],
                    ])->fetch();
                self::$arFields["PRODUCTS"][] = [
                    'ID'              => $elem->elementid,
                    'QUANTITY'        => $elem->quantity,
                    'PRICE'           => $elem->price,
                    'NAME'            => $element['NAME'],
                    'PREVIEW_PICTURE' => $element['PREVIEW_PICTURE'],
                    'UPDATE_ID' => (int)$elem->lineid>0 ? (int)$elem->lineid : false,
                ];

            }
            self::addOrderList();
            foreach ($obj->itemsRemoved as $rmElement) {
                self::removeElementFromHL(CMarketTools::getIdHLByName('WebCompMarketOrderPosition'),
                    $rmElement->lineid);
            }
            /**
             * Обновление итоговой цены заказа
             */
            $hlblock
                = HL\HighloadBlockTable::getById(CMarketTools::getIdHLByName('WebCompMarketOrders'))
                ->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $entity_data_class::update(self::$orderID , ['UF_SUM'=>$obj->total]);
        }
    }

    /**
     * Метод добавления позиций заказа в highload block
     *
     * @param $HL_ID
     * @param $elementID
     */
    private static function removeElementFromHL($HL_ID,$elementID) {
        $hlblock = HL\HighloadBlockTable::getById($HL_ID)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $entity_data_class::Delete($elementID );
    }

    /**
     * Метод добавления позиций заказа в highload block
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private static function addOrderList() {

        $hlblock
            = HL\HighloadBlockTable::getById(CMarketTools::getIdHLByName('WebCompMarketOrderPosition'))
            ->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        foreach(self::$arFields["PRODUCTS"] as $item) {
            $data = [
                "UF_ORDER_ID" => self::$orderID,
                "UF_ELEMENT_ID" => $item["ID"],
                "UF_NAME" => $item["NAME"],
                "UF_PHOTO" => CFile::MakeFileArray($item["PREVIEW_PICTURE"]),
                "UF_QUANTITY" => $item["QUANTITY"],
                "UF_PRICE" => $item["PRICE"],
            ];
            if($item["UPDATE_ID"]){
                unset($data["UF_NAME"],$data["UF_PHOTO"]);
                $entity_data_class::update($item["UPDATE_ID"], $data);
            }
            else
                $entity_data_class::add($data);

        }
    }

    /**
     * Метод расширения почтового сообщения
     * @return string
     */
    private static function extensionMessage() {

        $TOTAL_PRICE = self::$arFields["TOTAL_PRICE"];

        $html = "";

        $html .= '<table border="1" style="width: 100%;text-align: center;border-collapse: collapse;border: 1px;">';
        $html .= '<tr>';
        $html .= '<th style="padding: 5px">'.Loc::getMessage("WEBCOMP_MARKET_TABLE_HEADING").'</th>';
        $html .= '</tr>';

        foreach(self::$arFields["PRODUCTS"] as $item) {
            $html .= '<tr>';
            $html .= '<td><img src="'.$_SERVER["HTTP_ORIGIN"].CFile::getPath($item["PREVIEW_PICTURE"]).'" style="width:100px"></td>';
            $html .= '<td>'.$item["NAME"].'</td>';
            $html .= '<td>'.number_format($item["PRICE"], "0", ".", " ").' '.Loc::getMessage("WEBCOMP_MARKET_CURRENCY").'</td>';
            $html .= '<td>'.$item["QUANTITY"].'</td>';
            $html .= '<td>'.number_format($item["PRICE"] * $item["QUANTITY"], "0", ".", " ").' '.Loc::getMessage("WEBCOMP_MARKET_CURRENCY").'</td>';
            $html .= '</tr>';
        }

        $html .= '<tr>';
        $html .= '<th style="text-align: right; padding:10px;" colspan="4">'.Loc::getMessage("WEBCOMP_MARKET_TOTAL_SUM_ORDER").'</th>';
        $html .= '<th>'.number_format(self::$arFields["TOTAL_PRICE"], "0", ".", " ").' '.Loc::getMessage("WEBCOMP_MARKET_CURRENCY").'</th>';
        $html .= '</tr>';

        if(!empty(self::$arFields["PAY"])) {
            $html .= '<tr>';
            $html .= '<th>'.Loc::getMessage("WEBCOMP_MARKET_PAY").'</th>';
            $html .= '<td style="text-align: left; padding: 10px;" colspan="4">'.self::$arFields["PAY"]["NAME"].'</td>';
            $html .= '</tr>';
        }

        if(!empty(self::$arFields["DELIVERY"])) {

            $TOTAL_PRICE += self::$arFields["DELIVERY"]["PRICE"];

            $html .= '<tr>';
            $html .= '<th>'.Loc::getMessage("WEBCOMP_MARKET_DELIVERY").'</th>';
            $html .= '<td style="text-align: left; padding: 10px;" colspan="3">'.self::$arFields["DELIVERY"]["NAME"].'</td>';
            $html .= '<th>'.number_format(self::$arFields["DELIVERY"]["PRICE"], "0",".", " ").' '.Loc::getMessage("WEBCOMP_MARKET_CURRENCY").'</th>';
            $html .= '</tr>';
        }

        $html .= '<tr>';
        $html .= '<th style="text-align: right; padding:10px;" colspan="4">'.Loc::getMessage("WEBCOMP_MARKET_TOTAL").'</th>';
        $html .= '<th>'.number_format($TOTAL_PRICE, "0", ".", " ").' '.Loc::getMessage("WEBCOMP_MARKET_CURRENCY").'</th>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }
}

/**
 * Class CMarketFormReviews
 * Класс отрабатывает при попытке отправить форму REVIEWS
 */
class CMarketFormReviews extends CMarketForm {
    /**
     * Основной метод для формирования записи данных в инфоблоки
     * @throws \Bitrix\Main\LoaderException
     */
    public static function init()
    {
        // Добавление отзыва и инфоблок отзывы
        self::addReviews();

        // Добавление в письмо о каком элементе оставлен отзыв
        if (isset(self::$arPost["ELEMENT"]) && self::$arPost["ELEMENT"] > 0) {
            self::$sendMailExtension = self::extensionMessage();
        }
    }

    public static function getReviewsIblockId()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $arIblock = \Bitrix\Iblock\IblockTable::getList([
            'select' => ['ID', 'NAME', 'CODE', 'API_CODE', 'IBLOCK_TYPE_ID'],
            'cache'  => [
                'ttl'         => 3600,
                'cache_joins' => true,
            ],
            'filter' => [
                'CODE' => 'webcomp_market_content_reviews',
            ],
        ]);
        $ibl = $arIblock->fetch();

        return $ibl['ID'];
    }

    /**
     * Метод добавление отзыва в инфоблок отзывы
     */
    private static function addReviews()
    {
        // На D7 походу не получится сделать, написано что метод заблокирован
        $el = new CIBlockElement;

        $arProps = self::prepareData();

        $data = [
            "IBLOCK_ID"         => self::getReviewsIblockId(),
            "IBLOCK_SECTION_ID" => 0,
            "ACTIVE_FROM"       => date("d.m.Y H:i:s"),
            "ACTIVE"            => "N",
            "NAME"              => self::$arPost["NAME"] ?: Loc::getMessage("WEBCOMP_MARKET_REVIEWS"),
            "PROPERTY_VALUES"   => $arProps,
        ];

        // Добавление элемента
        if (!$el->Add($data)) {
            CMarketLog::Log(3, __FILE__, __LINE__,
                ["#IBLOCK_ID#" => self::getReviewsIblockId()]);
        }
    }

    /**
     * Метод подготовливает данные для записи в инфоблок
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private static function prepareData() {

        $arProps = $data = [];

        Loader::includeModule("iblock");

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
            ],
            'filter' => ["IBLOCK_ID" => self::getReviewsIblockId()],
        ]);

        while ($arProperty = $rsProperty->fetch()) {
            $arProps[$arProperty["CODE"]] = $arProperty;
        }

        if(!empty($arProps)) {
            foreach ($arProps as $fieldName => $field) {

                if(isset(self::$arPost[$fieldName])) {

                    $data[$field["ID"]] = self::$arPost[$fieldName];

                    if($field["PROPERTY_TYPE"] === "F") {
                        // add file in upload
                        $file = self::$arPost[$field["CODE"]];

                        //todo: maybe error path at remote server
                        $arFile = CFile::MakeFileArray($file['tmp_name']);
                        $arFile['name'] = $file["name"];

                        if (!empty($arFile)) {
                            if ($fileID = CFile::SaveFile($arFile, self::MODULE_CLASS)) {
                                $data[$field["ID"]] = intval($fileID);
                            }
                        }
                    }
                }

                if($field["CODE"] === "ELEMENT") {
                    // Привязка элемента
                    $data[$field["ID"]] = isset(self::$arPost["ELEMENT"]) ? (current(self::$arPost["ELEMENT"])) : 0;
                }
            }
        }

        return $data;

    }

    /**
     * Метод возвращает данные об элементе
     * @return array
     */
    private static function getElement() {
        $element = [];

        $res = CIBlockElement::getList(
            ["ID", "ASC"],
            ["ID" => (int) current(self::$arPost["ELEMENT"])],
            false,
            [],
            ["ID", "NAME"]
        );

        while ($dbElements = $res->GetNextElement()) {

            $arFields = $dbElements->GetFields();

            $element = [
                "ID" => $arFields["ID"],
                "NAME" => $arFields["NAME"],
                "DETAIL_PAGE_URL" => $arFields["DETAIL_PAGE_URL"],
            ];
        }

        return $element;
    }

    /**
     * Метод добавляет сообщение
     * @return string
     */
    private static function extensionMessage() {
        $element = self::getElement();
        $html = "";

        if(!empty($element))
            $html = "<p> ".Loc::getMessage("WEBCOMP_MARKET_REVIEWS_PRO")."<b>".$element["NAME"]."</b></p>";

        return $html;
    }
}
