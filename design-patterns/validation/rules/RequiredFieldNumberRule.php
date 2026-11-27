<?php

require_once __DIR__ . '/../contracts/ValidationRuleInterface.php';

class RequiredFieldNumberRule implements ValidationRuleInterface
{
    public function __construct(
        private string $field,
        private string $message
    ) {}

    public function validate(array $data): ?string
    {
        $value = $data[$this->field] ?? null;

        if (
            $value === null ||
            $value === '' ||
            !is_numeric($value) ||
            (int)$value <= 0
        ) {
            return $this->message;
        }

        return null;
    }
}