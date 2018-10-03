<?php
/**
 * Created by PhpStorm.
 * User: nikolay
 * Date: 03.10.18
 * Time: 9:04
 */

namespace WalletOne\requests;

/**
 * Идентификатор площадки
 * @property string $platformId
 *
 * Подпись запроса
 * @property string $signature
 *
 * Дата формирования запроса в часовом поясе UTC+0
 * @property string $timestamp
*/


interface W1FormRequestInterface
{
    public function getFormFields():array;
}