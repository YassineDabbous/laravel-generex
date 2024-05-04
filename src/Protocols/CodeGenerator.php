<?php

namespace Yaseen\PackGen\Protocols;

interface CodeGenerator
{
    /**
     * Generate files from templates
     */
    public function handle(StubData $stub) : bool;

}