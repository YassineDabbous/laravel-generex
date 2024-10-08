<?php

namespace YassineDabbous\Generex\Concrete;

use YassineDabbous\Generex\Protocols\StubData;

 class CodeGeneratorFromStub extends CodeGeneratorImp
{
    function valueToString($value): string {
        if(is_array($value)){
            $value = implode(', ', array_map(fn($v)=>"'$v'", array_values($value)));
        }
        return $value;
    }



    public function renderContent(StubData $stub) : string {
        $this->dataHolder->__fillReplacements();
        return str_replace(
            array_map(fn($v)=> "{{$v}}", array_keys($this->dataHolder->replacements)),
            array_map(fn($v)=> $this->valueToString($v), array_values($this->dataHolder->replacements)),
            view($stub->source)
        );
    }



    public function renderPath(string $path) : string {
        $this->dataHolder->__fillReplacements();
        return str_replace(
            array_map(fn($v)=> "{{$v}}", array_keys($this->dataHolder->replacements)),
            array_map(fn($v)=> $this->valueToString($v), array_values($this->dataHolder->replacements)),
            $path
        );
    }

}