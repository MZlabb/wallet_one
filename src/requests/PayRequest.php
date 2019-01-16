<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 02.10.18
 * Time: 16:41
 */

namespace WalletOne\requests;

/**
 * Данный запрос отправляется после регистрации сделки в системе W1.
 * При отправке запроса происходит перенаправление пользователя на страницу оплаты сделки.
 *
 * Идентификатор площадки
 * @property string $platformId
 *
 * Идентификатор сделки на стороне площадки
 * @property string $platformDealId
 *
 * Некоторые способы оплаты поддерживают передачу авторизационных данных из площадки, позволяя плательщику миновать
 * платежные страницы и сразу инициировать оплату.
 * @property string $authData
 *
 * Урл возврата пользователя
 * @property string $returnUrl
 *
 * Перейти сразу к добавлению нового инструмента оплаты. Происходит при передаче значения “True”.
 * @property string $redirectToCardAddition
 *
 * Способ оплаты. Перейти к добавлению/выбору инструмента оплаты конкретного способа.
 * Если не передан выбирается способ по умолчанию.
 * @property string $paymentTypeId
 *
 * Подпись запроса
 * @property string $signature
 *
 * Дата формирования запроса в часовом поясе UTC+0
 * @property string $timestamp
 *
 * Язык интерфейса платежных страниц. Доступны ru, en.
 * @property string $language
 *
 */
class PayRequest extends BaseRequest implements W1FormRequestInterface
{
    public $platformId;
    public $platformDealId;
    public $authData;
    public $returnUrl;
    public $redirectToCardAddition;
    public $paymentTypeId;
    public $signature;
    public $timestamp;
    public $language = 'en';

    public static $method = 'POST';
    public static $endPoint = '/deal/pay';

    public function rules()
    {
        return [
            [['platformId', 'platformDealId', 'returnUrl', 'signature', 'timestamp'], 'required'],
            [['authData', 'paymentTypeId'], 'string'],
            [['redirectToCardAddition'], 'string'],
            [['returnUrl'], 'url']
        ];
    }
}