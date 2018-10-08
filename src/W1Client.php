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
    const RESP_TYPE_DEAL = 'deal';
    const RESP_TYPE_PAYMENT_METHOD = 'payment_method';
    const RESP_TYPE_PAYOUT = 'payout';
    const RESP_TYPE_REFUND = 'refund';

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
     * @param string $responseTypeId
     * @return DealResponse|PaymentMethodResponse|PayoutResponse|RefundResponse|W1ResponseInterface
     * @throws W1WrongParamException
     */
    private static function getResponseObj(string $responseTypeId)
    {
        switch ($responseTypeId) {
            case self::RESP_TYPE_DEAL:
                return new DealResponse();
            case self::RESP_TYPE_PAYMENT_METHOD:
                return new PaymentMethodResponse();
            case self::RESP_TYPE_PAYOUT:
                return new PayoutResponse();
                break;
            case self::RESP_TYPE_REFUND:
                return new RefundResponse();
                break;
            default:
                throw new W1WrongParamException('Passed wrong response ID');
        }
    }

    /**
     * Prepare and execute request to W1 API
     *
     * @param string $url
     * @param string $method
     * @param string $body
     * @throws W1ExecuteRequestException
     */
    public function execute(string $url, string $method, string $body = '')
    {
        $timeStamp = self::createTimeStamp();
        $httpRequest = new Request(
            $method,
            $url,
            [
                'X-Wallet-PlatformId' => $this->conf->platformId,
                'X-Wallet-Signature' => $this->createSignature($url, $timeStamp, $body),
                'X-Wallet-Timestamp' => $timeStamp
            ],
            $body
        );

        try {
            /**
             * @var Client $client
            */
            $client = Yii::$container->get(Client::class, ['base_uri' => $this->conf->baseW1Url]);
            $response = $client->send($httpRequest);
        } catch (RequestException $e) {
            throw new W1ExecuteRequestException($e);
        }
        $body = $response->getBody();
        $this->responseString = (string)$body;
    }

    /**
     * @return string
     */
    public function getResponseString()
    {
        return $this->responseString;
    }

    /**
     * @return array|mixed
     */
    public function getResponseArray()
    {
        $array = json_decode($this->responseString, true);
        return is_array($array) ? $array : [];
    }

    /**
     * @param string $responseTypeId
     * @return DealResponse|PaymentMethodResponse|PayoutResponse|RefundResponse|W1ResponseInterface
     * @throws W1WrongParamException
     */
    public function getResponseObject(string $responseTypeId)
    {
        $object = self::getResponseObj($responseTypeId);
        $object->setAttributes($this->getResponseArray());
        return $object;
    }

    /**
     * @return string
     */
    public static function createTimeStamp()
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
        $paramsString = $url . $timeStamp . $requestBody . $this->conf->signatureKey;
        return base64_encode(($this->conf->hashFunction)($paramsString));
    }
}