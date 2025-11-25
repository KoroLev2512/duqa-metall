<?php

namespace Flamix\BitrixIntegrations\Form;

use Bitrix\Main\Localization\Loc;
use Flamix\BitrixIntegrations\Email;
use Flamix\BitrixIntegrations\Lead;
use Flamix\BitrixIntegrations\Form;
use CFormResult;
use Exception;
use CForm;
use CSite;

/**
 * Класс для организации обработчиков модуля Веб-форм
 */
class Event
{
    /**
     * Обработчик при добавлении нового результата веб-формы
     *
     * @param int $formId - ID веб-формы
     * @param int $resultId - ID результата
     * @return void
     * @example вешается на событие
     */
    public static function onAfterResultAdd(int $formId, int $resultId)
    {
        if (!Form::isSendEnabled()) {
            return;
        }
        
        try {
            $arResult = null;
            $arFields2 = null;
            $arFields = CFormResult::GetDataByID($resultId, [], $arResult, $arFields2);

            $arData = ['FIELDS' => ['FORM_NAME' => self::getFormName($formId)]];

            //если код формы сгенерирован битриксом - делаем код типа FORM_1 для удобства
            $iFormId = (int) $arResult['FORM_ID'];
            $sFormCode = $arResult['SID'];
            if (substr($sFormCode, 0, 11) == 'SIMPLE_FORM') {
                $sFormCode = 'FORM_' . $iFormId;
            }

            foreach ($arFields as $sFieldCode => $arAnswers) {
                $arValues = [];
                foreach ($arAnswers as $arAnswer) {
                    $arValues[] = $arAnswer['USER_TEXT'] ?? $arAnswer['ANSWER_TEXT'];
                }

                $arValues = array_unique($arValues);
                $arValues = array_filter($arValues);

                //если код поля сгенерирован битриксом - делаем код типа FIELD_1 для удобства
                if (substr($sFieldCode, 0, 15) == 'SIMPLE_QUESTION') {
                    $sFieldCode = 'FIELD_' . $arAnswers[0]['FIELD_ID'];
                }

                //множесьвенные поля объединяем
                //код поля для отправки в б24 формируем как Код формы + код поля формы
                $arData['FIELDS'][$sFormCode . '_' . $sFieldCode] = implode(', ', $arValues);
            }

            foreach (GetModuleEvents('flamix.bitrixintegrations', 'onFormSubmitted', true) as $arEvent) {
                ExecuteModuleEventEx($arEvent, [$formId, $resultId, &$arData]);
            }

            //отправляем лид
            Lead::send($arData);

        } catch (Exception $e) {
            Loc::loadMessages(__FILE__);

            //отправляем уведомления об ошибке

            //для отправки нужен сайт, берем по умолчанию
            $arSites = [SITE_ID];

            //если мы в админке - берем сайт из формы
            if (CSite::InDir('/bitrix/') && isset($iFormId)) {
                $arSites = CForm::GetSiteArray($iFormId);
            }

            //отправляем уведомление по каждому сайту
            foreach ($arSites as $sSiteId) {
                Email::send([
                    'MESSAGE' => Loc::getMessage('FX_BI_ERROR', [
                        '#MESSAGE#' => $e->getMessage()
                    ])
                ], $sSiteId);
            }
        }
    }

    private static function getFormName(int $formId): string
    {
        $form = new CForm();
        $formInfo = $form->GetByID($formId)->Fetch();
        return $formInfo['NAME'] ?? 'Bitrix Form';
    }
}
