<?php

declare(strict_types=1);

namespace Unit\ValueObjects\User\Document;

use App\ValueObjects\User\Document\CNPJ;
use App\ValueObjects\User\Document\CPF;
use App\ValueObjects\User\Document\Document;

class DocumentTest extends \TestCase
{
    public function testShouldCreateACNPJ()
    {
        $value = '42.315.576/0001-98';
        
        static::assertInstanceOf(
            CNPJ::class,
            Document::createFromString($value)
        );
    }
    
    public function testShouldCreateACPF()
    {
        $value = '068.735.150-27';
        
        static::assertInstanceOf(
            CPF::class,
            Document::createFromString($value)
        );
    }

    public function testShouldNotReturnADocument()
    {
        $value = 'whatever';
        
        static::expectException(\InvalidArgumentException::class);
        
        Document::createFromString($value);
    }
}