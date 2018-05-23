<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Routing;

use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use MyParcelCom\JsonApi\Interfaces\UrlGeneratorInterface;

class UrlGenerator extends BaseUrlGenerator implements UrlGeneratorInterface
{

}
