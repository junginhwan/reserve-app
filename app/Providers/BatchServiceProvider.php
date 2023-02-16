<?php

namespace App\Providers;

use App\Console\Commands\MqvSeatCommand;
use App\Console\Commands\MqvReservationCommand;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;
use App\Services\MqvService;

class BatchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            MqvSeatCommand::class,
            function () {
                $mqvService = app(MqvService::class);
                $logger = app(LogManager::class);
                return new MqvSeatCommand($mqvService, $logger->channel('mqv-seat'));
            }
        );

        $this->app->bind(
            MqvReservationCommand::class,
            function () {
                $mqvService = app(MqvService::class);
                $logger = app(LogManager::class);
                return new MqvReservationCommand($mqvService, $logger->channel('mqv-reservation'));
            }
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
