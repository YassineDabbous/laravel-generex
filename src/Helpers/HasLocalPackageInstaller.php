<?php
namespace YassineDabbous\Generex\Helpers;

use Symfony\Component\Process\Process;

trait HasLocalPackageInstaller{
    
    /**
     * Add local repository to composer.json
     */
    public function addPathRepository(string $path, string $vendorName, string $packageName)
    {
        $localFolder = collect(explode('/', $path))->last(fn($v) => $v);
        $params = json_encode([
            'type' => 'path',
            'url' => "./$localFolder/*",
            'options' => [
                'symlink' => true,
            ],
        ]);
        $cmd = [
            'composer',
            'config',
            'repositories.'."{$vendorName}/{$packageName}",
            $params,
            '--file',
            'composer.json',
        ];

        return ! (new Process($cmd, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output){
                echo $output;
            });
    }
 

    /**
     * install the new package.
     */
    public function installPackage(string $vendorName, string $packageName)
    {
        $cmd = ['composer', 'require', "{$vendorName}/{$packageName}"];
        
        return ! (new Process($cmd, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output){
                echo $output;
            });
    }

    
}