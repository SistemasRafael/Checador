<?php

require_once __DIR__ . '/../contracts/ValidationRuleInterface.php';

class ConditionalRequiredFieldRule implements ValidationRuleInterface
{
    public function __construct(
        private string $field,
        private string $conditionField,
        private mixed $conditionValue,
        private string $message
    ) {}

    public function validate(array $data): ?string
    {
        if (($data[$this->conditionField] ?? null) != $this->conditionValue) {
            return null;
        }

        $value = $data[$this->field] ?? null;
        if (!is_array($value) || empty($value)) {
            return $this->message;
        }

        return null;
    }
}