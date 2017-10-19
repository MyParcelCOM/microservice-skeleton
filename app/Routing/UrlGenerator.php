<?php declare(strict_types=1);

namespace MyParcelCom\Microservice\Routing;

use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use MyParcelCom\Common\Contracts\UrlGeneratorInterface;

class UrlGenerator extends BaseUrlGenerator implements UrlGeneratorInterface
{

}
