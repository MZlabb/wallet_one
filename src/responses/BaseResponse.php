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
    const FIRST_ITEM = '0';

    protected $rawResponseDataArray = [];
    /**
     * name of response collection
     */
    protected $listName;
    protected $itemList = [];

    /**
     * setup model attributes from response data.
     * If response has collection of data - method store data into array and fullfill model with first
     * item of collection.
     * If in method passed 1 item - only model will be fulfilled
     *
     * @param array $values
     * @param bool $safeOnly
     */
    public function setAttributes($values, $safeOnly = true)
    {
        $this->rawResponseDataArray = $values;
        if (ArrayHelper::keyExists($this->listName, $values)) {
            $this->itemList = ArrayHelper::getValue($values, $this->listName, []);
            $values = ArrayHelper::getValue($values, [$this->listName, self::FIRST_ITEM], []);
        } else {
            $this->itemList[] = $values;
        }

        $array = [];
        foreach ($values as $key => $value) {
            $array[lcfirst($key)] = $value;
        }
        parent::setAttributes($array, $safeOnly);
    }

    /**
     * return each item of collection as object
     *
     * @return Generator
     */
    public function getGenerator(): Generator
    {
        foreach ($this->itemList as $item) {
            $this->setAttributes($item);
            yield $this;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = json_encode($this->rawResponseDataArray);
        return is_string($string) ? $string : '';
    }
}
