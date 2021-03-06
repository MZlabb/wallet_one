<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 04.10.18
 * Time: 10:53
 */

namespace WalletOne;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use WalletOne\exceptions\W1ExecuteRequestException;
use WalletOne\exceptions\W1WrongParamException;
use WalletOne\responses\DealResponse;
use WalletOne\responses\PaymentMethodResponse;
use WalletOne\responses\PayoutResponse;
use WalletOne\responses\RefundResponse;
use WalletOne\responses\ResponseFactory;
use WalletOne\responses\W1ResponseInterface;
use Yii;

/**
 * Property contain the raw string of response
 * @property string $responseString
 *
 * Property contain the array after json_decode responseString
 * @property string $responseArray
 *
 *
*/

class W1Client
{
    /**
     * @var W1Config $conf
     */
    private $conf;

    /**
     * Variable for storing of request result
     * @var string $responseString
     */
    private $responseString = '';


    public function __construct(W1Config $config)
    {
        $this->conf = $config;
    }

    /**
     * Prepare and execute request to W1 API
     *
     * @param string $url
     * @param string $method
     * @param string $body
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function execute(string $url, string $method, string $body = '')
    {
        $fullUrl = $this->conf->baseW1Url . $url;
        $timeStamp = self::createTimeStamp();
        $httpRequest = new Request(
            $method,
            $fullUrl,
            [
                'Content-Type' => 'application/json',
                'X-Wallet-PlatformId' => $this->conf->platformId,
                'X-Wallet-Signature' => $this->createSignature($fullUrl, $timeStamp, $body),
                'X-Wallet-Timestamp' => $timeStamp
            ],
            $body
        );

        try {
            /**
             * @var Client $client
            */
            $client = Yii::$container->get(Client::class);
            $response = $client->send($httpRequest);
        } catch (RequestException $e) {
            throw new W1ExecuteRequestException($e);
        }
        $this->responseString = (string)$response->getBody();
    }

    /**
     * @return string
     */
    public function getResponseString(): string
    {
        return $this->responseString;
    }

    /**
     * @return array|mixed
     */
    public function getResponseArray()
    {
        $array = json_decode($this->responseString, true);
        return \is_array($array) ? $array : [];
    }

    /**
     * @param string $responseTypeId
     * @return DealResponse|PaymentMethodResponse|PayoutResponse|RefundResponse|W1ResponseInterface
     * @throws W1WrongParamException
     */
    public function getResponseObject(string $responseTypeId)
    {
        return ResponseFactory::createResponse($responseTypeId, $this->getResponseArray());
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function createTimeStamp(): string
    {
        $timeZone = new \DateTimeZone(W1Config::TIMESTAMP_TIME_ZONE);
        $currentDateTime = new \DateTime('now', $timeZone);
        return $currentDateTime->format('Y-m-d\TH:i:s');
    }

    /**
     * @param string $url
     * @param string $timeStamp
     * @param string $requestBody
     * @return string
     */
    private function createSignature(string $url, string $timeStamp, string $requestBody): string
    {
        $paramsString = iconv(
            'windows-1251',
            'utf-8',
            $url . $timeStamp . $requestBody . $this->conf->signatureKey
        );
        $hash = ($this->conf->hashFunction)($paramsString);
        return base64_encode(pack('H*', $hash));
    }
}
