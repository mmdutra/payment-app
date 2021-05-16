<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use App\ValueObjects\User\Document\Document;

interface UserRepository
{
    public function create(User $user): User;
    public function findById(int $id): User;
    public function documentAlreadyExists(Document $document): bool;
}