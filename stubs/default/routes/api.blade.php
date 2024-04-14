@php
    echo "<?php".PHP_EOL;
@endphp

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')
        ->namespace('{{ $o->packageNamespace }}\Http\Controllers')
        ->middleware(['auth:sanctum'])
        ->group(function(Router $router)  {

        $router->resource('{{ $o->tableName }}', '{{ $o->modelName }}ApiController');

});