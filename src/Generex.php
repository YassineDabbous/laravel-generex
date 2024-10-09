<?php

namespace YassineDabbous\Generex;

use Illuminate\Support\Collection;
use YassineDabbous\Generex\Helpers\Config;

class Generex
{
    public Config $config;

    public function __construct()
    {
        $this->config = $this->loadConfig();
    }

    public function loadConfig(): Config
    {
        $configArray = config('generex');
        return new Config($configArray);
    }

    /** Add a new template programmatically. */
    public function addTemplate(string $name, array $stubs, ?string $dataHolder = null, ?string $inputValidator = null, ?string $dataGenerator = null, ?string $codeGenerator = null): void
    {
        $templateDetails = [
            'stubs' => $stubs,
            'data_holder' => $dataHolder,
            'input_validator' => $inputValidator,
            'data_generator' => $dataGenerator,
            'code_generator' => $codeGenerator,
        ];

        $this->config->addTemplate($name, $templateDetails);
    }

    /** Remove an existing template by name. */
    public function removeTemplate(string $name): void
    {
        $this->config->removeTemplate($name);
    }

    /**
     * Get all templates to verify or manage them.
     * 
     * @return \Illuminate\Support\Collection<\YassineDabbous\Generex\Helpers\Template>
     */
    public function getTemplates(): Collection
    {
        return collect($this->config->templates);
    }
}
