<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

use Illuminate\Support\Arr;
use MyParcelCom\JsonApi\Interfaces\MapperInterface;
use MyParcelCom\Microservice\Model\Json\AddressJson;
use MyParcelCom\Microservice\Model\Json\ContactJson;

class ManifestMapper implements MapperInterface
{
    /**
     * @param array $data
     * @param null  $model
     * @return Manifest
     */
    public function map($data, $model = null): Manifest
    {
        $attributes = Arr::get($data, 'attributes', $data);
        $relationships = Arr::get($data, 'relationships', $data);

        $manifest = new Manifest(
            $attributes['name'],
            new AddressJson($attributes['address']),
            new ContactJson($attributes['address'])
        );

        $this->mapRelationships($relationships, $manifest);

        return $manifest;
    }

    /**
     * @param array    $relationships
     * @param Manifest $manifest
     * @return $this
     */
    private function mapRelationships(array $relationships, Manifest $manifest): void
    {
//        if (array_key_exists('shipments', $relationships)) {
//            $shipments = array_map(function ($shipment) {
//                // todo: implement functionality to retrieve shipment based on shipment id.
//                return $shipment['id'];
//            }, Arr::get($relationships, 'shipments.data'));
//            $manifest->shipments()->save($shipments);
//        }
    }
}
