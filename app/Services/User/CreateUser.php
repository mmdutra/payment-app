<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\User\DocumentAlreadyExistsException;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\ValueObjects\User\Document\Document;
use App\ValueObjects\User\Type;

class CreateUser
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(array $data): User
    {
        $document = Document::createFromString($data['cpf']);

        $userExists = $this->userRepository->documentAlreadyExists($document);

        if ($userExists) {
            throw new DocumentAlreadyExistsException($data['cpf']);
        }

        $user = new User($data);
        $user->document = (string) $document;
        $user->password = $data['password'];
        $user->type = Type::fromDocument($document);

        return $this->userRepository->create($user);
    }
}
