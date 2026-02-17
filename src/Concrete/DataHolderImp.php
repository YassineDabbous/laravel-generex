<?php

namespace YassineDabbous\Generex\Concrete;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use YassineDabbous\Generex\Protocols\DataHolder;

class DataHolderImp extends DataHolder
{
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

    protected function useSoftDeletes() : bool {
        foreach ($this->fields as $field) {
            if ($field->name == 'deleted_at') {
                return true;
            }
        }
        return false;
    }

    protected function useMultiTenancy() : bool {
        foreach ($this->fields as $field) {
            if ($field->name == 'tenant_id') {
                return true;
            }
        }
        return false;
    }

    protected function fieldsWithDefaultValues() : Collection
    {
        return $this->fields->where(fn($c) => !is_null($c->defaultValue));
    }

    protected function fieldsWithCasts() : Collection
    {
        return $this->fields->where(fn($c) => !is_null($c->castType));
    }

    protected function editableFields() : Collection
    {
        return $this->fields->where(fn($c) => is_null($c->editable) || $c->editable);
    }

    protected function fieldsWithCreateRules() : Collection
    {
        return $this->editableFields
                    ->where(fn($f) => !in_array($f->name, ['id', 'created_at', 'updated_at']))
                    ->where(fn($f) => count($f->rules));
    }

    protected function fieldsWithUpdateRules() : Collection
    {
        return $this->editableFields
                    ->where(fn($f) => !in_array($f->name, ['id', 'created_at', 'updated_at']))
                    ->where(fn($f) => count($f->rules))
                    ->where(fn($f) => !(count($f->rules)==1 && $f->rules[0]==='required'));
    }

    protected function visibleFields() : Collection
    {
        return $this->fields->where(fn($c) => is_null($c->inView) || $c->inView);
    }


    protected function modelFillableValues() {
        return implode(', ', array_map(fn($v) => "'$v'", $this->editableFields()->pluck('name')->toArray()));
    }



    /** Package Namespace. */
    protected function packageNamespace() : string {
        return Str::studly($this->vendorName).'\\'.$this->moduleName;
    }
    protected function packageNamespaceForComposer() : string {
        return Str::studly($this->vendorName).'\\\\'.$this->moduleName;
    }



    protected function spatialableFields() : Collection
    {
        return $this->fields->where(fn($c) => $c->dbType == 'point');
    }


    protected function migrationLines() : array {
        $lines = [];
        $createdAtField = null;
        $updatedAtField = null;
        foreach($this->fields as $field){
            if($field->name == 'created_at'){
                $createdAtField = $field;
                continue;
            }
            if($field->name == 'updated_at'){
                $updatedAtField = $field;
                continue;
            }
            $lines[] = $field->migration;
        }
        if ($createdAtField?->name === 'created_at' && $updatedAtField?->name === 'updated_at') {
            $lines[] = '$table->timestamps();';
        } else {
            if ($createdAtField) {
                $lines[] = $createdAtField->migration;
            }
            if ($updatedAtField) {
                $lines[] = $updatedAtField->migration;
            }
        }
        return $lines;
    }

    
}
