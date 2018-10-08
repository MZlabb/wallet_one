<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 05.10.18
 * Time: 10:22
 */

use WalletOne\W1Config;

class W1ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testCreateConfig()
    {
        $config = self::getConfig();

        $this->assertInstanceOf(W1Config::class, $config);
        $this->assertAttributeEquals('test_platform', 'platformId', $config);
        $this->assertAttributeEquals('123456789', 'signatureKey', $config);
        $this->assertAttributeInstanceOf(Closure::class, 'hashFunction', $config);
        $this->assertAttributeEquals(true, 'isTestMode', $config);
    }

    public function testGetBaseW1Url()
    {
        $config = self::getConfig();
        $config->isTestMode = true;
        $this->assertSame('https://api.dev.walletone.com/p2p/', $config->baseW1Url);

        $config->isTestMode = false;
        $this->assertSame('https://api.w1.ru/p2p/', $config->baseW1Url);
    }

    public function testHashFunction()
    {
        $config = self::getConfig();
        $this->assertSame(md5('test'), ($config->hashFunction)('test'));
    }

    private static function getConfig()
    {
        return $config = new W1Config(
            [
                'platformId' => 'test_platform',
                'signatureKey' => '123456789',
                'isTestMode' => true,
            ]
        );
    }
}
