<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 03.10.18
 * Time: 14:31
 */

namespace WalletOne\responses;

/**
 * Модель данных платежного метода.
 *
 * Идентификатор платежного метода
 * @property string $paymentToolId
 *
 * Тип платежного метода
 * @property string $paymentTypeId
 *
 * @property string $mask
 *
 */

class PaymentMethodResponse extends BaseResponse
{
    public $paymentToolId;
    public $paymentTypeId;
    public $mask;

    protected $listName = 'PaymentTools';
}
