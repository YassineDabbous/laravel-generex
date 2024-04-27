<?php

namespace Yaseen\PackGen\Protocols;

abstract class DataHolder
{
    /**
     * Names used in stubs.
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

    public function __construct()
    {
        $this->unfillable = config('packgen.model.unfillable', $this->unfillable);
        $this->hidden = config('packgen.model.hidden', $this->hidden);
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


    
    protected abstract function useSoftDeletes() : bool;

    protected abstract function vendorName() : string;

    protected abstract function packageName() : string;

    protected abstract function moduleName() : string;
    
    protected abstract function modelName() : string;
}
