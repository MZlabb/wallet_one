<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 28.11.18
 * Time: 14:35
 */

namespace WalletOne\responses;

use WalletOne\exceptions\W1WrongParamException;
use WalletOne\W1Config;
use yii\base\Model;
use yii\helpers\ArrayHelper;

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
class CardCreateResponse extends Model
{
    public $platformBeneficiaryId;
    public $beneficiaryPaymentToolId;
    public $platformPayerId;
    public $payerPaymentToolId;
    public $signature;
    public $timestamp;

    private $data;
    private $conf;

    /**
     * CardCreateResponse constructor.
     * @param array $data
     * @param W1Config $conf
     * @param array $config
     * @throws W1WrongParamException
     */
    public function __construct(array $data, W1Config $conf, array $config = [])
    {
        parent::__construct($config);
        $this->data = $data;
        $this->conf = $conf;
        $this->setAttributes($this->data);
        if (!$this->validate()) {
            throw new W1WrongParamException('Wrong response param: ' . print_r($this->getErrors(), true));
        }
    }

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
            ['signature', 'validateSign']
        ];
    }

    public function setAttributes($values, $safeOnly = true)
    {
        $propArray = [];
        foreach ($values as $key => $value) {
            $prop = lcfirst($key);
            if (property_exists($this, $prop)) {
                $propArray[$prop] = $value;
            }
        }
        parent::setAttributes($propArray, $safeOnly);
    }

    /**
     * @param $attribute
     */
    public function validateSign($attribute)
    {
        if ($this->$attribute !== $this->generateSign()) {
            $this->addError($attribute, 'Sign is not valid');
        }
    }

    /**
     * @return string
     */
    private function generateSign(): string
    {
        $params = $this->data;
        ArrayHelper::remove($params, 'Signature');
        uksort($params, "strcasecmp");
        $request = "";
        foreach ($params as $k => $v) {
            $v = iconv("windows-1251", "utf-8", $v);
            $request .= $v;
        }
        $request .= $this->conf->signatureKey;
        $hash = ($this->conf->hashFunction)($request);
        return base64_encode(pack("H*", $hash));
    }

}