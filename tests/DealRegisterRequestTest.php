<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 08.10.18
 * Time: 10:28
 */

use WalletOne\requests\RequestTypesEnum;
use WalletOne\requests\W1RequestFactory;
use WalletOne\Test\testdata\DealRegisterRequestData;

class DealRegisterRequestTest extends PHPUnit_Framework_TestCase
{
    public function testCreateObject()
    {
        $request = W1RequestFactory::getRequest(
            RequestTypesEnum::DEAL_REGISTER_REQUEST,
            DealRegisterRequestData::getResponseData()
        );
        $this->assertAttributeEquals('test_platform', 'platformDealId', $request);
        $this->assertAttributeEquals('1', 'platformPayerId', $request);
        $this->assertAttributeEmpty('payerPaymentToolId', $request);
        $this->assertAttributeEquals('+39089656986', 'payerPhoneNumber', $request);
        $this->assertAttributeEquals('3', 'platformBeneficiaryId', $request);
        $this->assertAttributeEquals('2', 'beneficiaryPaymentToolId', $request);
        $this->assertAttributeEquals(10.00, 'amount', $request);
        $this->assertAttributeEquals(643, 'currencyId', $request);
        $this->assertAttributeEquals('test', 'shortDescription', $request);
        $this->assertAttributeEquals('test', 'fullDescription', $request);
    }

    public function testGetMethod()
    {
        $request = W1RequestFactory::getRequest(
            RequestTypesEnum::DEAL_REGISTER_REQUEST,
            DealRegisterRequestData::getResponseData()
        );
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testEndPoint()
    {
        $request = W1RequestFactory::getRequest(
            RequestTypesEnum::DEAL_REGISTER_REQUEST,
            DealRegisterRequestData::getResponseData()
        );
        $this->assertEquals('/api/v3/deals', $request->getEndPoint());
    }
/*
    public function testGetFormFields()
    {
        $request = W1RequestFactory::getRequest(
            RequestTypesEnum::DEAL_REGISTER_REQUEST,
            DealRegisterRequestData::getResponseData()
        );
        $fieldsArray = $request->getFormFields();
        $this->assertArrayNotHasKey('payerPaymentToolId', $fieldsArray);
        $this->assertArrayHasKey('PlatformDealId', $fieldsArray);
        $this->assertArrayHasKey('Amount', $fieldsArray);
        $this->assertEquals(10, $fieldsArray['Amount']);
    }
*/

    public function test__toString()
    {
        $request = W1RequestFactory::getRequest(
            RequestTypesEnum::DEAL_REGISTER_REQUEST,
            DealRegisterRequestData::getResponseData()
        );
        $this->assertEquals(
            '{"PlatformDealId":"test_platform","PlatformPayerId":"1","PayerPhoneNumber":"+39089656986","PlatformBeneficiaryId":"3","BeneficiaryPaymentToolId":"2","Amount":10,"CurrencyId":643,"ShortDescription":"test","FullDescription":"test","DeferPayout":true}',
            (string)$request
        );
    }
}
