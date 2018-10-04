<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 03.10.18
 * Time: 14:31
 */

namespace WalletOne\responses;

/**
 * Модель данных выплаты по исполнителю.
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
 * Идентификатор выплаты
 * @property string $payoutId
 *
 * Статус выплаты
 * @property string $payoutStateId
 *
 */

class PayoutResponse extends BaseResponse
{
    public $platformDealId;
    public $createDate;
    public $amount;
    public $currencyId;
    public $payoutId;
    public $payoutStateId;

    protected $listName = 'Payouts';
}
