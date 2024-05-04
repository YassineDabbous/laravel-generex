<?php

use Yaseen\PackGen\Services\CodeGeneratorImp;
use Yaseen\PackGen\Services\DataHolderImp;
use Yaseen\PackGen\Services\DataGeneratorImp;
use Yaseen\PackGen\Services\TemplateProviderImp;

return [
    
    /**
     * Schema folder path.
     */
    'schemas_folder'    => resource_path('packgen_schemas/'),

    /**
     * if true, all packages will be combined in one module
     */
    'single_module' => false,

    /**
     * Output folder.
     */
    'root'  => base_path('modules/'),

    /**
     * Package namespace.
     */
    'namespace' => [
        'vendor' => 'yaseen',
        'package' => null,
    ],

    /** DataHolder implementation class  */
    'data_holder' => DataHolderImp::class,

    /** TemplateProvider implementation class  */
    'template_provider' => TemplateProviderImp::class,

    /** CodeGenerator implementation class  */
    'code_generator' => CodeGeneratorImp::class,

    /** CodeGenerator implementation class  */
    'data_generator' => DataGeneratorImp::class,


    /**
     * stubs
     */
    'stubs' => [
        'packgen::basic.composer' => 'composer.json',
        'packgen::basic.routes.api' => 'routes/{{$o->tableName}}_api.php',
        'packgen::basic.src.ServiceProvider' => '/src/{{$o->serviceProviderClassName}}.php',
        'packgen::basic.src.Model' => '/src/Models/{{$o->modelClassName}}.php',
        'packgen::basic.src.Policy' => '/src/Policies/{{$o->policyClassName}}.php',
        'packgen::basic.src.Request' => '/src/Http/Requests/{{$o->requestClassName}}.php',
        'packgen::basic.src.HasQueryBuilder' => '/src/Concerns/Has{{$o->modelName}}QueryBuilder.php',
        'packgen::basic.src.ApiController' => '/src/Http/Controllers/{{$o->modelName}}ApiController.php',
    ],


    'model' => [
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
