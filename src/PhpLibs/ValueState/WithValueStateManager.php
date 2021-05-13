<?php

namespace PhpLibs\ValueState;

use PhpLibs\Observable\Observable;

trait WithValueStateManager
{
    abstract function addBeforeValueChangeObserver(
        callable $observerFunction,
        array $valueKeyFilters = [Observable::ALL_VALUES_KEYS],
        ?string $observerKey = null
    );

    protected ?ValueStateManager $_valueStateManager = null;

    /**
     * @return ValueStateManager
     */
    public function getValueStateManager(): ValueStateManager
    {
        return $this->_valueStateManager ?? new ValueStateManager();
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
        $this->getValueStateManager()->getTrackedValueStates();
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
        return $this->getValueStateManager()->getValueState($fieldKey);
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
        return $this->getValueStateManager()->getValueWasInitialized($fieldKey);
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
        return $this->getValueStateManager()->getValueIsModified($fieldKey);
    }

    protected function bindValueChangeStateTracking() : void
    {
        $func = function (object $sourceObject, string $fieldKey, mixed $currentValue, mixed $newValue) {
            $this->getValueStateManager()->onBeforeAssignment($sourceObject, $fieldKey, $currentValue, $newValue);
        };
        $this->addBeforeValueChangeObserver($func);
    }
}
