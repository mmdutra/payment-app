<?php

declare(strict_types=1);

namespace App\Transaction\Services;

use App\Transaction\Exceptions\UnauthorizedTransactionException;
use App\Transaction\Models\Status;
use App\Transaction\Models\Transaction;
use App\Transaction\Models\TransactionRepositoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;

class TransactionAuthorization
{
    private TransactionRepositoryInterface $transactionRepository;
    private ClientInterface $client;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        ClientInterface $client
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->client = $client;
    }

    public function authorize(Transaction $transaction): void
    {
        $approvedTransaction = $this->isAuthorized();
        
        if (!$approvedTransaction) {
            $this->transactionRepository->update($transaction, ['status' => Status::DENIED]);
            throw new UnauthorizedTransactionException();
        }

        $this->transactionRepository->update($transaction, ['status' => Status::APPROVED]);
    }

    private function isAuthorized(): bool
    {
        $uri = env('AUTHORIZATION_SERVICE_URL');

        try {
            $this->client->request('GET', $uri, [
                'timeout' => 3
            ]);
            
            return true;
        } catch (ClientException $exception) {
            return false;
        }
    }
}