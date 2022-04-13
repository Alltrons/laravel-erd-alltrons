<?php

namespace Alltrons\LaravelErdModules;

use Alltrons\LaravelERdModules\Commands\LaravelErdModulesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelErdModulesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-erd')
            ->hasConfigFile('laravel-erd')
            ->hasViews()
            ->hasCommand(LaravelErdModulesCommand::class)
            ->hasRoutes('web');
    }
}