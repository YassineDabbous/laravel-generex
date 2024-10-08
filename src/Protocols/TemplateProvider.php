<?php

namespace YassineDabbous\Generex\Protocols;
use Illuminate\Console\Command;

interface TemplateProvider
{

    /** prepare template */
    public function prepare(?string $template);

    public function validateInput(Command $command);

    public function preGenerating();

    public function generate();

    public function postGenerating();
}