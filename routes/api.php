<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationController;
use MyParcelCom\Microservice\Shipments\ShipmentController;
use MyParcelCom\Microservice\Statuses\StatusController;

Route::get('/', function () {
    return new JsonResponse([
        'meta' => [
            'title'  => 'MyParcel.com Microservice',
            'status' => 'OK',
        ],
    ]);
});

Route::get(
    '/v1/pickup-dropoff-locations/{countryCode}/{postalCode}',
    PickUpDropOffLocationController::class . '@getAll'
);

Route::get('/v1/shipments/{shipmentId}/statuses/{trackingCode}', StatusController::class . '@getStatuses');
Route::post('/v1/shipments', ShipmentController::class . '@create');
