<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 03.10.18
 * Time: 9:04
 */

namespace WalletOne\requests;

interface W1RequestInterface
{
    public function getMethod():string ;
    public function getEndPoint():string ;

    public function __toString();
    public function toArray();
}
