<?php

namespace YassineDabbous\Generex\Abstracts;

use YassineDabbous\Generex\Protocols\CodeGenerator;
use YassineDabbous\Generex\Protocols\DataHolder;
use YassineDabbous\Generex\Helpers\StubData;
use function Laravel\Prompts\info;
use function Laravel\Prompts\confirm;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

abstract class CodeGeneratorImp implements CodeGenerator
{
    
    public ?DataHolder $dataHolder = null;
    public Filesystem $fs;
    
    public function __construct(){
        $this->fs =  app(Filesystem::class);
    }

    
    public function initialize(DataHolder $dataHolder): void{
        $this->dataHolder = $dataHolder;
    }


    
    /**
     * Handle file generation
     */
    public function handle(array $stubs) : bool
    {
        $stubs = $this->stubs($stubs);
        /** @var StubData $stub */
        foreach ($stubs as $stub) {
            $method = 'generate'.Str::studly($stub->sourceName);
            if(method_exists($this, $method)){
                $this->{$method}($stub);
                continue;
            }
            $this->generate($stub);
        }
        return true;
    }

    // /** Example of custom generation by template name (for ServiceProvider). */
    // public function generateServiceProvider(StubData $stub) : bool
    // {
    //     #
    //     # generate ServiceProvider file
    //     #
    //     return true;
    // }


    /**
     * Default file generation method
     */
    public function generate(StubData $stub) : bool
    {
        
        if ( $this->fs->exists($stub->destination) ) {
            // don't overwrite files in single-module mode
            $isModuleFile = in_array($stub->sourceName, config('generex.module_files', [
                'composer', 
                'ServiceProvider',
            ]));
            if($this->dataHolder->isSingleModule && $isModuleFile){
                return false;
            }

            // confirm overwriting other files
            if( !confirm($stub->destinationName.' already exist. Would you like to overwrite it?') ){
                return false;
            }
        }

        info("Creating $stub->destinationName ...");

        $this->write($stub->destination, $this->renderContent($stub));

        return true;
    }
    


    /** 
     * Render destination path.
     * Ex: /src/{Model}.php => /src/Post.php
     */
    abstract public function renderPath(string $path) : string;

    /** render stub content */
    abstract public function renderContent(StubData $stub) : string;



    
    /**
     * Write the contents of a file.
     */
    protected function write(string $path, string $content)
    {
        $directory = $this->fs->dirname($path);

        if (! $this->fs->isDirectory($directory)) {
            $this->fs->makeDirectory($directory, 0755, true);
        }

        $this->fs->put($path, $content);
    }


    
    
    /**
     * Get templates list.
     * 
     * @return array<\YassineDabbous\Generex\Protocols\StubData> 
    */
    public function stubs($paths) : array {
        // $paths = $this->template['stubs'];
        $stubs = [];
        foreach ($paths as $source => $destination) {
            $path = $this->renderPath($destination);
            $path = $this->packagePath($path);
            
            $stubs[] = new StubData(source: $source, destination: $path);
        }
        return $stubs;
    }
    

    /** Get local package path from config. */
    protected function packagePath(string $path = '') : string {
        return config('generex.root', base_path('modules/')).$this->dataHolder->moduleName.'/'.$path;
    }

}