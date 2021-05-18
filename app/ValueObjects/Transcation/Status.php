<?php

declare(strict_types=1);

namespace App\ValueObjects\Transcation;

class Status
{
    const UNDER_ANALYSIS = 'U';
    const APPROVED = 'A';
    const DENIED = 'D';
}