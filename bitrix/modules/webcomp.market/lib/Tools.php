<?php


namespace Webcomp\Market;


class Tools
{
    /**
     * @param $subject
     *
     * @return string|string[]|null
     * Метод удаляет приводит строку с обрамлением слешами с двух сторон
     */
    static function delRepeatSlashes($subject)
    {
        $subject = '/'.$subject.'/';
        $pattern = '/\/\/+/';
        return preg_replace($pattern, '/', $subject, -1, $countReplace);
    }

    /**
     * @param $arParams
     * Метод для очистки массива от пустых множественных значений. Используется для arParams. В select в D7 не должно быть пустых элементов, только поля в соответствии с базой
     */
    static function clearEmptyValuesFromArParams(&$arParams){
        foreach ($arParams as &$item){
            if(is_array($item)){
                $item = array_diff($item, ['']);
            }
        }
    }

    static function num2word($num = 0, $words = array())
    {
        $num     = (int) $num;
        $cases   = array(2, 0, 1, 1, 1, 2);
        return $num . ' ' . $words[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }

    /** Метод обрезает строку до определенной длины
     * @param $txt - Текст который нужно обрезать
     * @param $needLength - Длина которая нужна
     * @return string - Возвращает отрезанную строку
     */
    static function cutString($txt, $needLength = 200) {

        $txt = trim(strip_tags($txt));
        $length = mb_strlen($txt, 'UTF-8');
        if(mb_strlen($txt, 'UTF-8') > $needLength) {
            $tmp = mb_substr($txt, $needLength, $length, 'UTF-8');
            $pos = mb_strpos($tmp, ' ', 0, 'UTF-8');
            $pos += $needLength;
            $txt = mb_substr($txt, 0, $pos, 'UTF-8');
            return $txt.' ...';
        }

        return $txt;
    }

}
