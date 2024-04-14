@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $o->packageNamespace }};

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use {{ $o->packageNamespace }}\Models\{{ $o->modelName }};
use {{ $o->packageNamespace }}\Policies\{{ $o->modelName }}Policy;

class {{ $o->moduleName }}ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::policy({{ $o->modelName }}::class, {{ $o->modelName }}Policy::class);

        Relation::enforceMorphMap([
		    '{{ $o->modelMorphName }}' => {{ $o->modelName }}::class,
		]);
        
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', '{{ $o->packageName }}');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', '{{ $o->packageName }}');
    }

}
