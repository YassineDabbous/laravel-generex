<?php

namespace YassineDabbous\Generex\Commands;
use YassineDabbous\Generex\Protocols\TemplateProvider;
use Illuminate\Console\Command;

/**
 * Class PackGeneratorCommand.
 *
 * @author  Yassine Dabbous <yassine.dabbous@gmail.com>
 */
class PackGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'gen:pack
                            {name : Table name}
                            {--template= : Template name}
                            {--vendor= : Vendor name}
                            {--package= : Package name}
                            {--connection= : Connection name}
                            ';

    /**
     * The console command description.
     */
    protected $description = 'Generate a laravel package with WEB & API CRUD operations';

    
    
    public function handle(TemplateProvider $templateProvider) : bool|null
    {
        $this->info('Running Package Generator ...');

        // choose template        
        $templateProvider->prepare($this->option('template'));

        // get cmd input
        $templateProvider->validateInput($this);


        // before generating files
        $templateProvider->preGenerating();

        // generate files
        $templateProvider->generate();
        
        // after generating files
        $templateProvider->postGenerating();

        return true;
    }

}
