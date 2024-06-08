<?php

namespace YassineDabbous\Generex\Services;

use YassineDabbous\Generex\Protocols\StubData;

 class CodeGeneratorFromBlade extends CodeGeneratorImp
{
    public function renderContent(StubData $stub) : string {
        return view($stub->source, ['o' => $this->dataHolder])->render();
    }
    
    public function renderPath(string $path) : string {
        return \Blade::render($path, ['o' => $this->dataHolder ]);
    }

}