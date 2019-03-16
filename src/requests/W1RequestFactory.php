<?php
/**
 * Created by PhpStorm.
 * User: mzapeka
 * Date: 27.02.19
 * Time: 21:34
 */

namespace WalletOne\requests;

use WalletOne\exceptions\W1WrongParamException;

class W1RequestFactory
{
    /**
     * Request factory method
     *
     * @param $requestId
     * @param array $params
     * @return W1RequestInterface | W1FormRequestInterface
     * @throws W1WrongParamException
     */
    public static function getRequest(string $requestId, array $params = []): W1RequestInterface
    {
        $requestClass = 'WalletOne\\requests\\' . $requestId;
        if (class_exists($requestClass)) {
            $requestObj = new $requestClass($params);
            if ($requestObj instanceof W1RequestInterface) {
                return new $requestClass($params);
            }
        }
        throw new W1WrongParamException('Wrong request Id passed');
    }
}
