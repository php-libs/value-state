<?php

namespace PhpLibs\ValueState;



trait WithValueStates
{
    abstract function addBeforeValueChangeObserver(
        callable $observerFunction,
        array $valueKeyFilters = [Observable::ALL_VALUES_KEYS],
        ?string $observerKey = null
    );

    public function getValueStates() : ValueStates
    {
        return $this->_valueStates ??= new ValueStates();
    }

    protected function bindValueChangeStateTracking() : void
    {
        $func = function (object $sourceObject, string $fieldKey, mixed $currentValue, mixed $newValue) {
            $this->getValueStates()->onBeforeAssignment($sourceObject, $fieldKey, $currentValue, $newValue);
        };
        $this->addBeforeValueChangeObserver($func);
    }
}
