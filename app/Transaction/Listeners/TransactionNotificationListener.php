<?php

declare(strict_types=1);

namespace App\Transaction\Listeners;

use App\Transaction\Events\TransactionNotificationEvent;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Log;

class TransactionNotificationListener
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle(TransactionNotificationEvent $event): void
    {
        $transaction = $event->getTransaction();
        $uri = env('NOTIFICATION_SERVICE_URL');
        
        try {
            $this->client->request('GET', $uri, [
                'payer' => $transaction->payer_id,
                'payee' => $transaction->payee_id,
                'value' => $transaction->value
            ]);
        } catch (\Exception $exception) {
            $exceptionClass = get_class($exception);
            Log::error("Erro ao tentar enviar notificação: {$exceptionClass}: {$exception->getMessage()}");
        }
    }
}