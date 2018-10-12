<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 08.10.18
 * Time: 12:50
 */

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use WalletOne\responses\DealResponse;
use WalletOne\Test\testdata\DealResponses;
use WalletOne\W1Client;
use WalletOne\W1Config;
use yii\helpers\ArrayHelper;

class W1ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var W1Config $config
    */
    private $config;

    public function testCreateClientObject()
    {
        $this->init();
        $client = new W1Client($this->config);
        $this->assertInstanceOf(\WalletOne\W1Client::class, $client);
    }

    public function testExecute()
    {
        $this->init();
        $client = new W1Client($this->config);
        $client->execute('/api/v3/deals', 'POST');
        $this->assertNotEmpty($client->getResponseString());
        $this->assertInstanceOf(DealResponse::class, $client->getResponseObject(W1Client::RESP_TYPE_DEAL));
    }

    public function testGetResponseString()
    {
        $this->init();
        $client = new W1Client($this->config);
        $client->execute('/api/v3/deals', 'POST');
        $this->assertNotEmpty($client->getResponseString());
        $this->assertEquals(DealResponses::getSingleResponse(true), $client->getResponseString());
    }

    public function testGetResponseArray()
    {
        $this->init();
        $client = new W1Client($this->config);
        $client->execute('/api/v3/deals', 'POST');
        $this->assertNotEmpty($client->getResponseArray());
        $this->assertEquals(DealResponses::getSingleResponse(), $client->getResponseArray());
    }

    public function testGetResponseObject()
    {
        $this->init();
        $client = new W1Client($this->config);
        $client->execute('/api/v3/deals', 'POST');
        $this->assertNotEmpty($client->getResponseString());
        $this->assertInstanceOf(DealResponse::class, $client->getResponseObject(W1Client::RESP_TYPE_DEAL));
    }

    public function testCreateTimeStamp()
    {
        $this->init();
        $timeStamp = W1Client::createTimeStamp();
        $this->assertTrue(date('Y-m-d\TH:i:s', strtotime($timeStamp)) == $timeStamp);
    }

    private function init()
    {
        $this->config = new W1Config(
            [
                'platformId' => 'test_platform',
                'signatureKey' => '123456789',
                'isTestMode' => true,
            ]
        );

        $mock = new MockHandler(
            [
                new Response(200, ['Content-Type' => 'application/json'], DealResponses::getSingleResponse(true)),
            ]
        );
        $handler = HandlerStack::create($mock);
        Yii::$container->setSingleton(Client::class, function ($container, $params, $config) use ($handler) {
            $config = ArrayHelper::merge($config, ['handler' => $handler]);
            return new Client($config);
        });
    }
}
