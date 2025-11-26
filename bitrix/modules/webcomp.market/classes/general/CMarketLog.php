<?php

/**
 * Class CMarketLog
 * Класс для работы с логами ошибок
 */
class CMarketLog extends CMarket {
    const ERROR_FILE = "_log.txt";
    const ERROR_CODE_FILE = "include/errorCode.php";

    private static $dateFormat = "d-m-Y H:i:s";
    private static $__FILE__;
    private static $__LINE__;

    /**
     * Метод записывает в лог файл сообщение
     * @param $message
     * @param $__FILE__
     * @param $__LINE__
     * @param array $arReplace
     */
    public static function Log($message, $__FILE__,  $__LINE__, $arReplace = []) {
        $path = self::getModulePath().self::ERROR_FILE;
        if (!empty($path)) {

            self::$__FILE__ = $__FILE__;
            self::$__LINE__ = $__LINE__;

            $msg = is_numeric($message)
                ? self::getErrorMessage($message)
                : trim(strip_tags($message));

            $msg = self::prepareMessage($msg, $arReplace);

            file_put_contents($path, $msg, FILE_APPEND);
        }
    }

    /**
     * Метод возвращает отформатированную сторку сообщения
     * @param $message
     * @param $arReplace
     * @return string
     */
    private static function prepareMessage($message, $arReplace) {
        $date = "[".date(self::$dateFormat)."] ";

        if(!empty($arReplace)) {
            foreach($arReplace as $key => $mark) {
                $message = str_replace($key, $mark, $message);
            }
        }

        if(self::$__FILE__) $message .= " - ".self::$__FILE__;
        if(self::$__LINE__) $message .= ":".self::$__LINE__;

        return $date.$message."\r\n";
    }

    /**
     * Метод возвращает сообщение по коду ошибки
     * @param $errorCode
     * @return mixed|string
     */
    private static function getErrorMessage($errorCode) {
        $path = self::getModulePath().self::ERROR_CODE_FILE;

        if(file_exists($path)) {
            $arErrorCodes = include_once($path);

            foreach($arErrorCodes as $code => $message) {
                if($code == $errorCode) {
                    return $message[LANGUAGE_ID];
                }
            }
        }

        return "";
    }


}