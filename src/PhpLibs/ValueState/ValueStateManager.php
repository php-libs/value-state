<?php


namespace PhpLibs\ValueState;


class ValueStateManager implements ValueStateProviderInterface
{
    /**
     * @var int[] fieldKey => ValueStateType id
     */
    private array $trackedValueStates = [];

    /**
     * fieldKey => ValueStateType id
     *
     * Cannot Include values that have not been at least initialized. Meaning, a value that was never touched is never
     * tracked.
     *
     * @return int[]
     */
    public function getTrackedValueStates() : array
    {
        return $this->trackedValueStates;
    }

    /**
     * Returns ValueStateTypes::NOT_YET_INITIALIZED to indicate that the value has never been seen
     *
     * @param string $fieldKey
     *
     * @return int ValueStateType Id
     */
    public function getValueState(string $fieldKey): int
    {
        if (!array_key_exists($fieldKey, $this->trackedValueStates)) {
            return ValueStateTypes::NOT_YET_INITIALIZED;
        }

        return $this->trackedValueStates[$fieldKey];
    }

    /**
     * True once a value is initialized, and continues to be true after a value is modified
     *
     * @param string $fieldKey
     *
     * @return bool
     */
    public function getValueWasInitialized(string $fieldKey): bool
    {
        if (!array_key_exists($fieldKey, $this->trackedValueStates)) {
            return false;
        }

        return ValueStateTypes::INITIALIZED_ID === $this->trackedValueStates[$fieldKey]
            || ValueStateTypes::MODIFIED_ID === $this->trackedValueStates[$fieldKey];
    }

    /**
     * True only if a value was changed after it was initialized
     *
     * @param string $fieldKey
     *
     * @return bool
     */
    public function getValueIsModified(string $fieldKey): bool
    {
        if (!array_key_exists($fieldKey, $this->trackedValueStates)) {
            return false;
        }

        return ValueStateTypes::MODIFIED_ID === $this->trackedValueStates[$fieldKey];
    }

    public function onBeforeAssignment(object $sourceObject, string $fieldKey, mixed $currentValue, mixed $newValue)
    {
        if (!array_key_exists($fieldKey, $this->trackedValueStates)) {
            $this->trackedValueStates[$fieldKey] = ValueStateTypes::INITIALIZED_ID;
        } else {
            $this->trackedValueStates[$fieldKey] = ValueStateTypes::MODIFIED_ID;
        }
    }
}
