<?php

namespace YassineDabbous\Generex\Helpers;

class Template
{
    public string $name;
    public array $stubs;
    public ?string $dataHolder;
    public ?string $inputValidator;
    public ?string $dataGenerator;
    public ?string $codeGenerator;

    public function __construct(string $name, array $template)
    {
        $this->name = $name;
        $this->stubs = $template['stubs'];
        $this->dataHolder = $template['data_holder'] ?? null;
        $this->inputValidator = $template['input_validator'] ?? null;
        $this->dataGenerator = $template['data_generator'] ?? null;
        $this->codeGenerator = $template['code_generator'] ?? null;
    }
}