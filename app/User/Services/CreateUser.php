<?php

declare(strict_types=1);

namespace App\User\Services;

use App\User\Exceptions\DocumentAlreadyExistsException;
use App\User\Models\{Type, User, UserRepositoryInterface};
use App\User\Models\Document\Document;

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
