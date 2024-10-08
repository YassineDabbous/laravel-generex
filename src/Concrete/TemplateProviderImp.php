<?php

namespace YassineDabbous\Generex\Concrete;
use YassineDabbous\Generex\Protocols\CodeGenerator;
use YassineDabbous\Generex\Protocols\DataHolder;
use YassineDabbous\Generex\Protocols\TemplateProvider;
use YassineDabbous\Generex\Protocols\StubData;

use function Laravel\Prompts\select;

class TemplateProviderImp implements TemplateProvider
{
    public function __construct(
        private DataHolder $dataHolder,
        private CodeGenerator $codeGenerator,
    ){}

    /**
     * Package path.
     */
    protected function packagePath(string $path = '') : string {
        return config('generex.root', base_path('modules/')).$this->dataHolder->moduleName.'/'.$path;
    }

    /**
     * @return array<\YassineDabbous\Generex\Protocols\StubData> 
    */
    public function stubs() : array {    
        $paths = $this->chooseTemplate();
        $stubs = [];
        foreach ($paths as $source => $destination) {
            $path = $this->codeGenerator->renderPath($destination);
            $path = $this->packagePath($path);
            
            $stubs[] = new StubData(source: $source, destination: $path);
        }
        return $stubs;
    }

    public function chooseTemplate() : array {
        $templates = collect( config('generex.templates', []) );
        if($templates->count() == 0){
            throw new \Exception("Can't proceed without templates !");
        }
        if( $templates->count() == 1 ){
            return $templates->first();
        }
        $template = select(
            'Choose a template:',
            $templates->keys(),
        );
        return $templates->get($template);
    }

}