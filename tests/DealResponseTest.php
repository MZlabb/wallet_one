<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 05.10.18
 * Time: 10:13
 */

use WalletOne\responses\DealResponse;
use WalletOne\Test\testdata\DealResponses;
use WalletOne\W1Config;

class DealResponseTest extends PHPUnit_Framework_TestCase
{
    public function testSetAttributes()
    {
        $config = self::getConfig();
        $dealResponse = new DealResponse();
        $responseString = DealResponses::getSingleResponse();
        $dealResponse->setAttributes($responseString);
        $this->assertAttributeEquals('test_platform', 'platformDealId', $dealResponse);
        $this->assertAttributeEquals('Canceling', 'dealStateId', $dealResponse);
        $this->assertAttributeEquals('2015-01-07T21:45:33', 'createDate', $dealResponse);
        $this->assertAttributeEquals('2015-01-07T21:45:33', 'updateDate', $dealResponse);
        $this->assertAttributeEquals('2015-06-07T21:45:33', 'expireDate', $dealResponse);
        $this->assertAttributeEquals(10.00, 'amount', $dealResponse);
        $this->assertAttributeEquals(643, 'currencyId', $dealResponse);
        $this->assertAttributeEquals(1, 'platformPayerId', $dealResponse);
        $this->assertAttributeEquals(123, 'payerPhoneNumber', $dealResponse);
        $this->assertAttributeEquals(1, 'payerPaymentToolId', $dealResponse);
        $this->assertAttributeEquals(1, 'platformBeneficiaryId', $dealResponse);
        $this->assertAttributeEquals(1, 'beneficiaryPaymentToolId', $dealResponse);
        $this->assertAttributeEquals('Оплата сделки', 'shortDescription', $dealResponse);
        $this->assertAttributeEquals('Полное описание', 'fullDescription', $dealResponse);
        $this->assertAttributeEquals('Deferred', 'dealTypeId', $dealResponse);
        $this->assertAttributeEquals('string', 'lastErrorCode', $dealResponse);
        $this->assertAttributeEquals('string', 'lastErrorMessage', $dealResponse);
    }

    public function testSetAttributesMultiple()
    {
        $config = self::getConfig();
        $dealResponse = new DealResponse();
        $responseString = DealResponses::getMultiResponse();
        $dealResponse->setAttributes($responseString);
        $this->assertAttributeEquals('test_platform', 'platformDealId', $dealResponse);
        $this->assertAttributeEquals('Created', 'dealStateId', $dealResponse);
        $this->assertAttributeEquals('2017-09-11T09:22:15.056Z', 'createDate', $dealResponse);
        $this->assertAttributeEquals('2017-09-11T09:22:15.056Z', 'updateDate', $dealResponse);
        $this->assertAttributeEquals('2017-09-11T09:22:15.056Z', 'expireDate', $dealResponse);
        $this->assertAttributeEquals(10.00, 'amount', $dealResponse);
        $this->assertAttributeEquals(643, 'currencyId', $dealResponse);
        $this->assertAttributeEquals(1, 'platformPayerId', $dealResponse);
        $this->assertAttributeEquals(123, 'payerPhoneNumber', $dealResponse);
        $this->assertAttributeEquals(1, 'payerPaymentToolId', $dealResponse);
        $this->assertAttributeEquals('string', 'platformBeneficiaryId', $dealResponse);
        $this->assertAttributeEquals(0, 'beneficiaryPaymentToolId', $dealResponse);
        $this->assertAttributeEquals('string', 'shortDescription', $dealResponse);
        $this->assertAttributeEquals('string', 'fullDescription', $dealResponse);
        $this->assertAttributeEquals('Instant', 'dealTypeId', $dealResponse);
        $this->assertAttributeEquals('string', 'lastErrorCode', $dealResponse);
        $this->assertAttributeEquals('string', 'lastErrorMessage', $dealResponse);

        $objArray = [];
        foreach ($dealResponse->getGenerator() as $item) {
            $objArray[] = clone($item);
        }
        $this->assertEquals(3, count($objArray));

        $this->assertAttributeEquals(10.00, 'amount', $objArray[0]);
        $this->assertAttributeEquals(11.00, 'amount', $objArray[1]);
        $this->assertAttributeEquals(12.00, 'amount', $objArray[2]);
    }

    public function testSetAttributesMultiple2()
    {
        $config = self::getConfig();
        $dealResponse = new DealResponse();
        $responseString = DealResponses::getMulti2Response();
        $dealResponse->setAttributes($responseString);
        $this->assertAttributeEquals('test_platform', 'platformDealId', $dealResponse);
        $this->assertAttributeEquals('Created', 'dealStateId', $dealResponse);
        $this->assertAttributeEquals('2017-09-11T09:22:15.056Z', 'createDate', $dealResponse);
        $this->assertAttributeEquals('2017-09-11T09:22:15.056Z', 'updateDate', $dealResponse);
        $this->assertAttributeEquals('2017-09-11T09:22:15.056Z', 'expireDate', $dealResponse);
        $this->assertAttributeEquals(10.00, 'amount', $dealResponse);
        $this->assertAttributeEquals(643, 'currencyId', $dealResponse);
        $this->assertAttributeEquals(1, 'platformPayerId', $dealResponse);
        $this->assertAttributeEquals(123, 'payerPhoneNumber', $dealResponse);
        $this->assertAttributeEquals(1, 'payerPaymentToolId', $dealResponse);
        $this->assertAttributeEquals('string', 'platformBeneficiaryId', $dealResponse);
        $this->assertAttributeEquals(0, 'beneficiaryPaymentToolId', $dealResponse);
        $this->assertAttributeEquals('string', 'shortDescription', $dealResponse);
        $this->assertAttributeEquals('string', 'fullDescription', $dealResponse);
        $this->assertAttributeEquals('Instant', 'dealTypeId', $dealResponse);
        $this->assertAttributeEquals('string', 'lastErrorCode', $dealResponse);
        $this->assertAttributeEquals('string', 'lastErrorMessage', $dealResponse);

        $objArray = [];
        foreach ($dealResponse->getGenerator() as $item) {
            $objArray[] = clone($item);
        }
        $this->assertEquals(3, count($objArray));

        $this->assertAttributeEquals(10.00, 'amount', $objArray[0]);
        $this->assertAttributeEquals(11.00, 'amount', $objArray[1]);
        $this->assertAttributeEquals(12.00, 'amount', $objArray[2]);
    }

    private static function getConfig()
    {
        include_once __DIR__.'/../vendor/yiisoft/yii2/Yii.php';
        return $config = new W1Config(
            [
                'platformId' => 'test_platform',
                'signatureKey' => '123456789',
                'isTestMode' => true,
            ]
        );
    }
}
