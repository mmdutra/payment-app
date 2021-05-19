<?php

declare(strict_types=1);

namespace App\User\Models\Document;

abstract class Document
{
    protected string $value;

    abstract protected function isValid(string $value): bool;

    public static function createFromString(string $value): self
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (strlen($value) === 14) {
            return new CNPJ($value);
        }

        return new CPF($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
