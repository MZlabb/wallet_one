<?php
/**
 * Created by PhpStorm.
 * User: mzapeka
 * Date: 27.02.19
 * Time: 21:28
 */

namespace WalletOne\requests;

trait W1FormTrait
{
    /**
     * @return array
     */
    public function getFormFields(): array
    {
        return $this->prepareFields();
    }
}
