<?php

namespace Yaseen\PackGen\Protocols;

interface PathProvider
{
    public function paths(DataHolder $dataHolder) : array;
}