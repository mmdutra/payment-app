<?php

declare(strict_types=1);

namespace Unit\Transaction\Listeners;

use App\Transaction\Events\TransactionNotificationEvent;
use App\Transaction\Listeners\TransactionNotificationListener;
use App\Transaction\Models\Transaction;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TransactionNotificationListenerTest extends \TestCase
{
    private $clientMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->clientMock = $this->createMock(ClientInterface::class);
    }

    public function testShouldSendTheTransaction()
    {
        $transaction = new Transaction([
            'value' => 1000,
            'payer_id' => 1,
            'payee_id' => 2
        ]);
        $event = new TransactionNotificationEvent($transaction);
        
        $this->clientMock->expects($this->once())
            ->method('request')
            ->with('GET', env('NOTIFICATION_SERVICE_URL'), [
                'payer' => 1,
                'payee' => 2,
                'value' => 1000
            ])
            ->willReturn($this->createMock(ResponseInterface::class));
        
        $listener = new TransactionNotificationListener($this->clientMock);
        $listener->handle($event);
    }

    public function testShouldNotSendTheNotification()
    {
        $transaction = new Transaction([
            'value' => 1000,
            'payer_id' => 1,
            'payee_id' => 2
        ]);
        $event = new TransactionNotificationEvent($transaction);
        
        $this->clientMock->expects($this->once())
            ->method('request')
            ->with('GET', env('NOTIFICATION_SERVICE_URL'), [
                'payer' => 1,
                'payee' => 2,
                'value' => 1000
            ])
            ->willThrowException(
                new RequestException(
                    'error', 
                        $this->createMock(RequestInterface::class)
                )
            );

        Log::shouldReceive('error')
            ->once()
            ->with('Erro ao tentar enviar notificação: GuzzleHttp\Exception\RequestException: error');
            
        $listener = new TransactionNotificationListener($this->clientMock);
        $listener->handle($event);

    }
}