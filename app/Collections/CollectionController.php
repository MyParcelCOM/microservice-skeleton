<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Collections;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Exceptions\ResourceConflictException;
use MyParcelCom\JsonApi\Exceptions\ResourceNotFoundException;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use MyParcelCom\Microservice\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

        if ($collectionId !== $request->json('data.id')) {
            throw new ResourceConflictException('id');
        }

        $updatedCollection = $repository->updateFromPatchData($collectionId, $request->json('data'));

        return new JsonResponse(
            $transformerService->transformResource($updatedCollection),
        );
    }

    /**
     * @param string               $collectionId
     * @param CollectionRepository $repository
     * @return JsonResponse
     * @throws ResourceNotFoundException
     */
    public function void(
        string $collectionId,
        CollectionRepository $repository,
    ): JsonResponse {
        // TODO: implement void / cancel functionality.
        $repository->delete($collectionId);

        return new JsonResponse(
            '',
            Response::HTTP_NO_CONTENT
        );
    }
}
