<?php

declare(strict_types=1);

namespace App\User\Models\Document;

class CPF extends Document
{
    public function __construct(string $value)
    {
        if (!$this->isValid($value)) {
            throw new \InvalidArgumentException('CPF is not valid.');
        }

        $this->value = (string) preg_replace('/[^0-9]/', '', $value);
    }

    public function isValid(string $value): bool
    {
        $cpf = preg_replace( '/[^0-9]/is', '', $value);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
