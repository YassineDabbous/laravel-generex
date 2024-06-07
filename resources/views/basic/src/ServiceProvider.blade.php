@phpTag

namespace {{ $o->packageNamespace }};

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use {{ $o->packageNamespace }}\Models\{{ $o->modelClassName }};
use {{ $o->packageNamespace }}\Policies\{{ $o->policyClassName }};

class {{ $o->serviceProviderClassName }} extends ServiceProvider
{
    public function boot()
    {
        Gate::policy({{ $o->modelClassName }}::class, {{ $o->policyClassName }}::class);

        Relation::enforceMorphMap([
		    '{{ $o->modelMorphName }}' => {{ $o->modelClassName }}::class,
		]);
        
        $this->loadRoutesFrom(__DIR__.'/../routes/{{ $o->tableName }}_api.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/{{ $o->tableName }}_web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', '{{ $o->packageName }}');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', '{{ $o->packageName }}');
    }

}
