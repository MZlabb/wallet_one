<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 03.10.18
 * Time: 14:31
 */

namespace WalletOne\responses;

use yii\base\Model;

/**
 * Модель данных статуса сделки.
 *
 * Идентификатор сделки
 * @property string $platformDealId
 *
 * Статус сделки
 * @property string $dealStateId
 *
 * Время создания сделки
 * @property string $createDate
 *
 * Время последнего изменения сделки
 * @property string $updateDate

 * Время до которого сделка действительна
 * @property string $expireDate
 *
 * Идентификатор заказчика на стороне площадки
 * @property string $platformPayerId
 *
 * Номер телефона заказчика
 * @property string $payerPhoneNumber
 *
 * Id платежного метода заказчика (опционально)
 * @property string $payerPaymentToolId
 *
 * Идентификатор исполнителя на стороне площадки
 * @property string $platformBeneficiaryId
 *
 * Id платежного метода исполнителя
 * @property bool $beneficiaryPaymentToolId
 *
 * Сумма сделки
 * @property float $amount
 *
 * Валюта сделки в формате циферного кода ISO
 * @property int $currencyId
 *
 * Короткое описание сделки
 * @property string $shortDescription
 *
 * Полное описание сделки (опционально)
 * @property string $fullDescription
 *
 * Тип сделки
 * @property bool $dealTypeId
 *
 * Код ошибки
 * @property bool $lastErrorCode
 *
 * Описание ошибки
 * @property bool $lastErrorMessage
 *
 */

class DealResponse extends Model
{
    public $platformDealId;
    public $dealStateId;
    public $createDate;
    public $updateDate;
    public $expireDate;
    public $platformPayerId;
    public $payerPhoneNumber;
    public $payerPaymentToolId;
    public $platformBeneficiaryId;
    public $beneficiaryPaymentToolId;
    public $amount;
    public $currencyId;
    public $shortDescription;
    public $fullDescription;
    public $dealTypeId;
    public $lastErrorCode;
    public $lastErrorMessage;


    public function setAttributes($values, $safeOnly = true)
    {
        $array = [];
        foreach ($values as $key => $value) {
            $array[lcfirst($key)] = $values;
        }
        parent::setAttributes($array, $safeOnly);
    }
}