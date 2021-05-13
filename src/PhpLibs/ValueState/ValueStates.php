<?php


namespace PhpLibs\ValueState;


class ValueStates
{
    private const INITIALIZED = 1;
    private const MODIFIED = 2;

    private array $_valueStates = [];

    public function isSet(string $fieldKey) : bool
    {
        return array_key_exists($fieldKey, $this->_valueStates) &&
            $this->_valueStates[$fieldKey][static::INITIALIZED] === true;
    }

    public function isModifiedId(string $fieldKey) : bool
    {
        return array_key_exists($fieldKey, $this->_valueStates) &&
            $this->_valueStates[$fieldKey][static::MODIFIED] === true;
    }

    public function onBeforeAssignment(object $sourceObject, string $fieldKey, mixed $currentValue, mixed $newValue)
    {
        if (!array_key_exists($fieldKey, $this->_valueStates)) {
            $this->_valueStates[$fieldKey] = [
                static::INITIALIZED => true,
                static::MODIFIED => false,
            ];
        } else {
            $this->_valueStates[$fieldKey][static::MODIFIED] = true;
        }
    }
}
