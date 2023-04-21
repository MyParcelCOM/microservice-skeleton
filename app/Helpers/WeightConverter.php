<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Helpers;

use InvalidArgumentException;
use MyParcelCom\Microservice\Enums\WeightUnitEnum;

class WeightConverter
{
    public static function convert(int|float $weight, string $from, ?string $to = 'g'): int|float
    {
        $weightInGrams = self::convertToGrams($weight, $from);

        return self::convertFromGrams($weightInGrams, $to);
    }

    private static function convertToGrams(int|float $weight, string $from): int|float
    {
        return match ($from) {
            WeightUnitEnum::MILLIGRAM => $weight / 1000,
            WeightUnitEnum::GRAM => $weight,
            WeightUnitEnum::KILOGRAM => $weight * 1000,
            WeightUnitEnum::OUNCE => $weight * 28.3495,
            WeightUnitEnum::POUND => $weight * 453.592,
            default => throw new InvalidArgumentException('Invalid weight unit'),
        };
    }

    private static function convertFromGrams(float|int $weightInGrams, string $to): float|int
    {
        return match ($to) {
            WeightUnitEnum::MILLIGRAM => $weightInGrams * 1000,
            WeightUnitEnum::GRAM => $weightInGrams,
            WeightUnitEnum::KILOGRAM => $weightInGrams / 1000,
            WeightUnitEnum::OUNCE => $weightInGrams / 28.3495,
            WeightUnitEnum::POUND => $weightInGrams / 453.592,
            default => throw new InvalidArgumentException('Invalid weight unit'),
        };
    }
}
