<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static TaxTypeEnum EORI()
 * @method static TaxTypeEnum IOSS()
 * @method static TaxTypeEnum VAT()
 * @method static TaxTypeEnum GST()
 * @method static TaxTypeEnum UKIMS()
 */
class TaxTypeEnum extends Enum
{
    const EORI = 'eori';
    const IOSS = 'ioss';
    const VAT = 'vat';
    const GST = 'gst';
    const UKIMS = 'ukims';
}
