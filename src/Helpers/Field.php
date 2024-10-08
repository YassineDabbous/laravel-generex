<?php

namespace YassineDabbous\Generex\Helpers;

class Field
{ 
    public function __construct(
        public string $name,
        public string $dbType = 'varchar',
        public string $inputType = 'text',
        public ?string $castType = null,
        public bool $searchable = true,
        public bool $editable = true,
        public bool $inView = true,
        public bool $inGrid = true,
        public bool $nullable = false,
        public ?string $defaultValue = null,
        public ?string $comment = null,
        public ?string $migration = null,
    ){}

}
