<?php

namespace Yaseen\PackGen\Protocols;

class PathProviderImp implements PathProvider
{
    public function paths(DataHolder $dataHolder) : array {
        return config('packgen.stubs', []);
    }

}