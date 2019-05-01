<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 04.10.18
 * Time: 11:59
 */

namespace WalletOne;

use yii\base\BaseObject;

/**
 * Config object
 *
 * Hash function for creating sign. Should be callback - function($string) {...}
 * @property Callable $hashFunction
 *
 * Credentials
 * @property string $platformId
 * @property string $signatureKey
 *
 * Test mode if set to true
 * @property bool $isTestMode
 *
 * URL Wallet One API server
 * @property string $baseW1Url
 *
 * URL Урл возврата пользователя
 * @property string $returnUrl
 *
 * URL для сallback
 * @property string $dealCallbackURL
 *
 */
class W1Config extends BaseObject
{
    const TIMESTAMP_TIME_ZONE = '+0000';

    const END_POINT = 'https://api.w1.ru/p2p';
    const TEST_END_POINT = 'https://api.dev.walletone.com/p2p';

    public $platformId;
    public $signatureKey;
    public $isTestMode = false;
    public $hashFunction;

    public $returnUrl;
    public $dealCallbackURL;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->hashFunction = $this->hashFunction ?? function (string $string) {
            return md5($string);
        };
    }

    /**
     * @return string
     */
    public function getBaseW1Url(): string
    {
        return $this->isTestMode ? self::TEST_END_POINT : self::END_POINT;
    }
}
