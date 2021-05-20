<?php

declare(strict_types=1);

namespace App\Transaction\Exceptions;

class SellerTransactionException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Sellers can not make payments.');
    }
}