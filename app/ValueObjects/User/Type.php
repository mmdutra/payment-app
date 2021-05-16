<?php

declare(strict_types=1);

namespace App\ValueObjects\User;

use App\ValueObjects\User\Document\CNPJ;
use App\ValueObjects\User\Document\Document;

class Type
{
    const PERSON = 'P';
    const SELLER = 'S';

    public static function fromDocument(Document $document): string
    {
        if ($document instanceof CNPJ) {
            return self::SELLER;
        }

        return self::PERSON;
    }
}
