<?php

namespace Yaseen\PackGen\Services;

use Yaseen\PackGen\Protocols\StubData;

 class CodeGeneratorFromBlade extends CodeGeneratorImp
{
    public function renderContent(StubData $stub) : string {
        return view($stub->source, ['o' => $this->dataHolder])->render();
    }
    
    public function renderPath(string $path) : string {
        return \Blade::render($path, ['o' => $this->dataHolder ]);
    }

}