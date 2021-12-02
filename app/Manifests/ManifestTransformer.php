<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Manifests;

use MyParcelCom\JsonApi\Exceptions\ModelTypeException;
use MyParcelCom\JsonApi\Transformers\AbstractTransformer;
use MyParcelCom\Microservice\Shipments\File;

class ManifestTransformer extends AbstractTransformer
{
    /** @var string */
    protected $type = 'manifests';

    /**
     * @param Manifest $manifest
     * @return string|null
     */
    public function getId($manifest): ?string
    {
        $this->validateModel($manifest);

        return null;
    }

    /**
     * @param Manifest $manifest
     * @return array
     */
    public function getAttributes($manifest): array
    {
        $this->validateModel($manifest);

        return array_filter([
            'name'  => $manifest->getName(),
            'files' => array_map(function (File $file) {
                return [
                    'resource_type' => $file->getType(),
                    'mime_type'     => $file->getMimeType(),
                    'extension'     => $file->getExtension(),
                    'data'          => $file->getData(),
                ];
            }, $manifest->getFiles()),
        ]);
    }

    /**
     * @param Manifest $manifest
     * @throws ModelTypeException
     */
    protected function validateModel($manifest): void
    {
        if (!$manifest instanceof Manifest) {
            throw new ModelTypeException($manifest, 'manifests');
        }
    }
}
