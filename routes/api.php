<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use MyParcelCom\Microservice\Credentials\CredentialController;
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

Route::get('/pickup-dropoff-locations/{countryCode}/{postalCode}', PickUpDropOffLocationController::class . '@getAll');

Route::get('/shipments/{shipmentId}/statuses/{trackingCode}', StatusController::class . '@getStatuses');
Route::post('/shipments', ShipmentController::class . '@create');

Route::get('/validate-credentials', CredentialController::class . '@validateCredentials');
