<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use MyParcelCom\Microservice\Collections\CollectionController;
use MyParcelCom\Microservice\Credentials\CredentialController;
use MyParcelCom\Microservice\Manifests\ManifestController;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationController;
use MyParcelCom\Microservice\ServiceRates\ServiceRateController;
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

Route::post('/get-service-rates', [ServiceRateController::class, 'getServiceRates'])
    ->name('get-service-rates');

Route::post('/manifests', [ManifestController::class, 'create'])
    ->name('create-manifest');

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

//Route::post('/collections', [CollectionController::class, 'create'])
//    ->name('create-collection');
//
//Route::patch('/collections/{collectionId}', [CollectionController::class, 'update'])
//    ->name('update-collection');
