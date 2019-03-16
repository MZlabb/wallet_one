<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 02.10.18
 * Time: 14:45
 */

namespace WalletOne;

use WalletOne\callback\Callback;
use WalletOne\exceptions\W1ExecuteRequestException;
use WalletOne\exceptions\W1RuntimeException;
use WalletOne\exceptions\W1WrongParamException;
use WalletOne\requests\DealRegisterRequest;
use WalletOne\requests\W1FormRequestInterface;
use WalletOne\responses\CardCreateResponse;
use WalletOne\responses\DealResponse;
use WalletOne\responses\PaymentMethodResponse;
use WalletOne\responses\PayoutResponse;
use WalletOne\responses\RefundResponse;
use WalletOne\responses\ResponseFactory;
use WalletOne\responses\ResponseTypesEnum;
use yii\base\BaseObject;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property Callable $hashFunction
 */
class W1Api extends BaseObject
{
    /**
     * @var W1Config $conf
     */
    private $conf;
    /**
     * @var W1Client $w1Client
     */
    private $w1Client;

    public function __construct(array $config = [])
    {
        $this->conf = new W1Config($config);
        $this->w1Client = new W1Client($this->conf);
        parent::__construct();
    }

    /**
     * Prepare all needed fields for customer request via POST form
     * For request which should send from browser
     *
     * @param W1FormRequestInterface $request
     * @return mixed
     * @throws W1WrongParamException
     * @throws \Exception
     */
    public function getFormData(W1FormRequestInterface $request): array
    {
        /**
         * @var $request Model
        */
        $request->platformId = $this->conf->platformId;
        $request->timestamp = W1Client::createTimeStamp();
        $this->createSignatureForForm($request);
        if (!$request->validate()) {
            $errorsString = print_r($request->getErrors(), true);
            throw new W1WrongParamException($errorsString);
        }
        return $request->toArray();
    }

    /**
     * Регистрация сделки в системе
     *
     * @param DealRegisterRequest $request
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dealRegister(DealRegisterRequest $request): DealResponse
    {
        if (!$request->validate()) {
            $errorsString = print_r($request->getErrors(), true);
            throw new W1WrongParamException($errorsString);
        }
        $this->w1Client->execute($request->getEndPoint(), $request->getMethod(), (string)$request);
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_DEAL);
    }

    /**
     * Завершение сделки
     *
     * @param string $dealId
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dealComplete(string $dealId): DealResponse
    {
        $url = "/api/v3/deals/{$dealId}/complete";
        $this->w1Client->execute($url, 'PUT');
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_DEAL);
    }

    /**
     * Подтверждение сделки
     *
     * @param string $dealId
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dealConfirm(string $dealId): DealResponse
    {
        $url = "/api/v3/deals/{$dealId}/confirm";
        $this->w1Client->execute($url, 'PUT');
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_DEAL);
    }

    /**
     * Отмена сделки
     *
     * @param string $dealId
     * @param bool $returnMoneyWithCommission
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dealCancel(string $dealId, bool $returnMoneyWithCommission = false): DealResponse
    {
        $body = json_encode(['WithCommission' => $returnMoneyWithCommission]);
        $url = "/api/v3/deals/{$dealId}/cancel";
        $this->w1Client->execute($url, 'PUT', $body);
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_DEAL);
    }

    /**
     * Получение статуса сделки
     *
     * @param string $dealId
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDealStatus(string $dealId): DealResponse
    {
        $url = "/api/v3/deals/{$dealId}";
        $this->w1Client->execute($url, 'GET');
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_DEAL);
    }

    /**
     * Изменение инструмента исполнителя по сделке
     *
     * @param string $dealId
     * @param string $paymentToolId
     * @param bool $autoComplete
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function changeSupplierPaymentWay(
        string $dealId,
        string $paymentToolId,
        bool $autoComplete = false
    ): DealResponse {
        $body = json_encode(
            [
                'PaymentToolId' => $paymentToolId,
                'AutoComplete' => $autoComplete ? 1 : 0
            ]
        );
        $url = "api/v3/deals/{$dealId}/beneficiary/tool";
        $this->w1Client->execute($url, 'PUT', $body);
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_DEAL);
    }

    /**
     * Изменение инструмента заказчика по сделке
     *
     * @param string $dealId
     * @param string $paymentToolId
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function changeCustomerPaymentWay(string $dealId, string $paymentToolId): DealResponse
    {
        $body = json_encode(
            [
                'PaymentToolId' => $paymentToolId
            ]
        );
        $url = "api/v3/deals/{$dealId}/payer/tool";
        $this->w1Client->execute($url, 'PUT', $body);
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_DEAL);
    }

    /**
     * Получение инструмента исполнителя
     *
     * @param string $supplierId
     * @param string $paymentToolId
     * @return PaymentMethodResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSupplierPaymentWay(string $supplierId, string $paymentToolId): PaymentMethodResponse
    {
        $url = "api/v3/beneficiaries/{$supplierId}/tools/{$paymentToolId}";
        $this->w1Client->execute($url, 'GET');
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_PAYMENT_METHOD);
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
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSupplierPaymentWayList(
        string $supplierId,
        string $paymentTypeId = null,
        string $pageNumber = null,
        string $itemsPerPage = null
    ): PaymentMethodResponse {
        $url = "api/v3/beneficiaries/{$supplierId}/tools"
            . $this->prepareQueryString(compact('paymentTypeId', 'pageNumber', 'itemsPerPage'));

        $this->w1Client->execute($url, 'GET');
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_PAYMENT_METHOD);
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
    public function removeSupplierPaymentWay(string $supplierId, string $paymentToolId): string
    {
        $url = "api/v3/beneficiaries/{$supplierId}/tools/{$paymentToolId}";
        $this->w1Client->execute($url, 'DELETE');
        return $this->w1Client->getResponseString();
    }

    /**
     * Получение инструмента исполнителя
     *
     * @param string $supplierId
     * @param string $paymentToolId
     * @return PaymentMethodResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCustomerPaymentWay(string $supplierId, string $paymentToolId): PaymentMethodResponse
    {
        $url = "api/v3/payers/{$supplierId}/tools/{$paymentToolId}";
        $this->w1Client->execute($url, 'GET');
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_PAYMENT_METHOD);
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
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCustomerPaymentWayList(
        string $customerId,
        string $paymentTypeId = null,
        string $pageNumber = null,
        string $itemsPerPage = null
    ): PaymentMethodResponse {
        $url = "api/v3/payers/{$customerId}/tools"
            . $this->prepareQueryString(compact('paymentTypeId', 'pageNumber', 'itemsPerPage'));

        $this->w1Client->execute($url, 'GET');
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_PAYMENT_METHOD);
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
    public function removeCustomerPaymentWay(string $customerId, string $paymentToolId): string
    {
        $url = "api/v3/payers/{$customerId}/tools/{$paymentToolId}";
        $this->w1Client->execute($url, 'DELETE');
        return $this->w1Client->getResponseString();
    }

    /**
     * Получение списка выплат по исполнителю
     *
     * @param string $supplierId
     * @param string|null $platformDealId
     * @param string|null $pageNumber
     * @param string|null $itemsPerPage
     * @return PayoutResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSupplierPayoutList(
        string $supplierId,
        string $platformDealId = null,
        string $pageNumber = null,
        string $itemsPerPage = null
    ): PayoutResponse {
        $url = "/api/v3/beneficiaries/{$supplierId}/payouts"
            . $this->prepareQueryString(compact('platformDealId', 'pageNumber', 'itemsPerPage'));

        $this->w1Client->execute($url, 'GET');
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_PAYOUT);
    }


    /**
     * Получение списка возвратов по заказчику
     *
     * @param string $customerId
     * @param string|null $platformDealId
     * @param string|null $pageNumber
     * @param string|null $itemsPerPage
     * @return RefundResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllCustomerRefunds(
        string $customerId,
        string $platformDealId = null,
        string $pageNumber = null,
        string $itemsPerPage = null
    ): RefundResponse {
        $url = "/api/v3/payers/{$customerId}/refunds"
            . $this->prepareQueryString(compact('platformDealId', 'pageNumber', 'itemsPerPage'));

        $this->w1Client->execute($url, 'GET');
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_REFUND);
    }

    /**
     * Получение списка сделок  по исполнителю
     *
     * @param string $supplierId
     * @param string|null $dealStates
     * @param string|null $pageNumber
     * @param string|null $itemsPerPage
     * @param string|null $searchString
     * @return DealResponse
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllDealsBySupplier(
        string $supplierId,
        string $dealStates = null,
        string $pageNumber = null,
        string $itemsPerPage = null,
        string $searchString = null
    ): DealResponse {
        $url = "api/v3/beneficiaries/{$supplierId}/deals"
            . $this->prepareQueryString(compact('dealStates', 'pageNumber', 'itemsPerPage', 'searchString'));

        $this->w1Client->execute($url, 'GET');
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_DEAL);
    }

    /**
     * Массовое завершение сделок
     *
     * @param array $dealIdList
     * @param string|null $paymentToolId
     * @return string
     * @throws W1ExecuteRequestException
     * @throws W1WrongParamException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dealsCompleteAll(array $dealIdList, string $paymentToolId = null): string
    {
        $params = ['PlatformDeals' => $dealIdList];
        if ($paymentToolId !== null) {
            $params['PaymentToolId'] = $paymentToolId;
        }
        $body = json_encode($params);

        $url = 'api/v3/deals/complete';

        $this->w1Client->execute($url, 'PUT', $body);
        return $this->w1Client->getResponseObject(ResponseTypesEnum::RESP_TYPE_DEAL);
    }

    /**
     * Получение списка доступных способов ввода для площадки
     *
     * @return mixed
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPlatformPaymentTypes()
    {
        $url = '/api/v3/payin/paymenttypes';
        $this->w1Client->execute($url, 'GET');
        return $this->w1Client->getResponseArray();
    }

    /**
     * Получение списка доступных способов вывода для площадки
     *
     * @return mixed
     * @throws W1ExecuteRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPlatformPayoutTypes()
    {
        $url = '/api/v3/payout/paymenttypes';
        $this->w1Client->execute($url, 'GET');
        return $this->w1Client->getResponseArray();
    }

    /**
     * @param array $request
     * @return Callback
     * @throws W1RuntimeException
     * @throws W1WrongParamException
     */
    public function prepareW1Callback(array $request): Callback
    {
        $this->validateSignature($request);
        $callback = new Callback();
        $callback->setAttributes($request);
        if ($callback->validate()) {
            throw new W1WrongParamException('Wrong request:' .print_r($callback->getErrors(), true));
        }
        return $callback;
    }

    /**
     * @param array $request
     * @return CardCreateResponse
     * @throws W1RuntimeException
     * @throws W1WrongParamException
     */
    public function prepareCardCreateRequest(array $request): CardCreateResponse
    {
        $this->validateSignature($request);
        $obj = ResponseFactory::createResponse(ResponseTypesEnum::RESP_TYPE_CARD_CREATE, $request);
        $obj->setAttributes($request);
        if ($obj->validate()) {
            throw new W1WrongParamException('Wrong request:' .print_r($obj->getErrors(), true));
        }
        return $obj;
    }

    /**
     * @return W1Config
     */
    public function getConfig(): W1Config
    {
        return $this->conf;
    }


    /**
     * @param W1FormRequestInterface $request
     */
    private function createSignatureForForm(W1FormRequestInterface $request)
    {
        $request->signature = $this->generateSignature($request->toArray());
    }

    /**
     * @param array $params
     * @return string
     */
    private function prepareQueryString(array $params): string
    {
        foreach ($params as $key => $val) {
            if ($val === null) {
                unset($params[$key]);
            }
        }
        if (count($params) > 0) {
            return '?' . http_build_query($params);
        }
        return '';
    }

    /**
     * @param array $params
     * @throws W1RuntimeException
     */
    private function validateSignature(array $params)
    {
        $signature = ArrayHelper::getValue($params, 'Signature', '');
        $calculatedSignature = $this->generateSignature($params);
        if ($signature !== $calculatedSignature) {
            throw new W1RuntimeException('Wrong signature given');
        }
    }

    /**
     * @param array $params
     * @return string
     */
    private function generateSignature(array $params): string
    {
        ArrayHelper::remove($params, 'Signature');
        uksort($params, 'strcasecmp');
        $request = '';
        foreach ($params as $k => $v) {
            $v = iconv('windows-1251', 'utf-8', $v);
            $request .= $v;
        }
        $request .= $this->conf->signatureKey;
        $hash = ($this->conf->hashFunction)($request);
        return base64_encode(pack('H*', $hash));
    }
}
