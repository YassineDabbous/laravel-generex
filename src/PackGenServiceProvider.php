<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Yaseen\PackGen\Commands\FullPackGenerator;
use Yaseen\PackGen\Protocols\CodeGenerator;
use Yaseen\PackGen\Protocols\DataHolder;
use Yaseen\PackGen\Services\CodeGeneratorImp;
use Yaseen\PackGen\Services\DataHolderImp;
use Yaseen\PackGen\Protocols\DataGenerator;
use Yaseen\PackGen\Protocols\TemplateProvider;
use Yaseen\PackGen\Services\DataGeneratorImp;
use Yaseen\PackGen\Services\TemplateProviderImp;

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
            
            Blade::directive('phpTag', function () {
                return '<?php echo "<?php\n\n" ?>';
            });

            $this->app->singleton(DataHolder::class, config('packgen.data_holder', DataHolderImp::class));
            $this->app->singleton(TemplateProvider::class, config('packgen.template_provider', TemplateProviderImp::class));
            $this->app->singleton(CodeGenerator::class, config('packgen.code_generator', CodeGeneratorImp::class));
            $this->app->singleton(DataGenerator::class, config('packgen.data_generator', DataGeneratorImp::class));
        }

    }

}
