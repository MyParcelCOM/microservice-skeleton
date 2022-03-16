<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Providers;

use Illuminate\Support\ServiceProvider;
use MyParcelCom\Microservice\Collections\CollectionRepository;
use MyParcelCom\Microservice\PickUpDropOffLocations\PickUpDropOffLocationRepository;
use MyParcelCom\Microservice\Shipments\ShipmentRepository;
use MyParcelCom\Microservice\Statuses\StatusRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PickUpDropOffLocationRepository::class);
        $this->app->singleton(StatusRepository::class);
        $this->app->singleton(ShipmentRepository::class);
        $this->app->singleton(CollectionRepository::class);
    }
}
