<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

use Illuminate\Http\JsonResponse;
use MyParcelCom\JsonApi\Exceptions\InvalidJsonSchemaException;
use MyParcelCom\JsonApi\Transformers\TransformerException;
use MyParcelCom\JsonApi\Transformers\TransformerService;
use MyParcelCom\Microservice\Http\Controllers\Controller;
use MyParcelCom\Microservice\Http\JsonRequestValidator;
use MyParcelCom\Microservice\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManifestController extends Controller
{
    /**
     * @param JsonRequestValidator $jsonRequestValidator
     * @param ManifestRepository   $repository
     * @param Request              $request
     * @param TransformerService   $transformerService
     * @return JsonResponse
     * @throws InvalidJsonSchemaException
     * @throws TransformerException
     */
    public function create(
        JsonRequestValidator $jsonRequestValidator,
        ManifestRepository $repository,
        Request $request,
        TransformerService $transformerService
    ): JsonResponse {
        $jsonRequestValidator->validate('/manifests', 'post');

        $manifest = $repository->createFromPostData($request->json('data'), $request->json('meta', []));

        return new JsonResponse(
            $transformerService->transformResource($manifest),
            Response::HTTP_CREATED
        );
    }
}
