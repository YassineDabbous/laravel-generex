<?php

namespace YassineDabbous\Generex\Services;

use YassineDabbous\Generex\Protocols\StubData;

 class CodeGeneratorFromStub extends CodeGeneratorImp
{
    public function renderContent(StubData $stub) : string {
        $this->dataHolder->__fillReplacements();
        return str_replace(
            array_map(fn($v)=> '{'.$v.'}', array_keys($this->dataHolder->replacements)),
            array_values($this->dataHolder->replacements), 
            view($stub->source)
        );
    }
    
    public function renderPath(string $path) : string {
        $this->dataHolder->__fillReplacements();
        return str_replace(
            array_map(fn($v)=> '{'.$v.'}', array_keys($this->dataHolder->replacements)),
            array_values($this->dataHolder->replacements), 
            $path
        );
    }

}