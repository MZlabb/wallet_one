<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 05.10.18
 * Time: 11:27
 */

namespace WalletOne\Test\testdata;


class DealResponses
{
    public static function getSingleResponse($returnJson = false)
    {
        $response = '{'.
            '"PlatformDealId":"test_platform",'.
            '"DealStateId":"Canceling",'.
            '"CreateDate":"2015-01-07T21:45:33",'.
            '"UpdateDate":"2015-01-07T21:45:33",'.
            '"ExpireDate":"2015-06-07T21:45:33",'.
            '"Amount":10.00,'.
            '"CurrencyId":643,'.
            '"PlatformPayerId":"1",'.
            '"PayerPhoneNumber":"123",'.
            '"PayerPaymentToolId":1,'.
            '"PlatformBeneficiaryId":"1",'.
            '"BeneficiaryPaymentToolId":1,'.
            '"ShortDescription":"Оплата сделки",'.
            '"FullDescription":"Полное описание",'.
            '"DealTypeId":"Deferred",'.
            '"LastErrorCode":"string",'.
            '"LastErrorMessage":"string"'.
         '}';
        return $returnJson ? $response : json_decode($response, true);
    }

    public static function getMultiResponse($returnJson = false)
    {
        $response = '{
  "Deals": [
            {
              "PlatformDealId": "test_platform",
              "DealStateId": "Created",
              "CreateDate": "2017-09-11T09:22:15.056Z",
              "UpdateDate": "2017-09-11T09:22:15.056Z",
              "ExpireDate": "2017-09-11T09:22:15.056Z",
              "Amount": 10.00,
              "CurrencyId": 643,
              "PlatformPayerId": 1,
              "PayerPhoneNumber": "123",
              "PayerPaymentToolId": 1,
              "PlatformBeneficiaryId": "string",
              "BeneficiaryPaymentToolId": 0,
              "ShortDescription": "string",
              "FullDescription": "string",
              "DealTypeId": "Instant",
              "LastErrorCode":"string",
              "LastErrorMessage":"string"
            },
            {
              "PlatformDealId": "test_platform",
              "DealStateId": "Created",
              "CreateDate": "2017-09-11T09:22:15.056Z",
              "UpdateDate": "2017-09-11T09:22:15.056Z",
              "ExpireDate": "2017-09-11T09:22:15.056Z",
              "Amount": 11.0,
              "CurrencyId": 643,
              "PlatformPayerId": 1,
              "PayerPhoneNumber": "123",
              "PayerPaymentToolId": 0,
              "PlatformBeneficiaryId": "string",
              "BeneficiaryPaymentToolId": 0,
              "ShortDescription": "string",
              "FullDescription": "string",
              "DealTypeId": "Instant",
              "LastErrorCode":"string",
              "LastErrorMessage":"string"
            },
            {
              "PlatformDealId": "test_platform",
              "DealStateId": "Created",
              "CreateDate": "2017-09-11T09:22:15.056Z",
              "UpdateDate": "2017-09-11T09:22:15.056Z",
              "ExpireDate": "2017-09-11T09:22:15.056Z",
              "Amount": 12.00,
              "CurrencyId": 643,
              "PlatformPayerId": 1,
              "PayerPhoneNumber": "123",
              "PayerPaymentToolId": 1,
              "PlatformBeneficiaryId": "string",
              "BeneficiaryPaymentToolId": 0,
              "ShortDescription": "string",
              "FullDescription": "string",
              "DealTypeId": "Instant",
              "LastErrorCode":"string",
              "LastErrorMessage":"string"
            }
          ],
          "TotalCount": 0
        }';
        return $returnJson ? $response : json_decode($response, true);
    }

    public static function getMulti2Response($returnJson = false)
    {
        $response = '[ 
    {
              "PlatformDealId": "test_platform",
              "DealStateId": "Created",
              "CreateDate": "2017-09-11T09:22:15.056Z",
              "UpdateDate": "2017-09-11T09:22:15.056Z",
              "ExpireDate": "2017-09-11T09:22:15.056Z",
              "Amount": 10.00,
              "CurrencyId": 643,
              "PlatformPayerId": 1,
              "PayerPhoneNumber": "123",
              "PayerPaymentToolId": 1,
              "PlatformBeneficiaryId": "string",
              "BeneficiaryPaymentToolId": 0,
              "ShortDescription": "string",
              "FullDescription": "string",
              "DealTypeId": "Instant",
              "LastErrorCode":"string",
              "LastErrorMessage":"string"
            },
            {
              "PlatformDealId": "test_platform",
              "DealStateId": "Created",
              "CreateDate": "2017-09-11T09:22:15.056Z",
              "UpdateDate": "2017-09-11T09:22:15.056Z",
              "ExpireDate": "2017-09-11T09:22:15.056Z",
              "Amount": 11.0,
              "CurrencyId": 643,
              "PlatformPayerId": 1,
              "PayerPhoneNumber": "123",
              "PayerPaymentToolId": 0,
              "PlatformBeneficiaryId": "string",
              "BeneficiaryPaymentToolId": 0,
              "ShortDescription": "string",
              "FullDescription": "string",
              "DealTypeId": "Instant",
              "LastErrorCode":"string",
              "LastErrorMessage":"string"
            },
            {
              "PlatformDealId": "test_platform",
              "DealStateId": "Created",
              "CreateDate": "2017-09-11T09:22:15.056Z",
              "UpdateDate": "2017-09-11T09:22:15.056Z",
              "ExpireDate": "2017-09-11T09:22:15.056Z",
              "Amount": 12.00,
              "CurrencyId": 643,
              "PlatformPayerId": 1,
              "PayerPhoneNumber": "123",
              "PayerPaymentToolId": 1,
              "PlatformBeneficiaryId": "string",
              "BeneficiaryPaymentToolId": 0,
              "ShortDescription": "string",
              "FullDescription": "string",
              "DealTypeId": "Instant",
              "LastErrorCode":"string",
              "LastErrorMessage":"string"
            }
]';
        return $returnJson ? $response : json_decode($response, true);
    }

}