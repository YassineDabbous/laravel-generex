<?php

namespace Yaseen\PackGen\Protocols;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

    protected function vendorName() : string {
        return config('namespace.vendor', 'yaseen');
    }

    protected function packageName() : string {
        return config('namespace.package', Str::singular($this->tableName) );
    }

    protected function moduleName() : string {
        return Str::studly($this->packageName);
    }

    protected function modelName() : string {
        return Str::studly(Str::singular($this->tableName));
    }

    protected function modelMorphName() : string {
        return Str::snake($this->modelName);
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

    /**
     * Model Namespace.
     */
    protected function modelNamespace() : string {
        return $this->packageNamespace().'\Models';
    }

    /**
     * Policy Namespace.
     */
    protected function policyNamespace() : string {
        return $this->packageNamespace().'\Policies';
    }

    /**
     * Controller Namespace.
     */
    protected function controllerNamespace() : string {
        return $this->packageNamespace().'\Http\Controllers';
    }

    /**
     * Request Namespace.
     */
    protected function requestNamespace() : string {
        return $this->packageNamespace().'\Http\Requests';
    }

    
}
