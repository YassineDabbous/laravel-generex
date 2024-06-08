<?php

namespace YassineDabbous\Generex\Protocols;

interface CodeGenerator
{
    /** Generate files from templates */
    public function handle(StubData $stub) : bool;

    /** render stub content */
    public function renderContent(StubData $stub) : string;

    /** 
     * Render destination path.
     * Ex: /src/{Model}.php => /src/Post.php
     */
    public function renderPath(string $path) : string;

}