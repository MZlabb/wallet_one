<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 02.10.18
 * Time: 16:41
 */

namespace WalletOne\requests;

/**
 * Данный запрос используется для регистрация сделки.
 *
 * Идентификатор сделки
 * @property string $platformDealId
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
 * при значении false создается online (Instant) сделка
 * @property bool $deferPayout
 *
 */
class DealRegisterRequest extends BaseRequest
{
    public $platformDealId;
    public $platformPayerId;
    public $payerPhoneNumber;
    public $payerPaymentToolId;
    public $platformBeneficiaryId;
    public $beneficiaryPaymentToolId;
    public $amount;
    public $currencyId;
    public $shortDescription;
    public $fullDescription;
    public $deferPayout = true;

    public static $method = 'POST';
    public static $endPoint = '/api/v3/deals';

    public function rules()
    {
        return [
            [
                [
                    'platformDealId',
                    'platformPayerId',
                    'payerPhoneNumber',
                    'platformBeneficiaryId',
                    'amount',
                    'beneficiaryPaymentToolId',
                    'currencyId',
                    'shortDescription'
                ],
                'required'
            ],
            [['shortDescription', 'fullDescription', 'payerPaymentToolId'], 'string'],
            [['deferPayout'], 'boolean'],
            [['currencyId', 'amount'], 'number']
        ];
    }
}
