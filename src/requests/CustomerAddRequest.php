<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 02.10.18
 * Time: 16:41
 */

namespace WalletOne\requests;

/**
 * Данный запрос используется для изменения идентификатора карты заказчика по сделке.
 * Для выбора карты заказчик будет переадресован на соответствующую страницу в системе W1.
 *
 * Идентификатор площадки
 * @property string $platformId
 *
 * Идентификатор заказчика на стороне площадки
 * @property string $platformPayerId
 *
 * Номер телефона заказчика
 * @property string $phoneNumber
 *
 * Наименование заказчика (опционально)
 * @property string $title
 *
 * Урл возврата пользователя
 * @property string $returnUrl
 *
 * Перейти сразу к добавлению нового инструмента оплаты. Происходит при передаче значения “True”.
 * @property bool $redirectToPaymentToolAddition
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

class CustomerAddRequest extends BaseRequest implements W1FormRequestInterface
{
    public $platformId;
    public $platformPayerId;
    public $phoneNumber;
    public $title;
    public $returnUrl;
    public $redirectToPaymentToolAddition;
    public $paymentTypeId;
    public $signature;
    public $timestamp;
    public $language = 'en';

    public static $method = 'POST';
    public static $endPoint = '/v2/payer';

    public function rules()
    {
        return [
            [['platformId', 'platformPayerId', 'returnUrl', 'signature', 'timestamp', 'phoneNumber'], 'required'],
            [['authData', 'paymentTypeId'], 'string'],
            [['redirectToPaymentToolAddition'], 'boolean'],
            [['returnUrl'], 'url']
        ];
    }
}
