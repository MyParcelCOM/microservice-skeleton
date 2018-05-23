<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use MyParcelCom\Microservice\Http\Middleware\ExtractCredentials;
use MyParcelCom\Microservice\Http\Middleware\VerifySecret;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        VerifySecret::class,
        ExtractCredentials::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'api' => [],
    ];
}
