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
            'title'  => config('app.name'),
            'status' => 'OK',
        ],
    ]);
});

Route::post('/get-service-rates')->name('get-service-rates');

Route::get('/pickup-dropoff-locations/{countryCode}/{postalCode}', [PickUpDropOffLocationController::class, 'getAll'])
    ->name('get-pickup-dropoff-locations');

Route::get('/shipments/{shipmentId}/statuses/{trackingCode}', [StatusController::class, 'getStatuses'])
    ->name('get-statuses');

Route::delete('/shipments/{shipmentId}', [ShipmentController::class, 'void'])
    ->name('void-shipment');

Route::post('/shipments', [ShipmentController::class, 'create'])
    ->name('create-shipment');

Route::get('/validate-credentials', [CredentialController::class, 'validateCredentials'])
    ->name('validate-credentials');
