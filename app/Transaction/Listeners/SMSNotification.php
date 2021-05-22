<?php

declare(strict_types=1);

namespace App\Transaction\Listeners;

use App\Transaction\Events\TransactionNotificationEvent;

class SMSNotification
{
    public function handle(TransactionNotificationEvent $event)
    {
        echo "Opa, amigao!\n";
    }
}