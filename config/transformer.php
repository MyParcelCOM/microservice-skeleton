<?php

use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocation;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationTransformer;
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
        PickUpDropOffLocation::class => PickUpDropOffLocationTransformer::class,
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
