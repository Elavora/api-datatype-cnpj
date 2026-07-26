<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Cnpj\Tests;

use Elavora\Api\DataTypes\Brazil\Cnpj;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CnpjAlphanumericTest extends TestCase
{
    public function testAcceptsOfficialAlphanumericVector(): void
    {
        self::assertSame('12ABC34501DE35', Cnpj::from('12ABC34501DE35')->value());
        self::assertSame('12ABC34501DE35', Cnpj::from('12.ABC.345/01DE-35')->value());
        self::assertSame('12ABC34501DE35', Cnpj::from('12.abc.345/01de-35')->value());
    }

    public function testPreservesNumericCnpjValidation(): void
    {
        self::assertSame('11222333000181', Cnpj::from('11.222.333/0001-81')->value());
    }

    #[DataProvider('invalidValues')]
    public function testRejectsValuesOutsideSupportedFormats(mixed $value): void
    {
        self::assertFalse(Cnpj::isValid($value));
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function invalidValues(): iterable
    {
        yield 'invalid check digit' => ['12ABC34501DE34'];
        yield 'letter in check digit' => ['12ABC34501DE3A'];
        yield 'repeated numeric sequence' => ['11.111.111/1111-11'];
        yield 'partial mask' => ['12.ABC345/01DE-35'];
        yield 'leading space' => [' 12.ABC.345/01DE-35'];
        yield 'trailing space' => ['12.ABC.345/01DE-35 '];
        yield 'text around value' => ['CNPJ 12.ABC.345/01DE-35'];
        yield 'unexpected symbol' => ['12@ABC.345/01DE-35'];
        yield 'non ASCII character' => ['12.ÁBC.345/01DE-35'];
        yield 'integer' => [11222333000181];
        yield 'array' => [['12.ABC.345/01DE-35']];
        yield 'object' => [new class {}];
        yield 'null' => [null];
    }
}
