<?php

declare(strict_types=1);

namespace App\User\Exceptions;

class DocumentAlreadyExistsException extends \DomainException
{
    public function __construct(string $document)
    {
        $message = "Document {$document} has already been taken.";

        parent::__construct($message);
    }
}
