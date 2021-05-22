<?php

declare(strict_types=1);

namespace Unit\Transaction\Services;

use App\Transaction\Exceptions\SellerTransactionException;
use App\Transaction\Models\Status;
use App\Transaction\Models\Transaction;
use App\Transaction\Models\TransactionRepositoryInterface;
use App\Transaction\Services\CreateTransaction;
use App\User\Exceptions\UserNotFoundException;
use App\User\Models\Type;
use App\User\Models\UserRepositoryInterface;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Log;

class CreateTransactionTest extends \TestCase
{
    private $userRepositoryMock;
    private $transactionRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $this->transactionRepositoryMock = $this->createMock(TransactionRepositoryInterface::class);
    }

    public function testShouldNotRegisterPayerAndPayeeWithSameId()
    {
        $service = new CreateTransaction($this->transactionRepositoryMock, $this->userRepositoryMock);
        
        static::expectException(\InvalidArgumentException::class);
        $service->create(['payer' => 1, 'payee' => 1]);
    }

    public function testShouldNotMakeTransactionFromSeller()
    {
        $payer = UserFactory::new()->make();
        $payee = UserFactory::new(['type' => Type::PERSON])->make();
        $this->userRepositoryMock->expects($this->exactly(2))
            ->method('findById')
            ->withConsecutive([1], [2])
            ->willReturnOnConsecutiveCalls($payer, $payee);
            
        $service = new CreateTransaction($this->transactionRepositoryMock, $this->userRepositoryMock);
        
        static::expectException(SellerTransactionException::class);
        $service->create(['payer' => 1, 'payee' => 2]);
    }

    public function testShouldNotMakeTransactionWithNonExistentUsers()
    {
        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willThrowException(new UserNotFoundException());
            
        $service = new CreateTransaction($this->transactionRepositoryMock, $this->userRepositoryMock);
            
        static::expectException(UserNotFoundException::class);
        $service->create(['payer' => 1, 'payee' => 2]);
    }

    public function testShouldRegisterATransaction()
    {
        $payer = UserFactory::new(['id' => 1, 'document' => '89498239002', 'type' => Type::PERSON])->make();
        $payee = UserFactory::new(['id' => 2])->make();
        
        $this->userRepositoryMock->expects($this->exactly(2))
            ->method('findById')
            ->withConsecutive([1], [2])
            ->willReturnOnConsecutiveCalls($payer, $payee);
        
        $transaction = new Transaction([
            'payer_id' => 1,
            'payee_id' => 2,
            'value' => 100,
            'status' => Status::UNDER_ANALYSIS
        ]);
        
        $this->transactionRepositoryMock->expects($this->once())
            ->method('create')
            ->with($transaction)
            ->willReturn($transaction);
        
        $service = new CreateTransaction($this->transactionRepositoryMock, $this->userRepositoryMock);
        $service->create([
            'payer' => 1,
            'payee' => 2,
            'value' => 100
        ]);
    }
}