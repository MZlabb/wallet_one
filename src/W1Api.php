<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 02.10.18
 * Time: 14:45
 */

namespace WalletOne;

use yii\base\BaseObject;

/**
 * @property Callable $hashFunction
*/

class W1Api extends BaseObject
{
    const END_POINT = 'https://api.w1.ru/p2p/';
    const TEST_END_POINT = 'https://api.dev.walletone.com/p2p/';

    public $platformId;
    public $signatureKey;

    private $endpoint;

    public $hashFunction;

    public function __construct(array $config = [], $testMode = false)
    {
        parent::__construct($config);
        $this->endpoint = $testMode ? self::END_POINT : self::TEST_END_POINT;
        $this->hashFunction = function (string $string) {
            return md5($string);
        };
    }

    public function pay()
    {
        
    }

    public function addSupplier()
    {
        
    }

    public function addCustomer()
    {
        
    }

    public function dealRegister()
    {

    }

    public function dealComplete()
    {
        
    }

    public function dealConfirm()
    {
        
    }

    public function dealCancel()
    {
        
    }

    public function getDealStatus()
    {
        
    }

    public function changeSupplierPaymentWay()
    {

    }

    public function changeCustomerPaymentWay()
    {

    }

    public function getSupplierPaymentWay()
    {

    }

    public function removeSupplierPaymentWay()
    {

    }

    public function getAllSupplierPaymentWays()
    {

    }

    public function getCustomerPaymentWay()
    {

    }

    public function getAllCustomerPaymentWays()
    {

    }

    public function removeCustomerPaymentWay()
    {

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

}