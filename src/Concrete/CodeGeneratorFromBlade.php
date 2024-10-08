<?php

namespace YassineDabbous\Generex\Concrete;
use YassineDabbous\Generex\Abstracts\CodeGeneratorImp;
use YassineDabbous\Generex\Helpers\StubData;


 class CodeGeneratorFromBlade extends CodeGeneratorImp
{
    public function renderContent(StubData $stub) : string {
        return view($stub->source, ['o' => $this->dataHolder])->render();
    }
    
    public function renderPath(string $path) : string {
        return \Blade::render($path, ['o' => $this->dataHolder ]);
    }

}