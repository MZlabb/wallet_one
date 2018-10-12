<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 05.10.18
 * Time: 11:27
 */

namespace WalletOne\Test\testdata;


class DealRegisterRequestData
{
    public static function getResponseData()
    {
        return [
            'platformDealId' => 'test_platform',
            'platformPayerId' => '1',
            'payerPhoneNumber' => '+39089656986',
//            'payerPaymentToolId' => '2',
            'platformBeneficiaryId' => '3',
            'beneficiaryPaymentToolId' => '2',
            'amount' => 10.00,
            'currencyId' => 643,
            'shortDescription' => 'test',
            'fullDescription' => 'test',
        ];
    }
}