<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 03.10.18
 * Time: 14:31
 */

namespace WalletOne\responses;

/**
 * Модель данных возврата заказчику.
 *
 * Идентификатор сделки
 * @property string $platformDealId
 *
 * Дата создания выплаты
 * @property string $createDate
 *
 * Сумма выплаты
 * @property string $amount
 *
 * Код валюты
 * @property string $currencyId
 *
 * Идентификатор возврата
 * @property string $refundId
 *
 * Статус возврата
 * @property string $refundStateId
 *
 */

class RefundResponse extends BaseResponse
{
    public $platformDealId;
    public $createDate;
    public $amount;
    public $currencyId;
    public $refundId;
    public $refundStateId;

    protected $listName = 'Refunds';
}