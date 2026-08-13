<?php

namespace AdvancedModel;

use AdvancedModel\Commands\CreateModel;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use AdvancedModel\Http\Middleware\Base64DownloadScript;

class AdvancedModelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-advanced-model')
            // ->hasConfigFile('advanced-model')
            ->hasCommands([
                CreateModel::class
            ])
            /*->hasViews()*/;
    }
    
    public function boot()
    {
        parent::boot();

        // Load package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-advanced-model');
        
        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'advanced-model');
        
        Route::macro('advancedResource', function ($name, $controller, $options = []) {
            Route::get("{$name}/export", [$controller, 'export'])->name("{$name}.export");

            return Route::resource($name, $controller, $options);
        });
        
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(Base64DownloadScript::class);
    }

}
