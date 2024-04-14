<?php

namespace Yaseen\PackGen\Protocols;

use Illuminate\Support\Str;

class PathProviderImp implements PathProvider
{

    protected DataHolder $dataHolder;

    public function paths(DataHolder $dataHolder) : array {
        $this->dataHolder = $dataHolder;
        return [
            [
                'name' => 'composer',
                'stub' => 'packgen::default.composer',
                'path' => $this->packagePath('composer.json'),
            ],
            [
                'name' => 'Api Routes',
                'stub' => 'packgen::default.routes.api',
                'path' => $this->packagePath('routes/api.php'),
            ],
            [
                'name' => 'ServiceProvider',
                'stub' => 'packgen::default.src.ServiceProvider',
                'path' => $this->packageSrcPath($this->dataHolder->moduleName.'ServiceProvider.php'),
            ],
            [
                'name' => 'Policy',
                'stub' => 'packgen::default.src.Policy',
                'path' => $this->_getPolicyPath(),
            ],
            [
                'name' => 'ApiController',
                'stub' => 'packgen::default.src.ApiController',
                'path' => $this->_getControllerPath('Api'),
            ],
            // [
            //     'name' => 'WebController',
            //     'stub' => 'packgen::default.src.WebController',
            //     'path' => $this->_getControllerPath('Web'),
            // ],
            [
                'name' => 'QueryBuilder',
                'stub' => 'packgen::default.src.HasQueryBuilder',
                'path' => $this->packageSrcPath("Concerns/Has{$this->dataHolder->modelName}QueryBuilder.php"),
            ],
            [
                'name' => 'Request',
                'stub' => 'packgen::default.src.Request',
                'path' => $this->_getRequestPath(),
            ],
            [
                'name' => 'Model',
                'stub' => 'packgen::default.src.Model',
                'path' => $this->_getModelPath(),
            ],
        ];
    }


    
    
    /**
     * Get the path from namespace.
     */
    private function _getNamespacePath(string $namespace) : string
    {
        $str = Str::start(Str::finish(Str::after($namespace, $this->dataHolder->moduleName), '\\'), '\\');

        return str_replace('\\', '/', $str);
    }

    /**
     * Package path.
     */
    protected function packagePath(string $path = '') : string {
        return config('packgen.root', base_path('modules/')).$this->dataHolder->moduleName.'/'.$path;
    }

    /**
     * Package src folder path.
     */
    protected function packageSrcPath(string $path = '') : string {
        return $this->packagePath('src/'.$path);
    }
    
    /**
     * Package resources path.
     */
    protected function packageResourcesPath(string $path = '') : string {
        return $this->packagePath('resources/'.$path);
    }


    /**
     * Generate Controller file path from the name
     */
    protected function _getControllerPath($prefix = '') : string
    {
        return $this->packageSrcPath($this->_getNamespacePath($this->dataHolder->controllerNamespace)."{$this->dataHolder->modelName}{$prefix}Controller.php");
    }

    /**
     * Generate Request file path from the name
     */
    protected function _getRequestPath() : string
    {
        return $this->packageSrcPath($this->_getNamespacePath($this->dataHolder->requestNamespace)."{$this->dataHolder->modelName}Request.php");
    }

    /**
     * Model path from the name
     */
    protected function _getModelPath() : string
    {
        return $this->packageSrcPath($this->_getNamespacePath($this->dataHolder->modelNamespace)."{$this->dataHolder->modelName}.php");
    }

    /**
     * Policy path from the name
     */
    protected function _getPolicyPath() : string
    {
        return $this->packageSrcPath($this->_getNamespacePath($this->dataHolder->policyNamespace)."{$this->dataHolder->modelName}Policy.php");
    }

}