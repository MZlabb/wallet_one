<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 04.10.18
 * Time: 16:04
 */

namespace WalletOne\callback;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Модель данных callback запроса от W1.
 *
 * Идентификатор сделки
 * @property string $platformDealId
 *
 * Статус сделки
 * @property string $dealStateId
 *
 * Код ошибки (для неуспешных платежей)
 * @property string $errorCode
 *
 * Описание ошибки (для неуспешных платежей)
 * @property string $errorDescription
 *
 * Подпись запроса
 * @property string $signature
 *
 * Дата формирования запроса в часовом поясе UTC+0
 * @property string $timestamp
*/

class CallbackObj extends Model
{
    public $platformDealId;
    public $dealStateId;
    public $errorCode;
    public $errorDescription;
    public $signature;
    public $timestamp;

    public function rules()
    {
        $rules = [
            [
                [
                    'platformDealId',
                    'dealStateId',
                    'errorCode',
                    'errorDescription',
                    'signature',
                    'timestamp',
                ],
                'string'
            ],
            [
                [
                    'platformDealId',
                    'signature',
                    'timestamp',
                ],
                'required'
            ],
        ];
        return ArrayHelper::merge($rules, parent::rules());
    }

    /**
     * @param array $values
     * @param bool $safeOnly
     */
    public function setAttributes($values, $safeOnly = true)
    {
        $array = [];
        foreach ($values as $key => $value) {
            $array[lcfirst($key)] = $value;
        }
        parent::setAttributes($array, $safeOnly);
    }

    /**
     * ToDo: create validation of callback signature
    */
}
