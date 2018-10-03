<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 03.10.18
 * Time: 14:31
 */

namespace WalletOne\responses;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Модель данных платежного метода.
 *
 * Идентификатор платежного метода
 * @property string $paymentToolId
 *
 * Тип платежного метода
 * @property string $paymentTypeId
 *
 * @property string $mask
 *
 */

class PaymentMethodResponse extends Model
{
    public $paymentToolId;
    public $paymentTypeId;
    public $mask;

    private $paymentMethodsList = [];

    public function setAttributes($values, $safeOnly = true)
    {
        if (ArrayHelper::keyExists('PaymentTools', $values)) {
            $this->paymentMethodsList = ArrayHelper::getValue($values, 'PaymentTools', []);
            $values = ArrayHelper::getValue($values, 'PaymentTools.0', []);
        } else {
            $this->paymentMethodsList[] = $values;
        }

        $array = [];
        foreach ($values as $key => $value) {
            $array[lcfirst($key)] = $values;
        }
        parent::setAttributes($array, $safeOnly);
    }

    /**
     * @return \Generator
     */
    public function getGenerator()
    {
        foreach ($this->paymentMethodsList as $paymentMethodArray) {
            $this->setAttributes($paymentMethodArray);
            yield $this;
        }
    }
}