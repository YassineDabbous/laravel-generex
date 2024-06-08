<?php

use YassineDabbous\Generex\Services\CodeGeneratorFromBlade;
use YassineDabbous\Generex\Services\CodeGeneratorFromStub;
use YassineDabbous\Generex\Services\DataHolderImp;
use YassineDabbous\Generex\Services\DataGeneratorImp;
use YassineDabbous\Generex\Services\TemplateProviderImp;

return [

    /**
     * Schema folder path.
     */
    'schemas_folder'    => resource_path('generex_schemas/'),

    /** If true, all packages will be combined in one module. */
    'single_module' => false,
    /** Files that shouldn't be overwritten in single_module mode. */
    'module_files'  => [
        'composer', 
        'ServiceProvider',
    ],


    /**
     * Output folder.
     */
    'root'  => base_path('modules/'),

    /**
     * Package namespace.
     */
    'namespace' => [
        'vendor' => 'generex',
        'package' => null,
    ],

    /** DataHolder implementation class  */
    'data_holder' => DataHolderImp::class,

    /** TemplateProvider implementation class  */
    'template_provider' => TemplateProviderImp::class,

    /** CodeGenerator implementation class  */
    'code_generator' => CodeGeneratorFromBlade::class, // CodeGeneratorFromStub::class,

    /** CodeGenerator implementation class  */
    'data_generator' => DataGeneratorImp::class,


    /** templates used to generate your app files */
    'templates' => [
        'basic' => [
            'generex::basic.composer' => 'composer.json',
            'generex::basic.src.ServiceProvider' => '/src/{{$o->serviceProviderClassName}}.php',

            'generex::basic.src.Model' => '/src/Models/{{$o->modelClassName}}.php',
            'generex::basic.src.Policy' => '/src/Policies/{{$o->policyClassName}}.php',
            'generex::basic.src.Request' => '/src/Http/Requests/{{$o->requestClassName}}.php',

            // api
            'generex::basic.routes.api' => 'routes/{{$o->tableName}}_api.php',
            'generex::basic.src.ApiController' => '/src/Http/Controllers/{{$o->modelName}}ApiController.php',

            // web
            'generex::basic.routes.web' => 'routes/{{$o->tableName}}_web.php',
            'generex::basic.src.WebController' => '/src/Http/Controllers/{{$o->modelName}}WebController.php',
            'generex::basic.views.layout' => 'resources/views/layout.blade.php',
            'generex::basic.views.index' => 'resources/views/index.blade.php',
            'generex::basic.views.create' => 'resources/views/create.blade.php',
            'generex::basic.views.edit' => 'resources/views/edit.blade.php',
            'generex::basic.views.show' => 'resources/views/show.blade.php',
            'generex::basic.views.card' => 'resources/views/card.blade.php',
            'generex::basic.views.form' => 'resources/views/form.blade.php',
        ],
        'extended' => [
            'generex::basic.composer' => 'composer.json',
            'generex::basic.routes.api' => 'routes/{{$o->tableName}}_api.php',
            'generex::basic.src.ServiceProvider' => '/src/{{$o->serviceProviderClassName}}.php',
            'generex::basic.src.Model' => '/src/Models/{{$o->modelClassName}}.php',
            'generex::basic.src.Policy' => '/src/Policies/{{$o->policyClassName}}.php',
            'generex::basic.src.Request' => '/src/Http/Requests/{{$o->requestClassName}}.php',

            'generex::extended.src.HasQueryBuilder' => '/src/Concerns/Has{{$o->modelName}}QueryBuilder.php',
            'generex::extended.src.ApiController' => '/src/Http/Controllers/{{$o->modelName}}ApiController.php',
        ],
        'plain_stub' => [
            'generex::plain_stub.composer' => 'composer.json',
            'generex::plain_stub.src.ServiceProvider' => '/src/{serviceProviderClassName}.php',
        ],
    ],


    'model' => [
        'defaults' => [
            'meta' => "'{}'",
        ],

        /**
         * Default hidden columns
         */
        'hidden' => [
            'password',
            'email_verified_at',
            'remember_token',
            'deleted_at',
            'tenant_id',
            'indestructible',
            'meta',
        ],

        /*
         * Won't be included in Model $fillable property, the FormRequest and the html forms
         */
        'unfillable' => [
            'id',
            'password',
            'email_verified_at',
            'remember_token',
            'created_at',
            'updated_at',
            'deleted_at',
            'tenant_id',
            'indestructible',
            'meta',
        ],

    ],


];
