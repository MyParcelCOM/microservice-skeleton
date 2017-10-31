<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Statuses;

use MyParcelCom\Transformers\AbstractTransformer;
use MyParcelCom\Transformers\TransformerException;

class StatusTransformer extends AbstractTransformer
{
    /** @var string */
    protected $type = 'statuses';

    /**
     * @param Status $status
     * @return string
     */
    public function getId($status): string
    {
        $this->validateModel($status);

        return $status->getId();
    }

    /**
     * @param Status $status
     * @return array
     */
    public function getAttributes($status): array
    {
        $this->validateModel($status);

        return array_filter([
            'code'        => $status->getCode(),
            'description' => $status->getDescription(),
            'timestamp'   => $status->getTimestamp(),
        ]);
    }

    /**
     * @param Status $status
     * @throws TransformerException
     */
    protected function validateModel($status): void
    {
        if (!$status instanceof Status) {
            throw new TransformerException(
                'Invalid model supplied, expected instance of `Status`, got `' . get_class($status) . '`'
            );
        }
    }
}
