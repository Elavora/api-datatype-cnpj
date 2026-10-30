<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Cnpj\Tests;

use Elavora\Api\DataTypes\Brazil\Cnpj;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CnpjTest extends TestCase
{
    public function testValidatesCnpj(): void
    {
        self::assertSame('11222333000181', Cnpj::from('11.222.333/0001-81')->value());
        self::assertFalse(Cnpj::isValid('11.111.111/1111-11'));
    }

    /**
     * @return array<string, array{mixed, bool}>
     */
    public static function isValidProvider(): array
    {
        return [
            'apenas digitos' => ['12345678000195', true],
            'com mascara' => ['12.345.678/0001-95', true],
            'outro valido' => ['11222333000181', true],
            'inteiro' => [12345678000195, true],
            'ruido nao numerico removido' => [' CNPJ 12.345.678/0001-95 ', true],
            'digito verificador errado' => ['12345678000196', false],
            'primeiro digito verificador errado' => ['12345678000185', false],
            'todos iguais' => ['11111111111111', false],
            'zeros' => ['00000000000000', false],
            '13 digitos' => ['1234567800019', false],
            '15 digitos' => ['123456780001950', false],
            'vazio' => ['', false],
            'so letras' => ['abcdefghijklmn', false],
            'null' => [null, false],
        ];
    }

    #[DataProvider('isValidProvider')]
    public function testIsValid(mixed $value, bool $expected): void
    {
        self::assertSame($expected, Cnpj::isValid($value));
    }

    public function testIsValidNaoLancaExcecao(): void
    {
        foreach (['', 'abc', '1', str_repeat('9', 40), 0, 1.5, false, true] as $value) {
            self::assertIsBool(Cnpj::isValid($value));
        }
    }

    public function testNormalizaSoOsDigitos(): void
    {
        self::assertSame('12345678000195', (new Cnpj('12345678000195'))->value());
        self::assertSame('12345678000195', (new Cnpj('12.345.678/0001-95'))->value());
        self::assertSame('12345678000195', Cnpj::from('12.345.678/0001-95')->value());
    }
}
