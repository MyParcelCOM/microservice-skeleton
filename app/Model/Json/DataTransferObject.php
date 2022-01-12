<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Model\Json;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Spatie\DataTransferObject\DataTransferObject as BaseDataTransferObject;

abstract class DataTransferObject extends BaseDataTransferObject implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?self
    {
        return is_null($value) ? null : new static(json_decode($value, true));
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        return is_null($value) ? null : json_encode(array_filter($value->toArray()));
    }

    public function toArrayWith(DataTransferObject $object, bool $filter = false): array
    {
        $array = array_merge($this->toArray(), $object->toArray());

        return $filter ? array_filter($array) : $array;
    }
}
