<?php

use YassineDabbous\Generex\Helpers\Field;
use YassineDabbous\Generex\Concrete\CodeGeneratorFromBlade;
use YassineDabbous\Generex\Concrete\CodeGeneratorFromStub;
use YassineDabbous\Generex\Concrete\DataHolderImp;
use YassineDabbous\Generex\Concrete\DataGeneratorImp;
use YassineDabbous\Generex\Concrete\InputValidatorImp;
use YassineDabbous\Generex\Concrete\TemplateProviderImp;

return [
    /** Output folder. */
    'root'  => base_path('modules/'),

    /** Schema folder path. */
    'schemas_folder'    => resource_path('generex_schemas/'),

    /** If true, all packages will be combined in one module. */
    'single_module' => false,

    /** Files that shouldn't be overwritten in single_module mode. */
    'module_files'  => [
        'composer', 
        'ServiceProvider',
    ],

    /** Package namespace. */
    'namespace' => [
        'vendor' => 'generex',
        'package' => null,
    ],

    /** Default Template Provider */
    'template_provider' => TemplateProviderImp::class,

    /** Default data holder class */
    'data_holder' => DataHolderImp::class,

    /** Default Input Validator */
    'input_validator' => InputValidatorImp::class,

    /** Default code generator */
    'code_generator' => CodeGeneratorFromBlade::class, // CodeGeneratorFromStub::class,

    /** Default data generator */
    'data_generator' => DataGeneratorImp::class,


    /** templates used to generate your app files */
    'templates' => [
        'API Only'  => [
            'stubs' => [
                // base
                'generex::basic.composer' => 'composer.json',
                'generex::basic.src.ServiceProvider' => '/src/{{$o->serviceProviderClassName}}.php',

                'generex::basic.src.Model' => '/src/Models/{{$o->modelClassName}}.php',
                'generex::basic.src.Policy' => '/src/Policies/{{$o->policyClassName}}.php',
                'generex::basic.src.Request' => '/src/Http/Requests/{{$o->requestClassName}}.php',

                // 'generex::basic.database.migrations' => 'database/migrations/{{date("Y_m_d_His")}}_create_{{$o->tableName}}_table.php',
                'generex::basic.database.migrations' => 'database/migrations/{{date("Y_m_d_000000")}}_create_{{$o->tableName}}_table.php',
    
                // api
                'generex::basic.routes.api' => 'routes/{{$o->tableName}}_api.php',
                'generex::basic.src.ApiController' => '/src/Http/Controllers/{{$o->modelName}}ApiController.php',
            ],
        ],
        'Web only' => [
            'stubs' => [
                // base
                'generex::basic.composer' => 'composer.json',
                'generex::basic.src.ServiceProvider' => '/src/{{$o->serviceProviderClassName}}.php',
    
                'generex::basic.src.Model' => '/src/Models/{{$o->modelClassName}}.php',
                'generex::basic.src.Policy' => '/src/Policies/{{$o->policyClassName}}.php',
                'generex::basic.src.Request' => '/src/Http/Requests/{{$o->requestClassName}}.php',
    
                'generex::basic.database.migrations' => 'database/migrations/{{date("Y_m_d_000000")}}_create_{{$o->tableName}}_table.php',
    
                // web
                'generex::basic.routes.web' => 'routes/{{$o->tableName}}_web.php',
                'generex::basic.src.WebController' => '/src/Http/Controllers/{{$o->modelName}}WebController.php',
                'generex::basic.views.layout' => 'resources/views/layout.blade.php',
                'generex::basic.views.index' => 'resources/views/{{$o->tableName}}/index.blade.php',
                'generex::basic.views.create' => 'resources/views/{{$o->tableName}}/create.blade.php',
                'generex::basic.views.edit' => 'resources/views/{{$o->tableName}}/edit.blade.php',
                'generex::basic.views.show' => 'resources/views/{{$o->tableName}}/show.blade.php',
                'generex::basic.views.card' => 'resources/views/{{$o->tableName}}/card.blade.php',
                'generex::basic.views.form' => 'resources/views/{{$o->tableName}}/form.blade.php',
            ],
        ],
        'Full Pack' => [
            'stubs' => [
                // base
                'generex::basic.composer' => 'composer.json',
                'generex::basic.src.ServiceProvider' => '/src/{{$o->serviceProviderClassName}}.php',
    
                'generex::basic.src.Model' => '/src/Models/{{$o->modelClassName}}.php',
                'generex::basic.src.Policy' => '/src/Policies/{{$o->policyClassName}}.php',
                'generex::basic.src.Request' => '/src/Http/Requests/{{$o->requestClassName}}.php',
    
                'generex::basic.database.migrations' => 'database/migrations/{{date("Y_m_d_000000")}}_create_{{$o->tableName}}_table.php',
    
                // api
                'generex::basic.routes.api' => 'routes/{{$o->tableName}}_api.php',
                'generex::basic.src.ApiController' => '/src/Http/Controllers/{{$o->modelName}}ApiController.php',
    
                // web
                'generex::basic.routes.web' => 'routes/{{$o->tableName}}_web.php',
                'generex::basic.src.WebController' => '/src/Http/Controllers/{{$o->modelName}}WebController.php',
                'generex::basic.views.layout' => 'resources/views/layout.blade.php',
                'generex::basic.views.index' => 'resources/views/{{$o->tableName}}/index.blade.php',
                'generex::basic.views.create' => 'resources/views/{{$o->tableName}}/create.blade.php',
                'generex::basic.views.edit' => 'resources/views/{{$o->tableName}}/edit.blade.php',
                'generex::basic.views.show' => 'resources/views/{{$o->tableName}}/show.blade.php',
                'generex::basic.views.card' => 'resources/views/{{$o->tableName}}/card.blade.php',
                'generex::basic.views.form' => 'resources/views/{{$o->tableName}}/form.blade.php',
            ],
        ],
        'Extended' => [
            'stubs' => [
                'generex::basic.composer' => 'composer.json',
                'generex::basic.routes.api' => 'routes/{{$o->tableName}}_api.php',
                'generex::basic.src.ServiceProvider' => '/src/{{$o->serviceProviderClassName}}.php',
                'generex::basic.src.Model' => '/src/Models/{{$o->modelClassName}}.php',
                'generex::basic.src.Policy' => '/src/Policies/{{$o->policyClassName}}.php',
                'generex::basic.src.Request' => '/src/Http/Requests/{{$o->requestClassName}}.php',
    
                'generex::extended.src.HasQueryBuilder' => '/src/Concerns/Has{{$o->modelName}}QueryBuilder.php',
                'generex::extended.src.ApiController' => '/src/Http/Controllers/{{$o->modelName}}ApiController.php',
            ],
        ],
        'plain_stub' => [ 
            'code_generator' => CodeGeneratorFromStub::class,
            'stubs'     => [
                'generex::plain_stub.composer' => 'composer.json',
                'generex::plain_stub.src.ServiceProvider' => '/src/{serviceProviderClassName}.php',
            ]
        ],
    ],


    'model' => [
        /** Default values for attributes */
        'defaults' => [
            'meta' => "'{}'",
        ],
        
        /** Casting by fields name or filter */
        'casts' => [
            "YassineDabbous\\FileCast\\FileCast" => ['cover', 'photo', 'image', 'picture', 'icon'],
            "Ysn\\SuperCore\\Casts\\Spatial\\LocationCast" => fn(Field $f) => $f->dbType == 'point',
            'array' => fn(Field $f) => $f->dbType == 'json',
            'bool' => fn(Field $f) => $f->dbType == 'boolean',
            'date' => fn(Field $f) => in_array($f->dbType, ['date', 'datetime', 'timestamps']),
        ],

        /** Default hidden columns */
        'hidden' => [
            'password',
            'email_verified_at',
            'remember_token',
            'deleted_at',
            'tenant_id',
            'indestructible',
            'meta',
        ],

        /** Won't be included in Model $fillable property, the FormRequest and the html forms. */
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
