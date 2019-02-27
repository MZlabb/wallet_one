<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 03.10.18
 * Time: 9:07
 */

namespace WalletOne\requests;

use yii\base\Model;

abstract class BaseRequest extends Model implements W1RequestInterface
{
    public static $method;
    public static $endPoint;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return static::$method;
    }

    /**
     * @return string
     */
    public function getEndPoint(): string
    {
        return static::$endPoint;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = json_encode($this->prepareFields());
        return is_string($string) ? $string : '';
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        $propertyName = lcfirst($name);
        if (property_exists($this, $propertyName)) {
            return $this->$propertyName;
        }
        return parent::__get($name);
    }

    /**
     * @param array $fields
     * @param array $expand
     * @param bool $recursive
     * @return array
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true): array
    {
        $resultArray = [];
        foreach (parent::toArray() as $key => $value) {
            if ($value !== null) {
                $resultArray[ucfirst($key)] = $value;
            }
        }
        return $resultArray;
    }

    /**
     * Prepare fields name like W1 required for request
     *
     * @return array
     */
    protected function prepareFields(): array
    {
        $formFields = [];
        foreach ($this->toArray() as $key => $value) {
            if ($value !== null) {
                $formFields[ucfirst($key)] = $value;
            }
        }
        return $formFields;
    }
}
