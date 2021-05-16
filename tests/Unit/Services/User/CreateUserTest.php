<?php

declare(strict_types=1);

namespace Unit\Services\User;

use App\Exceptions\User\DocumentAlreadyExistsException;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\User\CreateUser;
use App\ValueObjects\User\Document\CPF;
use App\ValueObjects\User\Type;

class CreateUserTest extends \TestCase
{
    private $userRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
    }

    public function testShouldInformInvalidDocument()
    {
        $data = ['cpf' => 'whatever'];
        
        $service = new CreateUser($this->userRepositoryMock);

        static::expectException(\InvalidArgumentException::class);
        $service->execute($data);
    }

    public function testShouldNotAddUserWithAlreadyExistentDocument()
    {
        $data = ['cpf' => '52890253082'];
        
        $this->userRepositoryMock->expects($this->once())
            ->method('documentAlreadyExists')
            ->with('52890253082')
            ->willReturn(true);
            
        $service = new CreateUser($this->userRepositoryMock);
        
        static::expectException(DocumentAlreadyExistsException::class);
        $service->execute($data);
    }

    public function testShouldAddTheUser()
    {   
        $data = [
            'name' => 'example',
            'email' => 'test@example',
            'cpf' => '544.648.640-40',
            'password' => 'secret'
        ];
        
        $cpf = new CPF('544.648.640-40');
        
        $this->userRepositoryMock->expects($this->once())
            ->method('documentAlreadyExists')
            ->with($cpf)
            ->willReturn(false);
            
        $user = new User([
            'name' => 'example',
            'email' => 'test@example',
            'document' => '54464864040',
            'type' => Type::PERSON
        ]);
        $user->password = 'secret';
        
        $this->userRepositoryMock->expects($this->once())
            ->method('create')
            ->with($user)
            ->willReturn($user);
            
        $service = new CreateUser($this->userRepositoryMock);
        $service->execute($data);
    }
}