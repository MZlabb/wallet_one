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
use WalletOne\requests\RequestTypesEnum;
use WalletOne\requests\W1RequestFactory;
use WalletOne\responses\DealResponse;
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
        //todo: create test
    }

    public function testDealComplete()
    {
        //todo: create test
    }

    public function testRemoveSupplierPaymentWay()
    {
        //todo: create test
    }

    public function testPrepareW1Callback()
    {

    }

    public function testRemoveCustomerPaymentWay()
    {
        //todo: create test
    }

    public function testGetConfig()
    {
        //todo: create test
    }

    public function testGetFormData()
    {
        //todo: create test
    }

    public function testGetPlatformPayoutTypes()
    {
        //todo: create test
    }

    public function testGetSupplierPaymentWayList()
    {
        //todo: create test
    }

    public function testDealCancel()
    {
        //todo: create test
    }

    public function testChangeSupplierPaymentWay()
    {
        //todo: create test
    }

    public function testGetSupplierPayoutList()
    {
        //todo: create test
    }

    public function testGetAllDealsBySupplier()
    {
        //todo: create test
    }

    public function testGetCustomerPaymentWayList()
    {
        //todo: create test
    }

    public function testGetAllCustomerRefunds()
    {
        //todo: create test
    }

    public function testGetCustomerPaymentWay()
    {
        //todo: create test
    }

    public function testDealsCompleteAll()
    {
        //todo: create test
    }

    public function testGetPlatformPaymentTypes()
    {
        //todo: create test
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \WalletOne\exceptions\W1ExecuteRequestException
     * @throws \WalletOne\exceptions\W1WrongParamException
     */
    public function testDealRegister()
    {
        $this->init();
        $this->prepareClientMock(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                DealResponses::getSingleResponse(true)
            )
        );

        /**
         * @var \WalletOne\requests\DealRegisterRequest $request
        */
        $request = W1RequestFactory::getRequest(RequestTypesEnum::DEAL_REGISTER_REQUEST);
        $request->setAttributes(\WalletOne\Test\testdata\DealRegisterRequestData::getResponseData());
        $response = $this->api->dealRegister($request);

        $this->assertInstanceOf(DealResponse::class, $response);
    }

    public function testChangeCustomerPaymentWay()
    {
        //todo: create test
    }

    public function testGetDealStatus()
    {
        //todo: create test
    }

    public function testGetSupplierPaymentWay()
    {
        //todo: create test
    }
}
