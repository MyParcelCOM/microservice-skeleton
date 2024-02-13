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

Route::get('/pickup-dropoff-locations/{latitude}/{longitude}', [PickUpDropOffLocationController::class, 'getAllByGeolocation'])
    // the regex for latitude and longitude is from https://stackoverflow.com/a/18690202
    ->where('latitude', '^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$')
    ->where('longitude', '^[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$')
    ->name('get-pickup-dropoff-locations-by-geolocation');

Route::get('/pickup-dropoff-locations/{countryCode}/{postalCode}', [PickUpDropOffLocationController::class, 'getAllByCountryAndPostalCode'])
    ->name('get-pickup-dropoff-locations-by-country-and-postal-code');

Route::get(
    '/pickup-dropoff-locations/{pudo_id}', [PickUpDropOffLocationController::class, 'getOne'])
    ->name('get-pickup-dropoff-locations-by-id');

Route::get('/shipments/{shipmentId}/statuses/{trackingCode}', [StatusController::class, 'getStatuses'])
    ->name('get-statuses');

//Route::get('/shipments/{shipmentId}/options', [ShipmentController::class, 'getShipmentServiceOptions'])
//    ->name('get-shipment-service-options');

Route::delete('/shipments/{shipmentId}', [ShipmentController::class, 'void'])
    ->name('void-shipment');

Route::post('/shipments', [ShipmentController::class, 'create'])
    ->name('create-shipment');

//Route::post('/multi-colli-shipments', [ShipmentController::class, 'createMultiColli'])
//    ->name('create-multi-colli-shipment');

Route::get('/validate-credentials', [CredentialController::class, 'validateCredentials'])
    ->name('validate-credentials');

//Route::post('/collections', [CollectionController::class, 'create'])
//    ->name('create-collection');
//Route::get('/collection-time-slots', [CollectionController::class, 'getTimeSlots'])
//    ->name('get-collection-time-slots');
//Route::patch('/collections/{collectionId}', [CollectionController::class, 'update'])
//    ->name('update-collection');
//Route::delete('/collections/{collectionId}', [CollectionController::class, 'void'])
//    ->name('delete-collection');
