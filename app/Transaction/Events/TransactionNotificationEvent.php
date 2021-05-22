<?php

declare(strict_types=1);

namespace App\Transaction\Events;

use App\Base\Events\Event;
use App\Transaction\Models\Transaction;

class TransactionNotificationEvent extends Event
{
    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }
    
    protected function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}