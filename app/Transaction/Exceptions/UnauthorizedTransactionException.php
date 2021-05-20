<?php

declare(strict_types=1);

namespace App\Transaction\Exceptions;

use Throwable;

class UnauthorizedTransactionException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Houve um problema ao autorizar a transação.');
    }
}