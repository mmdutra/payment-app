<?php

declare(strict_types=1);

namespace Unit\Transaction\Services;

use App\Transaction\Exceptions\UnauthorizedTransactionException;
use App\Transaction\Models\Status;
use App\Transaction\Models\Transaction;
use App\Transaction\Models\TransactionRepositoryInterface;
use App\Transaction\Services\TransactionAuthorization;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TransactionAuthorizationTest extends \TestCase
{
    private $repositoryMock;
    private $clientMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repositoryMock = $this->createMock(TransactionRepositoryInterface::class);
        $this->clientMock = $this->createMock(ClientInterface::class);
    }

    public function testShouldNotAuthorizeTheTransaction()
    {
        $transaction = new Transaction();
        
        $this->repositoryMock->expects($this->once())
            ->method('update')
            ->with($transaction, ['status' => Status::DENIED]);
        
        $this->clientMock->expects($this->once())
            ->method('request')
            ->with('GET', env('AUTHORIZATION_SERVICE_URL'), ['timeout' => 3])
            ->willThrowException(new ClientException(
            'Unauthorized',
                $this->createMock(RequestInterface::class),
                $this->createMock(ResponseInterface::class)
            ));
            
        $service = new TransactionAuthorization($this->repositoryMock, $this->clientMock);
        
        static::expectException(UnauthorizedTransactionException::class);
        $service->authorize($transaction);
    }

    public function testShouldAuthorizeTheTransaction()
    {
        $transaction = new Transaction();
        
        $this->repositoryMock->expects($this->once())
            ->method('update')
            ->with($transaction, ['status' => Status::APPROVED]);
            
        $this->clientMock->expects($this->once())
            ->method('request')
            ->with('GET', env('AUTHORIZATION_SERVICE_URL'), ['timeout' => 3])
            ->willReturn($this->createMock(ResponseInterface::class));

        $service = new TransactionAuthorization($this->repositoryMock, $this->clientMock);
        $service->authorize($transaction);

    }
}