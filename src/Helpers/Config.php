<?php

namespace YassineDabbous\Generex\Helpers;

class Config
{
    public string $templateProvider;
    public string $dataHolder;
    public string $inputValidator;
    public string $codeGenerator;
    public string $dataGenerator;
    public array $templates = [];

    public function __construct(array $config)
    {
        $this->templateProvider = $config['template_provider'];
        $this->dataHolder = $config['data_holder'];
        $this->inputValidator = $config['input_validator'];
        $this->codeGenerator = $config['code_generator'];
        $this->dataGenerator = $config['data_generator'];
        $this->templates = $this->mapTemplates($config['templates']);
    }

    /** Convert templates array to Template objects. */
    private function mapTemplates(array $templates): array
    {
        return array_map(function ($key, $template) {
            return new Template($key, $template);
        }, array_keys($templates), $templates);
    }

}