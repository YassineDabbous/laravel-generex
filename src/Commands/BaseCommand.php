<?php

namespace Yaseen\PackGen\Commands;
 
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Yaseen\PackGen\Protocols\DataHolder;
use Yaseen\PackGen\Protocols\DataHolderImp;
use Yaseen\PackGen\Protocols\PathProvider;
use Yaseen\PackGen\Protocols\PathProviderImp;

/**
 * Class BaseCommand.
 */
abstract class BaseCommand extends Command
{    
    protected Filesystem $fs;

    protected DataHolder $dataHolder;

    protected PathProvider $pathProvider;

    protected $schemaFile;

    protected $offline = false;

    protected $singleModule = false;
    
    public function __construct(Filesystem $fs)
    {
        parent::__construct();

        $this->fs = $fs;

        $this->singleModule = config('packgen.single_module', false);
        /**
         * @var string dataHolderClass;
         */
        $dataHolderClass = config('packgen.data_holder', DataHolderImp::class);
        $this->dataHolder = new $dataHolderClass;

        /**
         * @var string dataHolderClass;
         */
        $pathProvider = config('packgen.path_provider', PathProviderImp::class);
        $this->pathProvider = new $pathProvider;
    }



    
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
     * Check if table exist or ask for schema file.
     */
    public function validate($tableName) : bool {
        if (!Schema::hasTable($tableName)) {
            $this->offline = true;
            $this->warn("`{$tableName}` table doesn't exist");
        }

        $schemaFile = $this->schemaFolder().$tableName.'.json';
        if($this->fs->exists($schemaFile)){
            $this->schemaFile = $schemaFile;
        } elseif($this->offline){
            $this->schemaFile = $this->ask('Enter schema file path');
            if ($this->schemaFile == null || !$this->fs->exists($this->schemaFile)) {
                $this->error("File not found, Can't proceed without an existing table or a schema file");
                return false;
            }
        }

        return true;
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


    protected function modulesFolder() : string {
        return config('packgen.root', base_path('modules/'));
    }

    protected function schemaFolder() : string {
        return config('packgen.schemas_folder', resource_path('packgen_schemas/'));
    }



    /**
     * Generate files from stubs
     */
    protected function generate($stub, $path, $ignore = false) : static
    {
        $name = basename($path);
        if ($this->fs->exists($path) && ($ignore || $this->ask($name.' already exist. Would you like to overwrite it (y/n)?', 'n') != 'y')) {
            return $this;
        }

        $this->info("Creating $name ...");
        $template = view($stub, ['o' => $this->dataHolder]);
        $this->write($path, $template);

        return $this;
    }

 
    /**
     * Generate fields.
     */
    protected function generateFields($tableName)
    {
        $this->dataHolder->tableName = $tableName;
        if($this->schemaFile){
            $this->generateFieldsFromSchema();
        }
        if(!$this->offline){
            $this->generateFieldsFromDb();
        }
    }

    /**
     * Generate Model fields from schema file.
     */
    protected function generateFieldsFromSchema()
    {
        $fileContents = $this->fs->get($this->schemaFile);
        $jsonData = json_decode($fileContents, true);
        $fields = $jsonData['fields']; //array_merge_recursive($this->dataHolder->fields, $jsonData['fields']);
        
        foreach($fields as &$field) {
            if(!isset($field['name'])){
                $this->error('All fields should have names !');
                throw new \Exception("Some fields doesn't have a name, File: {$this->schemaFile}");
            }
            $unfillable = in_array($field['name'], $this->dataHolder->unfillable);
            $hidden = in_array($field['name'], $this->dataHolder->hidden);
            $field['searchable'] ??= $field['searchable'] ?? !$hidden;
            $field['editable'] ??= $field['editable'] ?? !$unfillable;
            $field['inView'] ??= $field['inView'] ?? !$hidden;
            $field['inGrid'] ??= $field['inView'] ?? !$hidden;
        }
        $this->dataHolder->fields = collect($fields);
    }
    
    /**
     * Generate Model fields from db table.
     */
    protected function generateFieldsFromDb()
    {

        $tableColumns = Schema::getColumns($this->dataHolder->tableName);
        $this->dataHolder->fields = $this->mergeColumnsToFields( $tableColumns );
        // dump($this->dataHolder->fields);
        return $this;
    }


    /**
     * merge local schema with DB table columns
     */
    protected function mergeColumnsToFields(array $tableColumns) : Collection {
        $fields = collect([]);
        $dbToHtml = fn($type) => match($type){
            'int', 'tinyint', 'bigint'                  => 'number',
            'char', 'varchar'                           => 'text',
            'text',                                     => 'textarea',
            'bool'                                      => 'checkbox',
            'date', 'datetime', 'timestamps'            => 'date',
            default => 'text'
        };
        $dbToCast = fn($type) => match($type){
            'json'                                      => 'array',
            'bool'                                      => 'boolean',
            'date', 'datetime', 'timestamps'            => 'date',
            default => null
        };

        foreach ($tableColumns as $column) {
            $field = ($this->dataHolder->fields ?? collect([]))->firstWhere('name', $column['name'])  ?? [];

            $unfillable = in_array($column['name'], $this->dataHolder->unfillable);
            $hidden = in_array($column['name'], $this->dataHolder->hidden);
            
            // fill absent keys
            $field['name'] ??= $column['name'];
            $field['dbType'] ??= $column['type_name'];
            $field['inputType'] ??= $dbToHtml($field['dbType']);
            $field['runtimeType'] ??= $dbToCast($field['dbType']);
            $field['searchable'] ??= $field['searchable'] ?? !$hidden;
            $field['editable'] ??= $field['editable'] ?? !$unfillable;
            $field['inView'] ??= $field['inView'] ?? !$hidden;
            $field['inGrid'] ??= $field['inView'] ?? !$hidden;
            
            // anticipating rules from column info
            $rules = $field['rules'] ?? [];

            if (!isset($rules['required']) && !$column['nullable']) {
                $rules[] = 'required';
            }
            if (!isset($rules['boolean']) && $column['type_name'] == 'bool') {
                $rules[] = 'boolean';
            }
            if (!isset($rules['uuid']) && $column['type_name'] == 'uuid') {
                $rules[] = 'uuid';
            }
            if (!isset($rules['uuid']) && ($column['type_name'] == 'text' || $column['type_name'] == 'varchar')) {
                $rules[] = 'string';
            }
            $field['rules'] = $rules;
            
            
            $fields->push($field);
        }
        return $fields;
    }



}
