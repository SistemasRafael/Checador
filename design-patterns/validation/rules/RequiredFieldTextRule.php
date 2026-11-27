<?php

require_once __DIR__ . '/../contracts/ValidationRuleInterface.php';

class RequiredFieldTextRule implements ValidationRuleInterface
{
    public function __construct(
        private string $field,
        private string $message
    ) {}

    public function validate(array $data): ?string
    {
        if (
            !isset($data[$this->field]) ||
            trim((string)$data[$this->field]) === ''
        ) {
            return $this->message;
        }

        return null;
    }
}