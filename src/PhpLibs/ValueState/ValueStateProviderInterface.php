<?php

namespace PhpLibs\ValueState;

interface ValueStateProviderInterface
{
    public function getValueStates(): ValueStates;
}