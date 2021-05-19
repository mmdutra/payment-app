<?php

declare(strict_types=1);

namespace App\User\Models;

use App\User\Models\Document\CNPJ;
use App\User\Models\Document\Document;

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
