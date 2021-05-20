<?php

declare(strict_types=1);

namespace App\Transaction\Models;

interface TransactionRepositoryInterface
{
    public function create(Transaction $transaction): Transaction;
    public function update(Transaction $transaction, array $data): Transaction;
}