<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Http;

use Illuminate\Http\Request as BaseRequest;
use MyParcelCom\JsonApi\Http\Interfaces\RequestInterface;
use MyParcelCom\JsonApi\Http\Traits\RequestTrait;

class Request extends BaseRequest implements RequestInterface
{
    use RequestTrait;
}
