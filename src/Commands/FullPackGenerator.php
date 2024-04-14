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

        //
        foreach($this->pathProvider->paths($this->dataHolder) as $o){
            $this->build($o['name'], $o['stub'], $o['path']);
        }

        // dump($this->dataHolder->names);

        $this->info('Created Successfully.');
 
        if($this->ask('Package created. Would you like to install it (y/n)?', 'n') == 'n'){
            return true;
        }
        
        // $this->info('Be sure to add local  Successfully.');
        // adding local repository to composer.json
        $this->addPathRepository();

        $this->installPackage();

        return true;
    }



}
