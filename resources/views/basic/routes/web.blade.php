@phpTag

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::middleware(['web']) //, 'auth'
        ->namespace('{{ $o->packageNamespace }}\Http\Controllers')
        ->group(function(Router $router)  {

        $router->resource('{{ $o->tableName }}', '{{ $o->modelName }}WebController')->names('{{ $o->tableName }}');

});