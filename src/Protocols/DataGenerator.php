<?php

namespace YassineDabbous\Generex\Protocols;

 interface DataGenerator
{
    /** Initialize generator with data holder. */
    public function initialize(DataHolder $dataHolder);

    /** Handle generating data for templates. */
    public function handle() : bool;
}