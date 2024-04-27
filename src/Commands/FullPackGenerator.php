<?php

namespace Yaseen\PackGen\Commands;

/**
 * Class FullPackGenerator.
 *
 * @author  Yassine Dabbous <yassine.dabbous@gmail.com>
 */
class FullPackGenerator extends BaseCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'pack:gen
                            {name : Table name}
                            {--vendor= : Vendor name}
                            {--package= : Package name}
                            ';

    /**
     * The console command description.
     */
    protected $description = 'Generate a laravel package with WEB & API CRUD operations';


    
    public function handle() : bool|null
    {
        $this->info('Running Package Generator ...');

        $tableName = $this->getNameInput();
        if(!$this->validate($tableName)){
            return false;
        }
        
        // load vendor and package name
        $this->buildOptions();
        
        // generate model fields
        $this->generateFields($tableName);

        // generate code
        foreach($this->pathProvider->paths($this->dataHolder) as $stub => $path){
            $path = \Blade::render($path, ['o' => $this->dataHolder ]);
            $path = $this->packagePath($path);
            $name = basename($path, '.php');
            $ignore = false;
            $this->info($path);
            if($this->singleModule && in_array($name, ['composer.json', $this->dataHolder->serviceProviderClassName])){
                $ignore = true;
            }
            $this->generate($stub, $path, $ignore);
        }
        if($this->singleModule){
            $this->info('In Single Module mode, files such as "ServiceProvider" won\'t be automatically recreated and may need to be updated manually.');
        }

 
        if($this->ask('Package created successfully. \n Would you like to install it (y/n)?', 'n') != 'y'){
            return true;
        }
        
        // adding local repository to composer.json
        $this->addPathRepository();

        $this->installPackage();

        return true;
    }

    /**
     * Package path.
     */
    protected function packagePath(string $path = '') : string {
        return config('packgen.root', base_path('modules/')).$this->dataHolder->moduleName.'/'.$path;
    }
}
