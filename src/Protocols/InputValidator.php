<?php

namespace YassineDabbous\Generex\Protocols;
use Illuminate\Console\Command;

interface InputValidator
{
    /** Initialize generator with data holder. */
    public function initialize(DataHolder $dataHolder);

    /** The signature of the console command. */
    public function signature(): string;

    /** Get data from console args */
    public function handle(Command $command) : bool;
}