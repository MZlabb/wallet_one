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
    public static function getRequest(string $requestId, array $params): W1RequestInterface
    {
        if(class_exists('WalletOne\\requests\\'.$requestId)) {
            return new $requestId($params);
        }
        throw new W1WrongParamException('Wrong request Id passed');
    }

    public function getMethod(): string
    {
        return static::$method;
    }

    public function getEndPoint(): string
    {
        return static::$endPoint;
    }

    public function fields()
    {
        $fields = $this->attributes();
        return array_combine($fields, array_map('ucwords', $fields));
    }

    public function getFormFields(): array
    {
        $formFields = $this->toArray();
        foreach ($formFields as $key => $value) {
            if ($value === null) {
                unset($formFields[$key]);
            }
        }
        return $formFields;
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}

