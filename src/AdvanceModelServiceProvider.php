<?php

namespace AdvanceModel;

use AdvanceModel\Commands\CreateModel;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AdvanceModelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-advance-model')
            // ->hasConfigFile('advance-model')
            ->hasCommands([
                CreateModel::class
            ])
            /*->hasViews()*/;
    }
    
    public function boot()
    {
        parent::boot();

        // Load package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-advance-model');
    }

}
