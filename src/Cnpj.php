<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Brazil;

use Elavora\Api\DataTypes\AbstractDataType;

final readonly class Cnpj extends AbstractDataType
{
    /**
     * Verifica se o valor e um CNPJ valido.
     */
    public static function isValid(mixed $value): bool
    {
        $registration = self::canonical($value);
        if ($registration === null || preg_match('/^([0-9])\1{13}\z/', $registration) === 1) {
            return false;
        }

        return self::digit($registration, 12) === (int) $registration[12]
            && self::digit($registration, 13) === (int) $registration[13];
    }

    protected static function normalize(mixed $value): string
    {
        return self::canonical($value) ?? '';
    }

    private static function digit(string $registration, int $position): int
    {
        $weights = $position === 12
            ? [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
            : [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $sum = 0;
        foreach ($weights as $index => $weight) {
            $sum += (ord($registration[$index]) - 48) * $weight;
        }

        $remainder = $sum % 11;

        return $remainder < 2 ? 0 : 11 - $remainder;
    }

    private static function canonical(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        if (preg_match('/^[0-9A-Za-z]{12}[0-9]{2}\z/', $value) === 1) {
            return strtoupper($value);
        }

        if (
            preg_match(
                '/^[0-9A-Za-z]{2}\.[0-9A-Za-z]{3}\.[0-9A-Za-z]{3}\/[0-9A-Za-z]{4}-[0-9]{2}\z/',
                $value
            ) !== 1
        ) {
            return null;
        }

        return strtoupper(str_replace(['.', '/', '-'], '', $value));
    }
}
