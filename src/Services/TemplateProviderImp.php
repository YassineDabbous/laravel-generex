<?php

namespace Yaseen\PackGen\Services;
use Yaseen\PackGen\Protocols\DataHolder;
use Yaseen\PackGen\Protocols\TemplateProvider;
use Yaseen\PackGen\Protocols\StubData;

use function Laravel\Prompts\select;

class TemplateProviderImp implements TemplateProvider
{
    public function __construct(
        private DataHolder $dataHolder,
    ){}

    /**
     * Package path.
     */
    protected function packagePath(string $path = '') : string {
        return config('packgen.root', base_path('modules/')).$this->dataHolder->moduleName.'/'.$path;
    }

    /**
     * @return array<\Yaseen\PackGen\Protocols\StubData> 
    */
    public function stubs() : array {    
        $paths = $this->chooseTemplate();
        $stubs = [];
        foreach ($paths as $source => $destination) {
            $path = \Blade::render($destination, ['o' => $this->dataHolder ]);
            $path = $this->packagePath($path);
            
            $stubs[] = new StubData(source: $source, destination: $path);
        }
        return $stubs;
    }

    public function chooseTemplate() : array {
        $templates = collect( config('packgen.templates', []) );
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