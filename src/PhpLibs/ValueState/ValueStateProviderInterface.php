<?php

namespace PhpLibs\ValueState;

interface ValueStateProviderInterface
{
    public function getValueStateManager(): ValueStateManager;

    public function getTrackedValueStates(): array;

    public function getValueState(string $valueKey): int;
}