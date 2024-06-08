@phpTag

namespace {packageNamespace};

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use {packageNamespace}\Models\{modelClassName};
use {packageNamespace}\Policies\{policyClassName};

class {serviceProviderClassName} extends ServiceProvider
{
    public function boot()
    {
        Gate::policy({modelClassName}::class, {policyClassName}::class);

        Relation::enforceMorphMap([
		    '{modelMorphName}' => {modelClassName}::class,
		]);
        
        $this->loadRoutesFrom(__DIR__.'/../routes/{tableName}_api.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/{tableName}_web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', '{packageName}');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', '{packageName}');
    }

}
