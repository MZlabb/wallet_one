<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 28.11.18
 * Time: 14:35
 */

namespace WalletOne\responses;

/**
 * Модель данных статуса сделки.
 *
 * Идентификатор исполнителя на стороне площадки
 * @property string $platformBeneficiaryId
 *
 * Идентификатор выбранного исполнителем инструмента
 * @property string $beneficiaryPaymentToolId
 *
 * Идентификатор исполнителя на стороне площадки
 * @property string $platformPayerId
 *
 * Идентификатор выбранного исполнителем инструмента
 * @property string $payerPaymentToolId
 *
 * Подпись запроса
 * @property string $signature
 *
 * Дата формирования запроса в часовом поясе UTC+0
 * @property string $timestamp
 *
 */
class CardCreateResponse extends BaseResponse
{
    public $platformBeneficiaryId;
    public $beneficiaryPaymentToolId;
    public $platformPayerId;
    public $payerPaymentToolId;
    public $signature;
    public $timestamp;

    public function rules()
    {
        return [
            [['signature', 'timestamp'], 'required'],
            [
                [
                    'platformBeneficiaryId',
                    'beneficiaryPaymentToolId',
                    'platformPayerId',
                    'payerPaymentToolId',
                    'signature',
                    'timestamp',
                ],
                'string'
            ],
        ];
    }

    public function setAttributes($values, $safeOnly = true)
    {
        $this->rawResponseDataArray = $values;
        $propArray = [];
        foreach ($values as $key => $value) {
            $prop = lcfirst($key);
            if (property_exists($this, $prop)) {
                $propArray[$prop] = $value;
            }
        }
        parent::setAttributes($propArray, $safeOnly);
    }
}
