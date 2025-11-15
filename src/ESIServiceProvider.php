<?php

declare(strict_types=1);

namespace WormholeSystems\ESI;

use Illuminate\Http\Client\Factory as Http;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use WormholeSystems\ESI\Commands\ESICommand;
use WormholeSystems\ESI\Http\Connector;

final class ESIServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('ws-esi')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_ws_esi_table')
            ->hasCommand(ESICommand::class);
    }

    public function packageRegistered(): void
    {
        // Bind the Connector
        $this->app->singleton(Connector::class, function ($app) {
            return new Connector($app->make(Http::class));
        });

        // Bind the main ESI class
        $this->app->singleton(ESI::class, function ($app) {
            return new ESI($app->make(Connector::class));
        });

        // Alias for easier access
        $this->app->alias(ESI::class, 'esi');
    }
}
