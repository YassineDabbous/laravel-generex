<?php

namespace Yaseen\PackGen\Protocols;

interface TemplateProvider
{
    /**
     * Get templates list.
     * 
     * @return array<\Yaseen\PackGen\Protocols\StubData> 
    */
    public function stubs() : array;
}