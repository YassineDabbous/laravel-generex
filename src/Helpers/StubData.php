<?php

namespace YassineDabbous\Generex\Helpers;

/** StubData class */
 class StubData
{
    public string $sourceName;
    public string $destinationName;
    public function __construct(
        public string $source,
        public string $destination,
    ){
        $this->destinationName = basename($destination, '.php');
        $fragments = explode('.', $source);
        $templateName = $fragments[array_key_last($fragments)];
        $this->sourceName = $templateName;
    }
}