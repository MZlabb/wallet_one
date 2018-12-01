<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 03.10.18
 * Time: 9:07
 */

namespace WalletOne\requests;


use WalletOne\exceptions\W1WrongParamException;
use yii\base\Model;

class BaseRequest extends Model implements W1RequestInterface
{
    const PAY_REQUEST = 'PayRequest';
    const CUSTOMER_ADD_REQUEST = 'CustomerAddRequest';
    const SUPPLIER_ADD_REQUEST = 'SupplierAddRequest';
    const DEAL_REGISTER_REQUEST = 'DealRegisterRequest';

    public static $method;
    public static $endPoint;

    /**
     * @param $requestId
     * @param array $params
     * @return W1RequestInterface
     * @throws W1WrongParamException
     */
    public static function getRequest(string $requestId, array $params = []): W1RequestInterface
    {
        $requestClass = 'WalletOne\\requests\\'.$requestId;
        if(class_exists($requestClass)) {
            return new $requestClass($params);
        }
        throw new W1WrongParamException('Wrong request Id passed');
    }

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
     * @return array
     */
    public function getFormFields(): array
    {
        $formFields = [];
        foreach ($this->toArray() as $key => $value) {
            if ($value !== null) {
                $formFields[ucfirst($key)] = $value;
            }
        }
        return $formFields;
    }

    public function __toString()
    {
        $string = json_encode($this->getFormFields());
        return $string ?? '';
    }

    public function __get($name)
    {
        $propertyName = lcfirst($name);
        if (property_exists($this, $propertyName)) {
            return $this->$propertyName;
        }
        return parent::__get($name);
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $resultArray = [];
        foreach (parent::toArray() as $key => $value) {
            if ($value !== null) {
                $resultArray[ucfirst($key)] = $value;
            }
        }
        return $resultArray;
    }
}
