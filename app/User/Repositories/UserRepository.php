<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use App\ValueObjects\User\Document\Document;
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