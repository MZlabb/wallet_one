<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 08.10.18
 * Time: 10:09
 */

use WalletOne\requests\RequestTypesEnum;
use WalletOne\requests\W1RequestFactory;

class BaseRequestTest extends PHPUnit_Framework_TestCase
{

    /**
     * @throws \WalletOne\exceptions\W1WrongParamException
     */
    public function testGetRequest()
    {
        $customerAddRequest = W1RequestFactory::getRequest(RequestTypesEnum::CUSTOMER_ADD_REQUEST);
        $this->assertInstanceOf(\WalletOne\requests\CustomerAddRequest::class, $customerAddRequest);

        $payRequest = W1RequestFactory::getRequest(RequestTypesEnum::PAY_REQUEST);
        $this->assertInstanceOf(\WalletOne\requests\PayRequest::class, $payRequest);

        $request = W1RequestFactory::getRequest(RequestTypesEnum::DEAL_REGISTER_REQUEST);
        $this->assertInstanceOf(\WalletOne\requests\DealRegisterRequest::class, $request);

        $request = W1RequestFactory::getRequest(RequestTypesEnum::SUPPLIER_ADD_REQUEST);
        $this->assertInstanceOf(\WalletOne\requests\SupplierAddRequest::class, $request);
    }
}
