<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 04.10.18
 * Time: 10:40
 */

namespace WalletOne\responses;


use Generator;

interface W1ResponseInterface
{
    public function setAttributes($values, $safeOnly = true);
    public function getGenerator(): Generator;
}