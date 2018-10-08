<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 04.10.18
 * Time: 15:18
 */

namespace WalletOne;


use yii\helpers\ArrayHelper;

class W1Statuses
{
    const CREATED = 'Created';
    const PAYMENT_PROCESSING = 'PaymentProcessing';
    const PAYMENT_PROCESSING_ERROR = 'PaymentProcessError';
    const PAID = 'Paid';
    const PAYOUT_PROCESSING = 'PayoutProcessing';
    const PAYOUT_PROCESS_ERROR = 'PayoutProcessError';
    const COMPLETED = 'Completed';
    const CANCELING = 'Canceling';
    const CANCEL_ERROR = 'CancelError';
    const CANCELED = 'Canceled';
    const PAYMENT_HOLD = 'PaymentHold';
    const PAYMENT_HOLD_PROCESSING = 'PaymentHoldProcessing';

    /**
     * @return array
     */
    public static function getStatusNames()
    {
        return [
            self::CREATED => 'Сделка зарегистрирована в системе',
            self::PAYMENT_PROCESSING => 'Производится оплата сделки заказчиком',
            self::PAYMENT_PROCESSING_ERROR => 'Ошибка в процессе оплаты сделки',
            self::PAID => 'Сделка успешно оплачена заказчиком',
            self::PAYOUT_PROCESSING => 'Производится выплата исполнителю',
            self::PAYOUT_PROCESS_ERROR => 'Ошибка в процессе выплаты исполнителю',
            self::COMPLETED => 'Сумма сделки успешно выплачена исполнителю',
            self::CANCELING => 'Сделка отменяется (возврат заказчику)',
            self::CANCEL_ERROR => 'Ошибка в процессе отмены сделки',
            self::CANCELED => 'Сделка успешно отменена',
            self::PAYMENT_HOLD => 'Средства захолдированы на карте',
            self::PAYMENT_HOLD_PROCESSING => 'Сделка в процессе завершения оплаты либо отмены',
        ];
    }

    /**
     * @param string $status
     * @return string
     */
    public static function getStatusName(string $status)
    {
        return ArrayHelper::getValue(self::getStatusNames(), $status, '');
    }

    /**
     * @param string $status
     * @return bool
     */
    public static function isFinalStatus(string $status) : bool
    {
        return in_array($status, [self::COMPLETED, self::CANCELED]);
    }

    /**
     * @param string $status
     * @return bool
     */
    public static function isErrorStatus(string $status) : bool
    {
        return in_array($status, [self::PAYMENT_PROCESSING_ERROR, self::PAYOUT_PROCESS_ERROR, self::CANCEL_ERROR]);
    }
}