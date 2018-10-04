<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 04.10.18
 * Time: 10:34
 */

namespace WalletOne\responses;

use Generator;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class BaseResponse extends Model implements W1ResponseInterface
{
    protected $listName;
    protected $itemList = [];

    public function setAttributes($values, $safeOnly = true)
    {
        if (ArrayHelper::keyExists($this->listName, $values)) {
            $this->itemList = ArrayHelper::getValue($values, $this->listName, []);
            $values = ArrayHelper::getValue($values, [$this->listName, 0], []);
        } else {
            $this->itemList[] = $values;
        }

        $array = [];
        foreach ($values as $key => $value) {
            $array[lcfirst($key)] = $values;
        }
        parent::setAttributes($array, $safeOnly);
    }

    /**
     * @return Generator
     */
    public function getGenerator(): Generator
    {
        foreach ($this->itemList as $paymentMethodArray) {
            $this->setAttributes($paymentMethodArray);
            yield $this;
        }
    }
}