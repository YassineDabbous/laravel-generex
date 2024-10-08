<?php

namespace YassineDabbous\Generex\Concrete;

use Illuminate\Console\Command;
use YassineDabbous\Generex\Abstracts\InputValidator;

class InputValidatorImp extends InputValidator
{

    /** Command Signature without the name. */
    protected $signature = '{table : Table name}
                            {--vendor= : Vendor name}
                            {--package= : Package name}
                            {--connection= : Connection name}';




    /** Gathering inputs. */
    public function handle(Command $command) : bool
    {
        $this->dataHolder->tableName = trim($command->argument('table'));

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