<?php

namespace YassineDabbous\Generex\Abstracts;

use YassineDabbous\Generex\Protocols\DataHolder;
use YassineDabbous\Generex\Protocols\InputValidator as Contract;

abstract class InputValidator implements Contract
{
    public ?DataHolder $dataHolder = null;

    /** Command Signature without a name. */
    protected $signature;
    
    public function initialize(DataHolder $dataHolder): void{
        $this->dataHolder = $dataHolder;
    }

    
    public function signature(): string {
        return $this->signature;
    }


}