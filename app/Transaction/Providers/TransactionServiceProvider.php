<?php

declare(strict_types=1);

namespace App\Transaction\Providers;

use App\Transaction\Models\TransactionRepositoryInterface;
use App\Transaction\Repositories\TransactionRepository;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }
}