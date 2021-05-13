<?php


namespace PhpLibs\ValueState;


class ValueStateManager
{
    /**
     * @var int[] fieldKey => ValueStateType id
     */
    private array $trackedValueStates = [];

    /**
     * An item is considered initialized once an assignment has taken place
     *
     * @param string $fieldKey
     *
     * @return bool
     */
    public function isInitialized(string $fieldKey) : bool
    {
        return ValueStateTypes::INITIALIZED_ID === $this->getValueState($fieldKey);
    }

    /**
     * Any item that is modified is also considered to be initialized
     *
     * @param string $fieldKey
     *
     * @return bool
     */
    public function isModified(string $fieldKey) : bool
    {
        return ValueStateTypes::MODIFIED_ID === $this->getValueState($fieldKey);
    }

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

    public function onBeforeAssignment(object $sourceObject, string $fieldKey, mixed $currentValue, mixed $newValue)
    {
        if (!array_key_exists($fieldKey, $this->trackedValueStates)) {
            $this->trackedValueStates[$fieldKey] = ValueStateTypes::INITIALIZED_ID;
        } else {
            $this->trackedValueStates[$fieldKey] = ValueStateTypes::MODIFIED_ID;
        }
    }
}
