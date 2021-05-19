<?php

declare(strict_types=1);

namespace App\User\Providers;

use App\User\Models\UserRepositoryInterface;
use App\User\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}