<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\DeliveryDates;

use Illuminate\Http\JsonResponse;
use MyParcelCom\Microservice\DeliveryDates\Http\DeliveryDatesRequest;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class DeliveryDateController extends Controller
{
    /**
     * TODO, after implementation: add tests
     *
     * @throws UnknownProperties
     */
    public function get(
        DeliveryDatesRequest $request,
        JsonRequestValidator $jsonRequestValidator,
    ): JsonResponse {
        $jsonRequestValidator->validate('/available-delivery-dates', 'post');

        // TODO: make a request to the carrier API using the request data
        $request->serviceCode();
        $request->serviceOptionCodes();
        $request->address();
        $request->startDate();
        $request->endDate();

        return new JsonResponse([
            'data' => [
                [
                    'date_from' => '', // response should be ISO8601 formatted date time strings
                    'date_to' => '',
                ]
            ],
        ]);
    }
}
