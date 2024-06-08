<?php

namespace YassineDabbous\Generex;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use YassineDabbous\Generex\Commands\PackGeneratorCommand;
use YassineDabbous\Generex\Protocols\CodeGenerator;
use YassineDabbous\Generex\Protocols\DataHolder;
use YassineDabbous\Generex\Services\CodeGeneratorImp;
use YassineDabbous\Generex\Services\DataHolderImp;
use YassineDabbous\Generex\Protocols\DataGenerator;
use YassineDabbous\Generex\Protocols\TemplateProvider;
use YassineDabbous\Generex\Services\DataGeneratorImp;
use YassineDabbous\Generex\Services\TemplateProviderImp;

class GenerexServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PackGeneratorCommand::class,
            ]);

            $this->mergeConfigFrom(__DIR__.'/config.php', 'generex');
            $this->publishes([
                __DIR__.'/config.php' => config_path('generex.php'),
            ], 'generex-config');

            $this->loadViewsFrom(__DIR__.'/../resources/views', 'generex');
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/generex'),
            ], 'generex-templates');
            
            Blade::directive('phpTag', function () {
                return '<?php echo "<?php\n\n" ?>';
            });

            $this->app->singleton(DataHolder::class, config('generex.data_holder', DataHolderImp::class));
            $this->app->singleton(TemplateProvider::class, config('generex.template_provider', TemplateProviderImp::class));
            $this->app->singleton(CodeGenerator::class, config('generex.code_generator', CodeGeneratorImp::class));
            $this->app->singleton(DataGenerator::class, config('generex.data_generator', DataGeneratorImp::class));
        }

    }

}
