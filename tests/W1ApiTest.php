<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 12.10.18
 * Time: 8:26
 */

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use WalletOne\requests\BaseRequest;
use WalletOne\Test\testdata\DealResponses;
use WalletOne\W1Api;
use yii\helpers\ArrayHelper;

class W1ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var W1Api $api
     */
    private $api;

    private function prepareClientMock(Response $response)
    {
        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        Yii::$container->setSingleton(Client::class, function ($container, $params, $config) use ($handler) {
            $config = ArrayHelper::merge($config, ['handler' => $handler]);
            return new Client($config);
        });
    }

    private function init()
    {
        $this->api = new W1Api(
            [
                'platformId' => 'test_platform',
                'signatureKey' => '123456789',
                'isTestMode' => true,
            ]
        );
    }

    public function test__construct()
    {
        $this->api = new W1Api(
            [
                'platformId' => 'test_platform',
                'signatureKey' => '123456789',
                'isTestMode' => true,
            ]
        );
        $this->assertInstanceOf(W1Api::class, $this->api);
    }

    public function testDealConfirm()
    {

    }

    public function testDealComplete()
    {

    }

    public function testRemoveSupplierPaymentWay()
    {

    }

    public function testPrepareW1Callback()
    {

    }

    public function testRemoveCustomerPaymentWay()
    {

    }

    public function testGetConfig()
    {

    }

    public function testGetFormData()
    {

    }

    public function testGetPlatformPayoutTypes()
    {

    }

    public function testGetSupplierPaymentWayList()
    {

    }

    public function testDealCancel()
    {

    }

    public function testChangeSupplierPaymentWay()
    {

    }

    public function testGetSupplierPayoutList()
    {

    }

    public function testGetAllDealsBySupplier()
    {

    }

    public function testGetCustomerPaymentWayList()
    {

    }

    public function testGetAllCustomerRefunds()
    {

    }

    public function testGetCustomerPaymentWay()
    {

    }

    public function testDealsCompleteAll()
    {

    }

    public function testGetPlatformPaymentTypes()
    {

    }

    public function testDealRegister()
    {
        $this->init();
        $this->prepareClientMock(new Response(200, ['Content-Type' => 'application/json'], DealResponses::getSingleResponse(true)));
        /**
         * @var \WalletOne\requests\DealRegisterRequest $request
        */
        $request = BaseRequest::getRequest(BaseRequest::DEAL_REGISTER_REQUEST);
        $request->setAttributes(\WalletOne\Test\testdata\DealRegisterRequestData::getResponseData());
        $response = $this->api->dealRegister($request);

        $this->assertInstanceOf(\WalletOne\responses\DealResponse::class, $response);
    }

    public function testChangeCustomerPaymentWay()
    {

    }

    public function testGetDealStatus()
    {

    }

    public function testGetSupplierPaymentWay()
    {

    }
}
