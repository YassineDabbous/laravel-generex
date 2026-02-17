<?php

namespace YassineDabbous\Generex\Concrete;

use YassineDabbous\Generex\Helpers\Field;
use YassineDabbous\Generex\Protocols\DataHolder;

use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\error;
use function Laravel\Prompts\text;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use YassineDabbous\Generex\Protocols\DataGenerator;

class DataGeneratorImp implements DataGenerator
{
    protected ?DataHolder $dataHolder;
    protected Filesystem $fs;
    public ?string $schemaFile = null;
    public bool $tableExists = false;


    public function __construct(){
        $this->fs =  app(Filesystem::class);
    }


    public function initialize(DataHolder $dataHolder): void{
        $this->dataHolder = $dataHolder;
    }


    public function handle() : bool {
        if(!$this->validate()){
            return false;
        }

        // generate model fields
        $this->generateFields();
        return true;
    }

    
    
    /** Check if table exist or ask for schema file. */
    public function validate() : bool {
        $tableName = $this->dataHolder->tableName;
        $this->tableExists = Schema::connection($this->dataHolder->connectionName)->hasTable($tableName);
        if (! $this->tableExists) {
            warning("`{$tableName}` table doesn't exist");
        }

        $schemaFile = $this->schemaFolder().$tableName.'.json';
        if($this->fs->exists($schemaFile)){
            info("Using fields from schema: $schemaFile");
            $this->schemaFile = $schemaFile;
        } elseif(!$this->tableExists){
            $this->schemaFile = text('Enter schema file path', '/');
            if ($this->schemaFile == null || !$this->fs->exists($this->schemaFile)) {
                error("File not found, Can't proceed without a Database Table or a Schema File");
                return false;
            }
        }

        return true;
    }


    
    
    
    protected function schemaFolder() : string {
        return config('generex.schemas_folder', resource_path('generex_schemas/'));
    }
    
 
    /** Generate Model fields. */
    public function generateFields()
    {
        if($this->schemaFile){
            $this->generateFieldsFromSchema();
        }
        if($this->tableExists){
            $this->generateFieldsFromDb();
        }
    }

    /** Generate Model fields from schema file. */
    protected function generateFieldsFromSchema()
    {
        info("Using fields from schema: {$this->schemaFile}");
        $fileContents = $this->fs->get($this->schemaFile); //file_get_contents($this->schemaFile);
        $jsonData = json_decode($fileContents, true);
        $fieldsData = $jsonData['fields']; //array_merge_recursive($this->dataHolder->fields, $jsonData['fields']);
        $fields = collect();
        foreach($fieldsData as &$data) {
            if(!isset($data['name'])){
                throw new \Exception("Some fields doesn't have a name, File: {$this->schemaFile}");
            }

            $field = new Field($data['name']);

            $unfillable = in_array($field->name, $this->dataHolder->unfillable);
            $hidden = in_array($field->name, $this->dataHolder->hidden);
            $default = $this->dataHolder->defaults[$field->name] ?? null;
            
            $field->searchable = !$hidden;
            $field->editable = !$unfillable;
            $field->inView = !$hidden;
            $field->inGrid = !$hidden;
            $field->defaultValue = $default;
            $field->nullable = $data['nullable'] ?? false;
            $field->comment = $data['comment'] ?? null;
            $fields->push($field);
        }
        $this->dataHolder->fields = collect($fields);
    }
    
    /** Generate Model fields from db table. */
    protected function generateFieldsFromDb()
    {
        info("Using fields from table: {$this->dataHolder->connectionName}/{$this->dataHolder->tableName}");
        $tableColumns = Schema::connection($this->dataHolder->connectionName)->getColumns($this->dataHolder->tableName);
        $this->dataHolder->fields = $this->mergeColumnsToFields( $tableColumns );
        return $this;
    }


    /** Merge local schema with DB table columns. */
    protected function mergeColumnsToFields(array $tableColumns) : Collection {
        $fields = collect();
        $dbToHtml = fn($type) => match($type){
            'int', 'tinyint', 'bigint'                  => 'number',
            'char', 'varchar'                           => 'text',
            'text',                                     => 'textarea',
            'bool'                                      => 'checkbox',
            'date', 'datetime', 'timestamps'            => 'date',
            default => 'text'
        };

        foreach ($tableColumns as $data) {
            $field = ($this->dataHolder->fields ?? collect())->firstWhere(fn($f) => $f->name == $data['name'])  ?? new Field($data['name']);
    
            $unfillable = in_array($data['name'], $this->dataHolder->unfillable);
            $hidden = in_array($data['name'], $this->dataHolder->hidden);
            $default = $this->dataHolder->defaults[$data['name']] ?? null;
            
            // fill absent keys
            $field->dbType = $data['type_name'];
            $field->inputType = $dbToHtml($field->dbType);
            $field->castType = $this->generateCast($field);
            $field->searchable = !$hidden;
            $field->editable = !$unfillable;
            $field->inView = !$hidden;
            $field->inGrid = !$hidden;
            $field->defaultValue = $data['default'] ?? $default;
    
            $field->nullable = $data['nullable'] ?? false;
            $field->comment = $data['comment'] ?? null;
            
            // anticipating rules from data info
            $rules = $data['rules'] ?? [];
    
            if (!$field->nullable && is_null($field->defaultValue)) {
                $rules[] = 'required';
            }
            if($field->nullable) {
                $rules[] = 'nullable';
            }

            switch ($field->dbType) {
                case 'uuid':
                    $rules[] = 'uuid';
                    break;
                case 'bool':
                    $rules[] = 'boolean';
                    break;
                case 'tinyint':
                case 'bigint':
                case 'int':
                    $rules[] = 'integer';
                    break;
                case 'float':
                case 'double':
                case 'decimal':
                    $rules[] = 'numeric';
                    break;
                case 'json':
                    $rules[] = 'array';
                    break;
                case 'varchar':
                case 'text':
                    if(!isset($rules['uuid'])){
                        $rules[] = 'string';
                    }
            }
            
            $field->rules = array_unique($rules);
            
            $field->migration = $this->generateMigrationLine($field);

            $fields->push($field);
        }
        return $fields;
    }




    protected function generateCast(Field $field): ?string
    {
        $casts = config('generex.model.casts', []);
        foreach ($casts as $cast => $condition) {
            if (is_array($condition)) {
                if(in_array($field->name, $condition)){
                    return $cast;
                }
            }
            if ($condition instanceof \Closure) {
                if($condition($field)){
                    return $cast;
                }
            }
            if($field->name == $condition){
                return $cast;
            }
        }
        
        return null;
    }
    
    protected function generateMigrationLine(Field $field): string
    {
        if($field->name == 'id'){
            return '$table->id();';
        }
        if($field->name == 'deleted_at'){
            return '$table->softDeletes();';
        }
        $migrationText = '$table->';

        $migrationText .= match ($field->dbType) {
          'varchar' => "string('{$field->name}')",
          'bigint' => "bigInteger('{$field->name}')",
          'tinyint' => "tinyInteger('{$field->name}')",
          default => "{$field->dbType}('{$field->name}')",
        };
        

        if($field->nullable){
            $migrationText .= '->nullable()';
        }
        if(!is_null($field->defaultValue)){
            $v = trim($field->defaultValue,"' \n\r\t\v\x00");
            $v = is_numeric($v) ? $v : "'".$v."'";
            if($v != "'{}'"){ // mysql: json doesn't support default value
                $migrationText .= "->default($v)";
            }
        }
        if($field->comment){
            $migrationText .= '->comment(\''.$field->comment.'\')';
        }
        $migrationText .= ';';
        return $migrationText;
    }

}