<?php

namespace YassineDabbous\Generex\Concrete;

use Illuminate\Console\Command;
use YassineDabbous\Generex\Protocols\DataHolder;
use YassineDabbous\Generex\Protocols\InputValidator;

class InputValidatorImp implements InputValidator
{
    
    public ?DataHolder $dataHolder = null;
    
    public function initialize(DataHolder $dataHolder): void{
        $this->dataHolder = $dataHolder;
    }


    /**
     * Gathering inputs.
     */
    public function handle(Command $command) : bool
    {
        $this->dataHolder->tableName = trim($command->argument('name'));

        $vendor = $command->option('vendor');
        if (! empty($vendor)) {
            $this->dataHolder->vendorName = strtolower($vendor);
        }

        $package = $command->option('package');
        if (! empty($package)) {
            $this->dataHolder->packageName = strtolower($package);
        }

        $connection = $command->option('connection');
        if (! empty($connection)) {
            $this->dataHolder->connectionName = $connection;
        }

        return true;
    }


}