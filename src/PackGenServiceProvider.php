<?php
namespace Yaseen\PackGen;
use Illuminate\Support\ServiceProvider;
use Yaseen\PackGen\Commands\FullPackGenerator;
class PackGenServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                FullPackGenerator::class,
            ]);

            $this->mergeConfigFrom(__DIR__.'/config.php', 'packgen');
            $this->publishes([
                __DIR__.'/config.php' => config_path('packgen.php'),
            ], 'packgen-config');

            $this->loadViewsFrom(__DIR__.'/../resources/views', 'packgen');
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/packgen'),
            ], 'packgen-templates');
        }

    }

}
