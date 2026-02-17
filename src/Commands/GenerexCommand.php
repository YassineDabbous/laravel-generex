<?php

namespace YassineDabbous\Generex\Commands;
use Illuminate\Console\Parser;
use YassineDabbous\Generex\Protocols\TemplateProvider;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerexCommand.
 *
 * @author  Yassine Dabbous <yassine.dabbous@gmail.com>
 */
class GenerexCommand extends Command
{
    protected $signature = 'generex {--template= : Template name}';
    protected $aliases = ['gen'];

    /**
     * The console command description.
     */
    protected $description = 'Generate a laravel package from templates.';
    

    /** Set signature from selected template. */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $templateProvider = app(TemplateProvider::class);

        $templateProvider->prepare($this->option('template'));
        $this->signature  .= " {$templateProvider->getSignature()}";

        // $this->ignoreValidationErrors = false; private in L12
        $this->reparseSignature();
        $input->bind($this->getDefinition());
        $this->input->validate();
    }

    
    protected function configure() {
        $this->ignoreValidationErrors();
    }



    
    /** Reparse template signature to add more arguments and options. */
    protected function reparseSignature()
    {
        [$name, $arguments, $options] = Parser::parse($this->signature);
        
        foreach($arguments as $argument) {
            if(!$this->hasArgument($argument->getName())) {
                $this->getDefinition()->addArgument($argument);
            }
        }
        foreach($options as $option) {
            if(!$this->hasOption($option->getName())) {
                $this->getDefinition()->addOption($option);
            }
        }
    }

    
    
    public function handle(TemplateProvider $templateProvider) : bool|null
    {
        $this->info('Running Package Generator ...');

        // get cmd input
        if(!$templateProvider->validateInput($this)){
            return false;
        }

        // before generating files
        if(!$templateProvider->preGenerating()){
            return false;
        }

        // generate files
        if(!$templateProvider->generate()){
            return false;
        }
        
        // after generating files
        if(!$templateProvider->postGenerating()){
            return false;
        }

        return true;
    }

}
