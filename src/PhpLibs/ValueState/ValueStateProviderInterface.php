<?php

namespace PhpLibs\ValueState;

interface ValueStateProviderInterface
{
    public function getTrackedValueStates(): array;

    public function getValueState(string $valueKey): int;

    public function getValueWasInitialized(string $fieldKey): bool;

    public function getValueIsModified(string $fieldKey): bool;
}