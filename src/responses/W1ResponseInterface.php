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
    /**
     * setup model attributes from response data.
     * If response has collection of data - method store data into array and fullfill model with first
     * item of collection.
     * If in method passed 1 item - only model will be fulfilled
     *
     * @param array $values
     * @param bool $safeOnly
     */
    public function setAttributes($values, $safeOnly = true);

    /**
     * return each item of collection as object
     *
     * @return Generator
     */
    public function getGenerator(): Generator;
}
