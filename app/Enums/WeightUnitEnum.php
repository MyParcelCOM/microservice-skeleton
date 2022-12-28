<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static WeightUnitEnum MILLIGRAM()
 * @method static WeightUnitEnum GRAM()
 * @method static WeightUnitEnum KILOGRAM()
 * @method static WeightUnitEnum OUNCE()
 * @method static WeightUnitEnum POUND()
 */
class WeightUnitEnum extends Enum
{
    public const MILLIGRAM = 'mg';
    public const GRAM = 'g';
    public const KILOGRAM = 'kg';
    public const OUNCE = 'oz';
    public const POUND = 'lb';
}
