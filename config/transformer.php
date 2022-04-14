<?php

use MyParcelCom\Microservice\Collections\Collection;
use MyParcelCom\Microservice\Collections\CollectionTransformer;
use MyParcelCom\Microservice\Manifests\Manifest;
use MyParcelCom\Microservice\Manifests\ManifestTransformer;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocation;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationTransformer;
use MyParcelCom\Microservice\ServiceRates\ServiceRate;
use MyParcelCom\Microservice\ServiceRates\ServiceRateTransformer;
use MyParcelCom\Microservice\Shipments\Shipment;
use MyParcelCom\Microservice\Shipments\ShipmentTransformer;
use MyParcelCom\Microservice\Statuses\Status;
use MyParcelCom\Microservice\Statuses\StatusTransformer;

return [

    /*
    |--------------------------------------------------------------------------
    | Transformer Mapping
    |--------------------------------------------------------------------------
    |
    | Here you can map models to transformers so that the transformer factory
    | knows what transformer to use.
    |
    */

    'mapping' => [
        Collection::class            => CollectionTransformer::class,
        Manifest::class              => ManifestTransformer::class,
        PickUpDropOffLocation::class => PickUpDropOffLocationTransformer::class,
        ServiceRate::class           => ServiceRateTransformer::class,
        Shipment::class              => ShipmentTransformer::class,
        Status::class                => StatusTransformer::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Api Schemas
    |--------------------------------------------------------------------------
    |
    | Here you can list the different api schemas and their location.
    |
    */

    'schemas' => [
        'carrier' => env('SCHEMA_DIR', 'vendor/myparcelcom/carrier-specification') . '/dist/swagger.json',
    ],

];
