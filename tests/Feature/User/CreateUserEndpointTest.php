<?php

declare(strict_types=1);

namespace Feature\User;

use App\User\Models\User;
use Database\Factories\UserFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;

class CreateUserEndpointTest extends \TestCase
{
    use DatabaseMigrations;
    
    public function testShouldNotAcceptInvalidData()
    {
        $this->post('/users', [])
            ->assertResponseStatus(422);
    }

    public function testShouldNotAddUserWithAlreadyExistentDocument()
    {
        $user = UserFactory::new()->create();
        
        $data = [
            'name' => 'example',
            'email' => 'test@example.com',
            'cpf' => $user->document,
            'password' => 'secret'
        ];
        
        $this->post('/users', $data)
            ->assertResponseStatus(403);
    }
    
    public function testShouldNotAddUserWithInvalidDocument()
    {
        $data = [
            'name' => 'example',
            'email' => 'test@example.com',
            'cpf' => 'whatever',
            'password' => 'secret'
        ];
        
        $this->post('/users', $data)
            ->assertResponseStatus(422);
    }

    public function testShouldSaveTheUser()
    {
        $data = [
            'name' => 'example',
            'email' => 'test@example.com',
            'cpf' => '544.648.640-40',
            'password' => 'secret'
        ];
        
        $this->post('/users', $data)
            ->assertResponseStatus(201);
            
        static::assertEquals(
            1, 
            User::all()->count()
        );
    }
}