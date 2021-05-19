<?php

declare(strict_types=1);

namespace Unit\User\Models\Document;

use App\User\Models\Document\CPF;

class CPFTest extends \TestCase
{
    public function testShouldCreateACPF()
    {
        static::assertInstanceOf(
            CPF::class,
            new CPF('994.569.890-70')
        );
    }

    public function testShouldNotCreateTheCPF()
    {
        static::expectException(\InvalidArgumentException::class);

        new CPF('whatever');
    }

    public function testCPFShouldBeUsedAsString()
    {
        static::assertEquals('99456989070', new CPF('994.569.890-70'));
    }
}
