<?php

declare(strict_types=1);

namespace App\User\Models;

use App\User\Models\Document\Document;

interface UserRepositoryInterface
{
    public function create(User $user): User;
    public function findById(int $id): User;
    public function documentAlreadyExists(Document $document): bool;
}