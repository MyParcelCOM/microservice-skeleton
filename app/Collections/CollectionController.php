<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use MyParcelCom\Microservice\Http\Request;

class CollectionController
{
    /**
     * Route that validates and creates a shipment.
     */
    public function create(
        JsonRequestValidator $jsonRequestValidator,
        CollectionRepository $repository,
        Request $request,
        TransformerService $transformerService
    ): JsonResponse {
        $jsonRequestValidator->validate('/collections', 'post');

        $collection = $repository->createFromPostData($request->json('data'));

        return new JsonResponse(
            $transformerService->transformResource($collection),
            JsonResponse::HTTP_CREATED
        );
    }

    public function update(
        string $collectionId,
        JsonRequestValidator $jsonRequestValidator,
        CollectionRepository $repository,
        Request $request,
        TransformerService $transformerService
    ): JsonResponse {
        $jsonRequestValidator->validate('/collections/{collection_id}', 'patch');

    }
}
