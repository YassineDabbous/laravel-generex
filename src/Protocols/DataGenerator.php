<?php

namespace Yaseen\PackGen\Protocols;

 interface DataGenerator
{
    
    /**
     * Check if table exist or ask for schema file.
     */
    public function validate($tableName) : bool;
    
    /**
     * Generate fields.
     */
    public function generateFields($tableName);
}