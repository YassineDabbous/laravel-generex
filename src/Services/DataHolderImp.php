<?php

namespace Yaseen\PackGen\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Yaseen\PackGen\Protocols\DataHolder;

class DataHolderImp extends DataHolder
{
    protected function useSoftDeletes() : bool {
        foreach ($this->fields as $column) {
            if ($column['name'] == 'deleted_at') {
                return true;
            }
        }
        return false;
    }

    protected function modelName() : string {
        return Str::studly(Str::singular($this->tableName));
    }

    protected function modelMorphName() : string {
        return Str::snake($this->modelName);
    }


    protected function serviceProviderClassName() : string {
        return "{$this->moduleName}ServiceProvider";
    }
    
    protected function modelClassName() : string {
        return $this->modelName;
    }

    protected function policyClassName() : string {
        return "{$this->modelName}Policy";
    }

    protected function requestClassName() : string {
        return "{$this->modelName}Request";
    }



    protected function fieldsWithDefaultValues() : Collection
    {
        return $this->fields->where(fn($c) => isset($c['default']) && $c['default']);
    }

    protected function editableFields() : Collection
    {
        return $this->fields->where(fn($c) => !isset($c['editable']) || $c['editable']);
    }



    protected function modelFillableValues() {
        return implode(', ', array_map(fn($v) => "'$v'", $this->editableFields()->pluck('name')->toArray()));
    }



    /**
     * Package Namespace.
     */
    protected function packageNamespace() : string {
        return Str::studly($this->vendorName).'\\'.$this->moduleName;
    }

    
}
