<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 04.10.18
 * Time: 15:18
 */

namespace WalletOne;

use yii\helpers\ArrayHelper;

class W1Error
{
    const PLATFORM_NOT_FOUND = 'PLATFORM_NOT_FOUND';
    const INVALID_SIGNATURE = 'INVALID_SIGNATURE';
    const INVALID_TIMESTAMP = 'INVALID_TIMESTAMP';
    const PLATFORM_ID_NOT_FOUND = 'PLATFORM_ID_NOT_FOUND';

    const DEAL_PARAMS_MISMATCH = 'DEAL_PARAMS_MISMATCH';
    const PAYER_ID_NOT_FOUND = 'PAYER_ID_NOT_FOUND';
    const BENEFICIARY_ID_NOT_FOUND = 'BENEFICIARY_ID_NOT_FOUND';
    const PAYER_PAYMENT_TOOL_NOT_FOUND = 'PAYER_PAYMENT_TOOL_NOT_FOUND';
    const BENEFICIARY_PAYMENT_TOOL_NOT_FOUND = 'BENEFICIARY_PAYMENT_TOOL_NOT_FOUND';
    const CURRENCY_ID_NOT_SUPPORTED = 'CURRENCY_ID_NOT_SUPPORTED';
    const AMOUNT_RANGE_ERROR = 'AMOUNT_RANGE_ERROR';
    const ARGUMENT_CHECK_ERROR = 'ARGUMENT_CHECK_ERROR';

    const DEAL_ID_NOT_FOUND = 'DEAL_ID_NOT_FOUND';
    const INVALID_DEAL_TYPE = 'INVALID_DEAL_TYPE';
    const DEAL_STATE_ID_ERROR = 'DEAL_STATE_ID_ERROR';
    const DEAL_CANCEL_ERROR = 'DEAL_CANCEL_ERROR';

    /**
     * @return array
     */
    public static function getErrorDescriptions()
    {
        return [
            self::PLATFORM_NOT_FOUND => 'Идентификатор площадки не найден',
            self::INVALID_SIGNATURE => 'Неверная подпись.',
            self::INVALID_TIMESTAMP => 'Значение поля Timestamp передано неверно',
            self::PLATFORM_ID_NOT_FOUND => 'Идентификатор площадки не найден',
            self::DEAL_PARAMS_MISMATCH => 'Параметры сделки не совпадают с переданными ранее. Или произошла попытка изменить тип сделки.',

            self::PAYER_ID_NOT_FOUND => 'Заказчик не найден',
            self::BENEFICIARY_ID_NOT_FOUND => 'Исполнитель не найден',
            self::PAYER_PAYMENT_TOOL_NOT_FOUND => 'Инструмент с переданным идентификатором не найден или не принадлежит указанному заказчику',
            self::BENEFICIARY_PAYMENT_TOOL_NOT_FOUND => 'Инструмент с переданным идентификатором не найден или не принадлежит указанному исполнителю',
            self::CURRENCY_ID_NOT_SUPPORTED => 'Указанный код валюты не поддерживается',
            self::AMOUNT_RANGE_ERROR => 'Недопустимое значение поля Amount',
            self::ARGUMENT_CHECK_ERROR => 'Прочие ошибки в параметрах.',

            self::DEAL_ID_NOT_FOUND => 'Сделка с указанным идентификатором не найдена',
            self::INVALID_DEAL_TYPE => 'Попытка завершить сделку с типом “Instant”',
            self::DEAL_STATE_ID_ERROR => 'Недопустимо для текущего состояния сделки.',
            self::DEAL_CANCEL_ERROR => 'Возврат вместе с комиссией недоступен',
        ];
    }

    /**
     * @param string $errorCode
     * @return string
     */
    public static function getErrorDescription(string $errorCode): string
    {
        return ArrayHelper::getValue(self::getErrorDescriptions(), $errorCode, '');
    }
}
