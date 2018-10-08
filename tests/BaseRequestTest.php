<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 08.10.18
 * Time: 10:09
 */

use WalletOne\requests\BaseRequest;

class BaseRequestTest extends PHPUnit_Framework_TestCase
{

    public function testGetRequest()
    {
        $customerAddRequest = BaseRequest::getRequest(BaseRequest::CUSTOMER_ADD_REQUEST);
        $this->assertInstanceOf(\WalletOne\requests\CustomerAddRequest::class, $customerAddRequest);

        $payRequest = BaseRequest::getRequest(BaseRequest::PAY_REQUEST);
        $this->assertInstanceOf(\WalletOne\requests\PayRequest::class, $payRequest);

        $request = BaseRequest::getRequest(BaseRequest::DEAL_REGISTER_REQUEST);
        $this->assertInstanceOf(\WalletOne\requests\DealRegisterRequest::class, $request);

        $request = BaseRequest::getRequest(BaseRequest::SUPPLIER_ADD_REQUEST);
        $this->assertInstanceOf(\WalletOne\requests\SupplierAddRequest::class, $request);
    }
}
