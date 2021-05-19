<?php

declare(strict_types=1);

namespace App\User\Models\Document;

class CNPJ extends Document
{
    public function __construct(string $value)
    {
        if (!$this->isValid($value)) {
            throw new \InvalidArgumentException('CNPJ is not valid.');
        }

        $this->value = (string) preg_replace('/[^0-9]/', '', $value);
    }

    protected function isValid(string $value): bool
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (strlen($value) != 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $value)) {
            return false;
        }

        for ($i = 0, $j = 5, $sum = 0; $i < 12; $i++)
        {
            $sum += $value[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $rest = $sum % 11;

        if ($value[12] != ($rest < 2 ? 0 : 11 - $rest)) {
            return false;
        }

        for ($i = 0, $j = 6, $sum = 0; $i < 13; $i++)
        {
            $sum += $value[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $rest = $sum % 11;

        return $value[13] == ($rest < 2 ? 0 : 11 - $rest);
    }
}
