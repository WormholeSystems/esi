<?php

namespace NicolasKion\ESI;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use NicolasKion\ESI\Commands\ESICommand;

class ESIServiceProvider extends PackageServiceProvider
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
}
