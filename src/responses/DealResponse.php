<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 03.10.18
 * Time: 14:31
 */

namespace WalletOne\responses;

use yii\helpers\ArrayHelper;

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

class DealResponse extends BaseResponse
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

    protected $listName = 'Deals';

    public function rules()
    {
        $rules = [
            [
                [
                    'platformDealId',
                    'platformPayerId',
                    'payerPhoneNumber',
                    'platformBeneficiaryId',
                    'beneficiaryPaymentToolId',
                    'shortDescription',
                    'fullDescription',
                    'payerPaymentToolId',
                    'dealTypeId',
                    'lastErrorCode',
                    'lastErrorMessage',
                    'dealStateId',
                ],
                'string'
            ],
            [['createDate', 'updateDate', 'expireDate'], 'number'],
            [['amount', 'currencyId'], 'number'],
        ];
        return ArrayHelper::merge($rules, parent::rules());
    }

    public function setAttributes($values, $safeOnly = true)
    {
        if (!array_key_exists($this->listName, $values) && !array_key_exists('PlatformDealId', $values)) {
            $values = [$this->listName => $values];
        }
        parent::setAttributes($values, $safeOnly);
    }
}
