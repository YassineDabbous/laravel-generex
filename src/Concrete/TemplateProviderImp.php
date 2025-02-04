<?php

namespace YassineDabbous\Generex\Concrete;

use Illuminate\Console\Command; 
use YassineDabbous\Generex\Generex;
use YassineDabbous\Generex\Helpers\HasLocalPackageInstaller;
use YassineDabbous\Generex\Helpers\Template;
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

    private Template $template;

    public function __construct(
        private DataHolder $dataHolder,
        private InputValidator $inputValidator,
        private CodeGenerator $codeGenerator,
        private DataGenerator $dataGenerator,
    ){}



    public function prepare(?string $template) {
        $this->template = $this->chooseTemplate($template);

        $dataHolderClass = $this->template->dataHolder;
        $this->dataHolder = $dataHolderClass ? new $dataHolderClass() : $this->dataHolder;

        $codeGeneratorClass = $this->template->codeGenerator;
        $this->codeGenerator = $codeGeneratorClass ? new $codeGeneratorClass() : $this->codeGenerator;
        $this->codeGenerator->initialize($this->dataHolder);

        $dataGeneratorClass = $this->template->dataGenerator;
        $this->dataGenerator = $dataGeneratorClass ? new $dataGeneratorClass() : $this->dataGenerator;
        $this->dataGenerator->initialize($this->dataHolder);

        $inputValidatorClass = $this->template->inputValidator;
        $this->inputValidator = $inputValidatorClass ? new $inputValidatorClass() : $this->inputValidator;
        $this->inputValidator->initialize($this->dataHolder);
    }

    public function chooseTemplate(?string $name) : Template {
        $templates = app(Generex::class)->getTemplates();
        if($templates->count() == 0){
            throw new \Exception("Can't proceed without templates !");
        }
        if($name){
            if($template = $templates->first(fn($t, $key) => $t->name == $name)){
                info("Using template: $name");
                return $template;
            }
            error("Provided template ($name) doesn't exist.");
        }
        if( $templates->count() == 1 ){
            $template = $templates->first();
            info("Using default template ({$template->name}).");
            return $template;
        }
        $name = select(
            'Choose a template:',
            $templates->pluck('name'),
        );
        return $templates->first(fn($t, $key) => $t->name == $name);
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

    public function getSignature(): string {
        return $this->inputValidator->signature();
    }
    
    public function validateInput(Command $command): bool {
        return $this->inputValidator->handle($command);
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


    public function preGenerating(): bool {
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
        
    public function generate(): bool{
        return $this->codeGenerator->handle($this->template->stubs);
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

    public function postGenerating(): bool {
        if($this->dataHolder->isSingleModule){
            info('In Single Module mode, files such as "ServiceProvider" won\'t be automatically recreated and may need to be updated manually.');
        }

        if(!confirm('Package created successfully. \n Would you like to install it (y/n)?', false) ){
            return true;
        }

        // adding local repository to composer.json
        $this->addPathRepository($this->modulesFolder(), $this->dataHolder->vendorName, $this->dataHolder->packageName);

        $this->installPackage($this->dataHolder->vendorName, $this->dataHolder->packageName);
        
        return true;
    }

    protected function modulesFolder() : string {
        return config('generex.root', base_path('modules/'));
    }

}