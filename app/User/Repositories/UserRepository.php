<?php

declare(strict_types=1);

namespace App\User\Repositories;

use App\User\Exceptions\UserNotFoundException;
use App\User\Models\User;
use App\User\Models\UserRepositoryInterface;
use App\User\Models\Document\Document;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function create(User $user): User
    {
        $user->password = Hash::make($user->password);
        $user->save();
        
        return $user;
    }

    public function findById(int $id): User
    {
        $user = User::find($id);
        
        if (is_null($user)) {
            throw new UserNotFoundException();
        }
        
        return $user;
    }

    public function documentAlreadyExists(Document $document): bool
    {
        $user = User::query()
            ->where('document', (string) $document)
            ->get()
            ->first();
            
        return !is_null($user);
    }
}