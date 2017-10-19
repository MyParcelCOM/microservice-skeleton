<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Http;

use Illuminate\Http\Request as BaseRequest;
use MyParcelCom\Common\Contracts\JsonApiRequestInterface;
use MyParcelCom\Common\Traits\JsonApiRequestTrait;

class Request extends BaseRequest implements JsonApiRequestInterface
{
    use JsonApiRequestTrait;
}
