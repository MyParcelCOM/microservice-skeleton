<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Shipments;

use Illuminate\Http\JsonResponse;
use MyParcelCom\Exceptions\InvalidJsonSchemaException;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use MyParcelCom\Microservice\Http\Request;
use MyParcelCom\Microservice\Validation\ResourceValidator;
use MyParcelCom\Transformers\TransformerService;

class ShipmentController extends Controller
{
    /**
     * Route that validates and creates a shipment.
     *
     * @param JsonRequestValidator $jsonRequestValidator
     * @param ResourceValidator    $resourceValidator
     * @param ShipmentRepository   $repository
     * @param Request              $request
     * @param TransformerService   $transformerService
     * @return JsonResponse
     * @throws InvalidJsonSchemaException
     * @throws \MyParcelCom\Transformers\TransformerException
     */
    public function create(JsonRequestValidator $jsonRequestValidator, ResourceValidator $resourceValidator, ShipmentRepository $repository, Request $request, TransformerService $transformerService): JsonResponse
    {
        $jsonRequestValidator->validate('/shipments', 'post', 201);

        // TODO Add rules to ResourceValidator to include carrier-specific requirments.
        if (!$resourceValidator->validate($request)) {
            $errors = $resourceValidator->getErrors();

            throw new InvalidJsonSchemaException($errors);
        }

        $shipment = $repository->createFromPostData($request->json('data'));

        return new JsonResponse(
            $transformerService->transformResource($shipment),
            JsonResponse::HTTP_CREATED
        );
    }
}
