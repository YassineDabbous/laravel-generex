<?php

namespace YassineDabbous\Generex\Protocols;

use Illuminate\Support\Str;

abstract class DataHolder
{
    /**
     * Names used in templates.
     */
    public array $replacements = [];

    /**
     * Model/Table fields.
     *
     * @var \Illuminate\Support\Collection<array>
     */
    public $fields;
    
    /**
     * Won't be included in Model $fillable property, the FormRequest and the blade views
     */
    public array $unfillable = [
        'id',
        'uuid',
        'ulid',
        'tenant_id',
        'password',
        'remember_token',
        'indestructible',
        'meta',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public array $hidden = [
            'password',
            'tenant_id',
            'remember_token',
            'indestructible',
            'meta',
            'email_verified_at',
            'deleted_at',
    ];
    
    public array $defaults = [];

    public function __construct()
    {
        $this->unfillable = config('generex.model.unfillable', $this->unfillable);
        $this->hidden = config('generex.model.hidden', $this->hidden);
        $this->defaults = config('generex.model.defaults', $this->defaults);
    }


    public function __set($key, $value)
    {
        $this->replacements[$key] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->replacements)) {
            return $this->replacements[$name];
        }
        if (method_exists($this, $name)) {
            $this->replacements[$name] = $this->$name();
            return $this->replacements[$name];
        }
        throw new \Exception('Undefined property: '.__CLASS__.'::'.$name);
    }

    
    public function __fillReplacements()
    {
        $reflector = new \ReflectionClass($this);
        $methods = $reflector->getMethods();
        // dump($methods);
        foreach ($methods as $method) {
            if(!str_starts_with($method->getName(), '__') && $method->getNumberOfRequiredParameters() == 0){
                $this->{$method->getName()};
            }
        }
    }


    protected function isSingleModule() : bool {
        return config('generex.single_module', false);
    }

    protected function vendorName() : string {
        return config('generex.namespace.vendor', 'generex');
    }

    protected function packageName() : string {
        return config('generex.namespace.package') ?? Str::singular($this->tableName);
    }

    protected function moduleName() : string {
        return Str::studly($this->packageName);
    }
    
    protected function connectionName() {
        return $this->replacements['connectionName'] ?? null;
    }
    
}
