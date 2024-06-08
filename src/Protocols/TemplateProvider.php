<?php

namespace YassineDabbous\Generex\Protocols;

interface TemplateProvider
{
    /**
     * Get templates list.
     * 
     * @return array<\YassineDabbous\Generex\Protocols\StubData> 
    */
    public function stubs() : array;
}