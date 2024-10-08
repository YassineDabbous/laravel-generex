<?php

namespace YassineDabbous\Generex\Concrete;

use Illuminate\Console\Command; 
use YassineDabbous\Generex\Helpers\HasLocalPackageInstaller;
use YassineDabbous\Generex\Protocols\CodeGenerator;
use YassineDabbous\Generex\Protocols\DataGenerator;
use YassineDabbous\Generex\Protocols\DataHolder;
use YassineDabbous\Generex\Protocols\InputValidator;
use YassineDabbous\Generex\Protocols\TemplateProvider;

use function Laravel\Prompts\select;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\confirm;

class TemplateProviderImp implements TemplateProvider
{
    use HasLocalPackageInstaller;

    private array $template;

    public function __construct(
        private DataHolder $dataHolder,
        private InputValidator $inputValidator,
        private CodeGenerator $codeGenerator,
        private DataGenerator $dataGenerator,
    ){}



    public function prepare(?string $template) {
        $this->template = $this->chooseTemplate($template);
        $dataHolderClass = $this->template['data_holder'] ?? null;
        $this->dataHolder = $dataHolderClass ? new $dataHolderClass() : $this->dataHolder;

        $codeGeneratorClass = $this->template['code_generator'] ?? null;
        $this->codeGenerator = $codeGeneratorClass ? new $codeGeneratorClass() : $this->codeGenerator;
        $this->codeGenerator->initialize($this->dataHolder);

        $dataGeneratorClass = $this->template['data_generator'] ?? null;
        $this->dataGenerator = $dataGeneratorClass ? new $dataGeneratorClass() : $this->dataGenerator;
        $this->dataGenerator->initialize($this->dataHolder);

        $inputValidatorClass = $this->template['input_validator'] ?? null;
        $this->inputValidator = $inputValidatorClass ? new $inputValidatorClass() : $this->inputValidator;
        $this->inputValidator->initialize($this->dataHolder);
    }

    public function chooseTemplate(?string $template) : array {
        $templates = collect( config('generex.templates', []) );
        if($templates->count() == 0){
            throw new \Exception("Can't proceed without templates !");
        }
        if($template){
            if($templates->has($template)){
                return $templates->get($template);
            }
            error("Provided template ($template) doesn't exist.");
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




    //
    //
    //
    //
    //
    //
    //
    //
    //
    //-------------------------------------------------------
    //
    // GET CONSOLE INPUT
    //
    //-------------------------------------------------------
    //
    //
    //
    //
    //
    //
    //
    //
    //

    
    public function validateInput(Command $command) {
        $this->inputValidator->handle($command);
    }
    


    //
    //
    //
    //
    //
    //
    //
    //
    //
    //-------------------------------------------------------
    //
    // before generating files
    //
    //-------------------------------------------------------
    //
    //
    //
    //
    //
    //
    //
    //
    //


    public function preGenerating() {
        return $this->dataGenerator->handle();
    }


    //
    //
    //
    //
    //
    //
    //
    //
    //
    //-------------------------------------------------------
    //
    // GENERATE FILES
    //
    //-------------------------------------------------------
    //
    //
    //
    //
    //
    //
    //
    //
    //
        
    public function generate(){
        $this->codeGenerator->handle($this->template['stubs']);
    }
    
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //-------------------------------------------------------
    //
    // after generating files
    //
    //-------------------------------------------------------
    //
    //
    //
    //
    //
    //
    //
    //
    //

    public function postGenerating() {
        if($this->dataHolder->isSingleModule){
            info('In Single Module mode, files such as "ServiceProvider" won\'t be automatically recreated and may need to be updated manually.');
        }

        if(!confirm('Package created successfully. \n Would you like to install it (y/n)?', false) ){
            return true;
        }

        // adding local repository to composer.json
        $this->addPathRepository($this->modulesFolder(), $this->dataHolder->vendorName, $this->dataHolder->packageName);

        $this->installPackage($this->dataHolder->vendorName, $this->dataHolder->packageName);
    }

    protected function modulesFolder() : string {
        return config('generex.root', base_path('modules/'));
    }

}