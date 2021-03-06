<?php

declare(strict_types=1);

namespace App\Transaction\Services;

use App\Transaction\Models\Status;
use App\Transaction\Models\Transaction;
use App\Transaction\Models\TransactionRepositoryInterface;
use App\User\Models\UserRepositoryInterface;
use App\Transaction\Exceptions\SellerTransactionException;

class CreateTransaction
{
    private TransactionRepositoryInterface $transactionRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
    }

    public function create(array $data): Transaction
    {
        if ($data['payer'] == $data['payee']) {
            throw new \InvalidArgumentException("Payer and Payee must be differents");
        }
    
        $payer = $this->userRepository->findById((int) $data['payer']);
        $payee = $this->userRepository->findById((int) $data['payee']);
        
        if ($payer->isSeller()) {
            throw new SellerTransactionException();
        }
        
        $transaction = new Transaction([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'value' => (float) $data['value'],
            'status' => Status::UNDER_ANALYSIS
        ]);
        
        return $this->transactionRepository->create($transaction);
    }
}