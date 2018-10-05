<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 04.10.18
 * Time: 16:04
 */

namespace WalletOne\callback;


use WalletOne\exceptions\W1RuntimeException;
use WalletOne\W1Config;
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

class Callback extends Model
{
    public $platformDealId;
    public $dealStateId;
    public $errorCode;
    public $errorDescription;
    public $signature;
    public $timestamp;

    private $conf;

    public function __construct(W1Config $conf, array $config = [])
    {
        parent::__construct($config);
        $this->conf = $conf;
    }

    /**
     * @param array $values
     * @param bool $safeOnly
     * @throws W1RuntimeException
     */
    public function setAttributes($values, $safeOnly = true)
    {
        $this->validateSignature($values);
        $array = [];
        foreach ($values as $key => $value) {
            $array[lcfirst($key)] = $values;
        }
        parent::setAttributes($array, $safeOnly);
    }

    /**
     * @param array $params
     * @throws W1RuntimeException
     */
    private function validateSignature(array $params)
    {
        $signature = ArrayHelper::getValue($params, 'signature', '');
        ArrayHelper::remove($params, 'signature');
        ksort($params);
        $paramsString = '';
        array_walk(
            $params,
            function ($value) use (&$paramsString) {
                $paramsString .= $value;
            }
        );
        $calculatedSignature = base64_encode($this->conf->hashFunction($paramsString . $this->conf->signatureKey));
        if ($signature != $calculatedSignature) {
            throw new W1RuntimeException('Wrong signature given');
        }
    }
}
