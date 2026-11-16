<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Brazil;

use Elavora\Api\DataTypes\AbstractDataType;
use Elavora\Api\Framework\Contracts\DescribesType;

final readonly class Cnpj extends AbstractDataType implements DescribesType
{
    /**
     * Descreve o tipo para a documentacao publica (OPTIONS).
     *
     * @return array<string, mixed>
     */
    public static function documentedType(): array
    {
        return [
            'type' => 'string',
            'mask' => '00.000.000/0000-00',
            'pattern' => '^(\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}|\d{14})$',
            'example' => '11.222.333/0001-81',
            'description' => 'CNPJ com ou sem mascara; value() devolve apenas os 14 digitos.',
        ];
    }

    /**
     * Verifica se o valor e um CNPJ valido.
     */
    public static function isValid(mixed $value): bool
    {
        $digits = self::digits($value);
        if (strlen($digits) !== 14 || preg_match('/^(\d)\1{13}$/', $digits) === 1) {
            return false;
        }

        return self::digit($digits, 12) === (int) $digits[12]
            && self::digit($digits, 13) === (int) $digits[13];
    }

    protected static function normalize(mixed $value): string
    {
        return self::digits($value);
    }

    private static function digit(string $digits, int $position): int
    {
        $weights = $position === 12
            ? [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
            : [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $sum = 0;
        foreach ($weights as $index => $weight) {
            $sum += (int) $digits[$index] * $weight;
        }

        $remainder = $sum % 11;

        return $remainder < 2 ? 0 : 11 - $remainder;
    }

    private static function digits(mixed $value): string
    {
        return preg_replace('/\D/', '', (string) $value) ?? '';
    }
}
