<?php

declare(strict_types=1);

namespace Unit\ValueObjects\User\Document;

use App\ValueObjects\User\Document\CNPJ;

class CNPJTest extends \TestCase
{
    public function testShouldCreateACNPJ()
    {
        static::assertInstanceOf(
            CNPJ::class,
            new CNPJ('39.999.631/0001-00')
        );
    }

    public function testShouldNotCreateTheCNPJ()
    {
        static::expectException(\InvalidArgumentException::class);

        new CNPJ('whatever');
    }

    public function testCNPJShouldBeUsedAsString()
    {
        static::assertEquals('39999631000100', new CNPJ('39.999.631/0001-00'));
    }
}
