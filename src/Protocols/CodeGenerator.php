<?php

namespace YassineDabbous\Generex\Protocols;

interface CodeGenerator
{
    /** Initialize generator with data holder. */
    public function initialize(DataHolder $dataHolder);

    /** Handle files generating from stubs */
    public function handle(array $stubs) : bool;
}