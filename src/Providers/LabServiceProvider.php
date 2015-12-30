<?php

namespace Continuum\Laboratory\Providers;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class LabServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadAutoloader(base_path('lab'));

        $this->publishes([
            __DIR__.'/../config/laboratory.php' => config_path('laboratory.php'),
        ]);

        $this->registerProviders();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //...
    }

    /**
     * Register any service providers
     *
     * @return void
     */
    protected function registerProviders()
    {
        foreach ($this->app->config['laboratory.providers'] as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Require composer's autoload file the packages.
     *
     * @return void
     */
    protected function loadAutoloader($path)
    {
        $finder = new Finder;
        $files = new Filesystem;

        $autoloads = $finder->in($path)->files()->name('autoload.php')->depth('<= 3')->followLinks();

        foreach ($autoloads as $file) {
            $files->requireOnce($file->getRealPath());
        }
    }
}
