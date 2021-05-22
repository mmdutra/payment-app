<?php

declare(strict_types=1);

namespace Feature\Transaction;

use App\User\Models\Type;
use Database\Factories\UserFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;

class CreateTransactionEndpointTest extends \TestCase
{
    use DatabaseMigrations;

    public function testShouldNotAcceptTheTransactionWithInvalidData()
    {
        $data = [];
        $this->post('/transactions', $data)
            ->assertResponseStatus(422);
    }

    public function testShouldNotAcceptTheTransactionWithNonExistentUser()
    {
        $data = [
            'payer' => 1,
            'payee' => 2,
            'value' => 1000
        ];
        
        $this->post('/transactions', $data)
            ->assertResponseStatus(422);
    }

    public function testShouldNotAcceptTransactionWithASellerAsPayer()
    {
        $sellerPayer = UserFactory::new()->create();
        $payee = UserFactory::new()->create();
        
        $data = [
            'payer' => $sellerPayer->id,
            'payee' => $payee->id,
            'value' => 50
        ];
        
        $this->post('/transactions', $data)
            ->assertResponseStatus(403);
    }

    public function testShouldRegisterTheTransaction()
    {
        $payer = UserFactory::new(['type' => Type::PERSON])->create();
        $payee = UserFactory::new()->create();
        
        $data = [
            'payer' => $payer->id,
            'payee' => $payee->id,
            'value' => 50
        ];
        
        $this->post('/transactions', $data)
            ->assertResponseStatus(201);
    }
}