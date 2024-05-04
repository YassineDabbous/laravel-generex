<?php

namespace Yaseen\PackGen\Services;

use Yaseen\PackGen\Protocols\CodeGenerator;
use Yaseen\PackGen\Protocols\DataHolder;
use Yaseen\PackGen\Protocols\StubData;
use function Laravel\Prompts\info;
use function Laravel\Prompts\confirm;
use Illuminate\Filesystem\Filesystem;

 class CodeGeneratorImp implements CodeGenerator
{
    
    public function __construct(
        public DataHolder $dataHolder,
        public Filesystem $fs,
    ){}
    
    /**
     * Handle file generation
     */
    public function handle(StubData $stub) : bool
    {
        
        if ( $this->fs->exists($stub->destination) ) {
            // don't overwrite files in single-module mode
            $isModuleFile = in_array($stub->sourceName, config('packgen.module_files', [
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
        $output = view($stub->source, ['o' => $this->dataHolder])->render();
        $this->write($stub->destination, $output);
        return true;
    }

    
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

}