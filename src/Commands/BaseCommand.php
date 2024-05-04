<?php

namespace Yaseen\PackGen\Commands;
 
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Yaseen\PackGen\Protocols\DataHolder;

/**
 * Class BaseCommand.
 */
abstract class BaseCommand extends Command
{    
    public DataHolder $dataHolder;
    
    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Build the options
     *
     * @return $this|array
     */
    protected function buildOptions()
    {

        $vendor = $this->option('vendor');
        if (! empty($vendor)) {
            $this->dataHolder->vendorName = strtolower($vendor);
        }
        
        $package = $this->option('package');
        if (! empty($package)) {
            $this->dataHolder->packageName = strtolower($package);
        }

        return $this;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the table'],
        ];
    }


    /**
     * Add local repository to composer.json
     */
    public function addPathRepository()
    {
        $localFolder = collect(explode('/', $this->modulesFolder()))->last(fn($v) => $v);
        $params = json_encode([
            'type' => 'path',
            'url' => "./$localFolder/*",
            'options' => [
                'symlink' => true,
            ],
        ]);
        $command = [
            'composer',
            'config',
            'repositories.'."{$this->dataHolder->vendorName}/{$this->dataHolder->packageName}",
            $params,
            '--file',
            'composer.json',
        ];

        return ! (new Process($command, $this->laravel->basePath(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }


    /**
     * install the new package.
     */
    public function installPackage()
    {
        $command = ['composer', 'require', "{$this->dataHolder->vendorName}/{$this->dataHolder->packageName}"];
        
        return ! (new Process($command, $this->laravel->basePath(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    
    protected function modulesFolder() : string {
        return config('packgen.root', base_path('modules/'));
    }

}
