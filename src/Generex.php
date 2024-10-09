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
