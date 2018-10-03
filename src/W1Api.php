<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 02.10.18
 * Time: 14:45
 */

namespace WalletOne;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use WalletOne\exceptions\W1ExecuteRequestException;
use WalletOne\exceptions\W1WrongParamException;
use WalletOne\requests\DealRegisterRequest;
use WalletOne\requests\W1FormRequestInterface;
use WalletOne\responses\DealResponse;
use WalletOne\responses\PaymentMethodResponse;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * @property Callable $hashFunction
 */
class W1Api extends BaseObject
{
    const TIMESTAMP_TIME_ZONE = '+0000';

    const END_POINT = 'https://api.w1.ru/p2p/';
    const TEST_END_POINT = 'https://api.dev.walletone.com/p2p/';

    public $platformId;
    public $signatureKey;

    private $baseW1Url;

    public $hashFunction;

    public function __construct(array $config = [], $testMode = false)
    {
        parent::__construct($config);
        $this->baseW1Url = $testMode ? self::END_POINT : self::TEST_END_POINT;
        $this->hashFunction = function (string $string) {
            return md5($string);
        };
    }

    /**
     * @param W1FormRequestInterface $request
     * @return mixed
     * @throws W1WrongParamException
     */
    public function getFormData(W1FormRequestInterface $request): array
    {
        $request->platformId = $this->platformId;
        $request->timestamp = $this->createTimeStamp();
        $this->createSignatureForForm($request);
        if (!$request->validate()) {
            $errorsString = print_r($request->getErrors(), true);
            throw new W1WrongParamException($errorsString);
        }
        return $request->toArray();
    }

    /**
     * @param DealRegisterRequest $request
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dealRegister(DealRegisterRequest $request)
    {
        if (!$request->validate()) {
            $errorsString = print_r($request->getErrors(), true);
            throw new W1WrongParamException($errorsString);
        }
        $httpRequest = $this->getHttpRequest($request->getEndPoint(), $request->getMethod(), (string)$request);
        $responseString = $this->sendRequest($httpRequest);
        return $this->prepareDealResponse($responseString);
    }

    /**
     * Завершение сделки
     *
     * @param string $dealId
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dealComplete(string $dealId)
    {
        $url = "/api/v3/deals/{$dealId}/complete";
        $httpRequest = $this->getHttpRequest($url, 'PUT');
        $responseString = $this->sendRequest($httpRequest);
        return $this->prepareDealResponse($responseString);
    }

    /**
     * Подтверждение сделки
     *
     * @param string $dealId
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dealConfirm(string $dealId)
    {
        $url = "/api/v3/deals/{$dealId}/confirm";
        $httpRequest = $this->getHttpRequest($url, 'PUT');
        $responseString = $this->sendRequest($httpRequest);
        return $this->prepareDealResponse($responseString);
    }

    /**
     * Отмена сделки
     *
     * @param string $dealId
     * @param bool $returnMoneyWithCommission
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dealCancel(string $dealId, bool $returnMoneyWithCommission = false)
    {
        $body = json_encode(['WithCommission' => $returnMoneyWithCommission]);
        $url = "/api/v3/deals/{$dealId}/cancel";
        $httpRequest = $this->getHttpRequest($url, 'PUT', $body);
        $responseString = $this->sendRequest($httpRequest);
        return $this->prepareDealResponse($responseString);
    }

    /**
     * Получение статуса сделки
     *
     * @param string $dealId
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDealStatus(string $dealId)
    {
        $url = "/api/v3/deals/{$dealId}";
        $httpRequest = $this->getHttpRequest($url, 'GET');
        $responseString = $this->sendRequest($httpRequest);
        return $this->prepareDealResponse($responseString);
    }

    /**
     * Изменение инструмента исполнителя по сделке
     *
     * @param string $dealId
     * @param string $paymentToolId
     * @param bool $autoComplete
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function changeSupplierPaymentWay(string $dealId, string $paymentToolId, bool $autoComplete = false)
    {
        $body = json_encode(
            [
                'PaymentToolId' => $paymentToolId,
                'AutoComplete' => $autoComplete ? 1 : 0
            ]
        );
        $url = "api/v3/deals/{$dealId}/beneficiary/tool";
        $httpRequest = $this->getHttpRequest($url, 'PUT', $body);
        $responseString = $this->sendRequest($httpRequest);
        return $this->prepareDealResponse($responseString);
    }

    /**
     * Изменение инструмента заказчика по сделке
     *
     * @param string $dealId
     * @param string $paymentToolId
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function changeCustomerPaymentWay(string $dealId, string $paymentToolId)
    {
        $body = json_encode(
            [
                'PaymentToolId' => $paymentToolId
            ]
        );
        $url = "api/v3/deals/{$dealId}/payer/tool";
        $httpRequest = $this->getHttpRequest($url, 'PUT', $body);
        $responseString = $this->sendRequest($httpRequest);
        return $this->prepareDealResponse($responseString);
    }

    /**
     * Получение инструмента исполнителя
     *
     * @param string $supplierId
     * @param string $paymentToolId
     * @return PaymentMethodResponse
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSupplierPaymentWay(string $supplierId, string $paymentToolId)
    {
        $url = "api/v3/beneficiaries/{$supplierId}/tools/{$paymentToolId}";
        $httpRequest = $this->getHttpRequest($url, 'GET');
        $responseString = $this->sendRequest($httpRequest);
        return $this->preparePaymentMethodResponse($responseString);
    }

    /**
     *
     *
     * @param string $supplierId
     * @param string $paymentTypeId
     * @param string $pageNumber
     * @param string $itemsPerPage
     * @return PaymentMethodResponse
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSupplierPaymentWayList(
        string $supplierId,
        string $paymentTypeId = null,
        string $pageNumber = null,
        string $itemsPerPage = null
    ) {
        $url = "api/v3/beneficiaries/{$supplierId}/tools";

        $queryArray = [];

        if (isset($paymentTypeId)) {
            $queryArray['paymentTypeId'] = $paymentTypeId;
        }
        if (isset($pageNumber)) {
            $queryArray['pageNumber'] = $pageNumber;
        }
        if (isset($itemsPerPage)) {
            $queryArray['itemsPerPage'] = $itemsPerPage;
        }

        if ($queryArray) {
            $url .= '?' . http_build_query($queryArray);
        }

        $httpRequest = $this->getHttpRequest($url, 'GET');
        $responseString = $this->sendRequest($httpRequest);
        return $this->preparePaymentMethodResponse($responseString);
    }

    /**
     * Удаление привязанного инструмента исполнителя
     *
     * @param string $supplierId
     * @param string $paymentToolId
     * @return string
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function removeSupplierPaymentWay(string $supplierId, string $paymentToolId)
    {
        $url = "api/v3/beneficiaries/{$supplierId}/tools/{$paymentToolId}";
        $httpRequest = $this->getHttpRequest($url, 'DELETE');
        $responseString = $this->sendRequest($httpRequest);
        return $responseString;
    }

    /**
     *Получение инструмента исполнителя
     *
     * @param string $supplierId
     * @param string $paymentToolId
     * @return PaymentMethodResponse
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCustomerPaymentWay(string $supplierId, string $paymentToolId)
    {
        $url = "api/v3/payers/{$supplierId}/tools/{$paymentToolId}";
        $httpRequest = $this->getHttpRequest($url, 'GET');
        $responseString = $this->sendRequest($httpRequest);
        return $this->preparePaymentMethodResponse($responseString);
    }

    /**
     * Получение списка привязанных инструментов оплаты заказчика
     *
     * @param string $customerId
     * @param string|null $paymentTypeId
     * @param string|null $pageNumber
     * @param string|null $itemsPerPage
     * @return PaymentMethodResponse
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCustomerPaymentWayList(
        string $customerId,
        string $paymentTypeId = null,
        string $pageNumber = null,
        string $itemsPerPage = null
    ) {
        $url = "api/v3/payers/{$customerId}/tools";

        $queryArray = [];

        if (isset($paymentTypeId)) {
            $queryArray['paymentTypeId'] = $paymentTypeId;
        }
        if (isset($pageNumber)) {
            $queryArray['pageNumber'] = $pageNumber;
        }
        if (isset($itemsPerPage)) {
            $queryArray['itemsPerPage'] = $itemsPerPage;
        }

        if ($queryArray) {
            $url .= '?' . http_build_query($queryArray);
        }

        $httpRequest = $this->getHttpRequest($url, 'GET');
        $responseString = $this->sendRequest($httpRequest);
        return $this->preparePaymentMethodResponse($responseString);
    }

    /**
     * Удаление привязанного инструмента заказчика
     *
     * @param string $customerId
     * @param string $paymentToolId
     * @return string
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function removeCustomerPaymentWay(string $customerId, string $paymentToolId)
    {
        $url = "api/v3/payers/{$customerId}/tools/{$paymentToolId}";
        $httpRequest = $this->getHttpRequest($url, 'DELETE');
        $responseString = $this->sendRequest($httpRequest);
        return $responseString;
    }

    public function getAllSupplierPayouts()
    {

    }

    public function getAllCustomerRefunds()
    {

    }

    public function getAllDealsBySupplier()
    {

    }

    public function dealsCompleteAll()
    {

    }

    public function getPlatformPaymentTypes()
    {

    }

    public function getPlatformPayoutTypes()
    {

    }

    /**
     * @return string
     */
    private function createTimeStamp()
    {
        $timeZone = new \DateTimeZone(self::TIMESTAMP_TIME_ZONE);
        $currentDateTime = new \DateTime('now', $timeZone);
        return $currentDateTime->format('Y-m-d\TH:i:s');
    }

    /**
     * @param W1FormRequestInterface $request
     * @return string
     */
    private function createSignatureForForm(W1FormRequestInterface $request): string
    {
        $params = $request->toArray();
        ArrayHelper::remove($params, 'signature');
        ksort($params);
        $paramsString = '';
        array_walk(
            $params, function ($value) use (&$paramsString) {
            $paramsString .= $value;
        }
        );
        $request->signature = base64_encode($this->hashFunction($paramsString . $this->signatureKey));
    }

    /**
     * @param string $url
     * @param string $timeStamp
     * @param string $requestBody
     * @return string
     */
    private function createSignatureForHeader(string $url, string $timeStamp, string $requestBody): string
    {
        $paramsString = $url . $timeStamp . $requestBody . $this->signatureKey;
        return base64_encode($this->hashFunction($paramsString));
    }

    /**
     * @param string $url
     * @param string $method
     * @param string $body
     * @return Request
     */
    private function getHttpRequest(string $url, string $method, string $body = ''): Request
    {
        $timeStamp = $this->createTimeStamp();
        return new Request(
            $method,
            $url,
            [
                'X-Wallet-PlatformId' => $this->platformId,
                'X-Wallet-Signature' => $this->createSignatureForHeader($url, $timeStamp, $body),
                'X-Wallet-Timestamp' => $timeStamp
            ],
            $body
        );
    }

    /**
     * @param Request $httpRequest
     * @return string
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendRequest(Request $httpRequest)
    {
        try {
            $client = new Client(['base_uri' => $this->baseW1Url]);
            $response = $client->send($httpRequest);
        } catch (RequestException $e) {
            throw new W1ExecuteRequestException($e);
        }
        $body = $response->getBody();
        return (string)$body;
    }

    /**
     * @param string $response
     * @return DealResponse
     */
    private function prepareDealResponse(string $response): DealResponse
    {
        $responseArray = json_decode($response, true);
        $dealResponse = new DealResponse();
        if (is_array($responseArray)) {
            $dealResponse->setAttributes($responseArray);
        }
        return $dealResponse;
    }

    /**
     * @param string $response
     * @return PaymentMethodResponse
     */
    private function preparePaymentMethodResponse(string $response): PaymentMethodResponse
    {
        $responseArray = json_decode($response, true);
        $dealResponse = new PaymentMethodResponse();
        if (is_array($responseArray)) {
            $dealResponse->setAttributes($responseArray);
        }
        return $dealResponse;
    }
}
