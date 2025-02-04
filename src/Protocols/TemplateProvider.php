<?php

namespace YassineDabbous\Generex\Protocols;
use Illuminate\Console\Command;

interface TemplateProvider
{

    /** prepare template */
    public function prepare(?string $template);

    public function getSignature(): string;

    public function validateInput(Command $command): bool;

    public function preGenerating(): bool;

    public function generate(): bool;

    public function postGenerating(): bool;
}