<?php

declare(strict_types=1);

namespace App\Transaction\Providers;

use App\Transaction\Events\TransactionNotificationEvent;
use App\Transaction\Listeners\TransactionNotificationListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TransactionNotificationEvent::class => [
            TransactionNotificationListener::class
        ]
    ];
}