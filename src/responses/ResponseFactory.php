<?php
/**
 * Created by PhpStorm.
 * User: mzapeka
 * Date: 27.02.19
 * Time: 22:18
 */

namespace WalletOne\responses;

use WalletOne\exceptions\W1WrongParamException;

class ResponseFactory
{
    /**
     * @param string $responseTypeId
     * @param array $responseData
     * @return DealResponse|PaymentMethodResponse|PayoutResponse|RefundResponse|W1ResponseInterface|CardCreateResponse
     * @throws W1WrongParamException
     */

    public static function createResponse(string $responseTypeId, array $responseData)
    {
        switch ($responseTypeId) {
            case ResponseTypesEnum::RESP_TYPE_DEAL:
                $obj = new DealResponse();
                break;
            case ResponseTypesEnum::RESP_TYPE_PAYMENT_METHOD:
                $obj = new PaymentMethodResponse();
                break;
            case ResponseTypesEnum::RESP_TYPE_PAYOUT:
                $obj = new PayoutResponse();
                break;
            case ResponseTypesEnum::RESP_TYPE_REFUND:
                $obj = new RefundResponse();
                break;
            case ResponseTypesEnum::RESP_TYPE_CARD_CREATE:
                $obj = new CardCreateResponse();
                break;
            default:
                throw new W1WrongParamException('Passed wrong response ID');
        }
        $obj->setAttributes($responseData);
        return $obj;
    }
}
