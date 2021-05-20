<?php

declare(strict_types=1);

namespace App\Transaction\Repositories;

use App\Transaction\Models\Transaction;
use App\Transaction\Models\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(Transaction $transaction): Transaction
    {
        $transaction->save();
        
        return $transaction;
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        $transaction->fill($data);
        
        return $transaction;
    }
}